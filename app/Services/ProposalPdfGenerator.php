<?php

namespace App\Services;

use App\Models\Quote;

class ProposalPdfGenerator
{
    /**
     * Lightweight Unicode-safe HTML proposal (laravel-unicode-pdf equivalent).
     */
    public function renderHtml(Quote $quote): string
    {
        $quote->loadMissing(['items', 'customer', 'serviceRequest']);
        $rows = '';
        foreach ($quote->items as $item) {
            $rows .= '<tr>'
                . '<td>' . e($item->description) . '</td>'
                . '<td style="text-align:right">' . e($item->quantity) . '</td>'
                . '<td>' . e($item->unit) . '</td>'
                . '<td style="text-align:right">$' . number_format((float) $item->unit_price, 2) . '</td>'
                . '<td style="text-align:right">$' . number_format((float) $item->line_total, 2) . '</td>'
                . '</tr>';
        }

        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' . e($quote->quote_number) . '</title>'
            . '<style>body{font-family:DejaVu Sans,Arial,sans-serif;color:#1a1a1a;padding:32px}'
            . 'h1{color:#0b3d2e}table{width:100%;border-collapse:collapse;margin-top:24px}'
            . 'th,td{border:1px solid #ddd;padding:8px;font-size:13px}th{background:#0b3d2e;color:#fff}'
            . '.meta{color:#555;margin:8px 0}.total{font-size:18px;font-weight:bold;margin-top:16px}</style></head><body>'
            . '<h1>PlumbingQuoteCraft Proposal</h1>'
            . '<p class="meta"><strong>' . e($quote->quote_number) . '</strong> — ' . e($quote->title) . '</p>'
            . '<p class="meta">Customer: ' . e($quote->customer?->name ?? 'N/A') . '</p>'
            . '<p class="meta">Valid until: ' . e($quote->valid_until) . '</p>'
            . '<p>' . nl2br(e($quote->ai_summary ?? '')) . '</p>'
            . '<table><thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Total</th></tr></thead>'
            . '<tbody>' . $rows . '</tbody></table>'
            . '<p class="total">Subtotal: $' . number_format((float) $quote->subtotal, 2)
            . ' | Tax: $' . number_format((float) $quote->tax, 2)
            . ' | Total: $' . number_format((float) $quote->total, 2) . '</p>'
            . '</body></html>';
    }
}