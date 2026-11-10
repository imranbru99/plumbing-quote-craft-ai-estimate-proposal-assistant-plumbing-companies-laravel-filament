<?php

namespace App\Services;

use App\Models\CatalogItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\ServiceRequest;
use App\Models\AuditLog;
use Illuminate\Support\Str;

class AiQuoteDrafter
{
    /**
     * Draft an itemized plumbing quote from a service request intake.
     * Uses local catalog + rule heuristics (AI Hub equivalent when remote keys absent).
     */
    public function draftFromRequest(ServiceRequest $request, ?int $userId = null): Quote
    {
        $lines = $this->suggestLineItems($request);
        $subtotal = collect($lines)->sum(fn ($l) => $l['quantity'] * $l['unit_price']);
        $taxRate = 0.0825;
        $tax = round($subtotal * $taxRate, 2);
        $total = round($subtotal + $tax, 2);

        $narrative = $this->buildNarrative($request, $lines, $total);

        $quote = Quote::query()->create([
            'service_request_id' => $request->id,
            'customer_id' => $request->customer_id,
            'created_by' => $userId,
            'quote_number' => 'PQC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'title' => 'Plumbing Estimate — ' . ($request->title ?: 'Service Request'),
            'status' => 'draft',
            'ai_summary' => $narrative,
            'ai_confidence' => $this->confidenceScore($request, $lines),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'valid_until' => now()->addDays(30)->toDateString(),
            'notes' => 'AI-drafted proposal pending human approval.',
        ]);

        foreach ($lines as $i => $line) {
            QuoteItem::query()->create([
                'quote_id' => $quote->id,
                'catalog_item_id' => $line['catalog_item_id'] ?? null,
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'unit' => $line['unit'],
                'unit_price' => $line['unit_price'],
                'line_total' => round($line['quantity'] * $line['unit_price'], 2),
                'sort_order' => $i + 1,
            ]);
        }

        $request->update(['status' => 'quoted']);

        AuditLog::record(
            'quote.ai_drafted',
            Quote::class,
            $quote->id,
            $userId,
            ['service_request_id' => $request->id, 'total' => $total, 'confidence' => $quote->ai_confidence]
        );

        return $quote->load('items');
    }

    protected function suggestLineItems(ServiceRequest $request): array
    {
        $catalog = CatalogItem::query()->where('is_active', true)->get();
        $text = strtolower(($request->title ?? '') . ' ' . ($request->description ?? '') . ' ' . ($request->job_type ?? ''));

        $matched = [];
        foreach ($catalog as $item) {
            $needles = array_filter(array_map('trim', explode(',', strtolower($item->keywords ?? ''))));
            $hit = false;
            foreach ($needles as $n) {
                if ($n !== '' && str_contains($text, $n)) {
                    $hit = true;
                    break;
                }
            }
            if ($hit || (str_contains($text, 'water heater') && str_contains(strtolower($item->name), 'water heater'))) {
                $matched[] = $item;
            }
        }

        if (count($matched) < 2) {
            $matched = $catalog->take(4)->all();
        }

        $lines = [];
        foreach ($matched as $item) {
            $qty = 1;
            if (str_contains(strtolower($item->category ?? ''), 'labor')) {
                $qty = max(2, (int) ceil(($request->estimated_hours ?? 4)));
            }
            if (str_contains(strtolower($item->name), 'fixture') || str_contains($text, 'fixture')) {
                $qty = max($qty, (int) ($request->outlet_count ?: 2));
            }
            $lines[] = [
                'catalog_item_id' => $item->id,
                'description' => $item->name . ($item->sku ? " ({$item->sku})" : ''),
                'quantity' => $qty,
                'unit' => $item->unit ?: 'ea',
                'unit_price' => (float) $item->unit_price,
            ];
        }

        if (str_contains($text, 'service') || str_contains($text, 'leak') || str_contains($text, 'clog') || empty($lines)) {
            $lines[] = [
                'catalog_item_id' => null,
                'description' => 'Service call / diagnostic visit',
                'quantity' => 1,
                'unit' => 'visit',
                'unit_price' => 119.00,
            ];
        }

        return $lines;
    }

    protected function buildNarrative(ServiceRequest $request, array $lines, float $total): string
    {
        $scope = $request->description ?: 'plumbing work as described in the intake.';
        $count = count($lines);

        return "Proposed scope for {$request->customer?->name}: {$scope} "
            . "This AI draft includes {$count} line items totaling \$" . number_format($total, 2) . ". "
            . "Labor assumptions use standard residential/commercial plumbing rates. "
            . "Permit fees, fixture upgrades, and unforeseen slab or sewer repairs may adjust final pricing. "
            . "Recommend site verification before customer delivery.";
    }

    protected function confidenceScore(ServiceRequest $request, array $lines): float
    {
        $score = 0.55;
        if (! empty($request->description)) {
            $score += 0.15;
        }
        if (! empty($request->job_type)) {
            $score += 0.1;
        }
        if (count($lines) >= 3) {
            $score += 0.1;
        }
        if ($request->estimated_hours) {
            $score += 0.05;
        }

        return min(0.95, round($score, 2));
    }
}
