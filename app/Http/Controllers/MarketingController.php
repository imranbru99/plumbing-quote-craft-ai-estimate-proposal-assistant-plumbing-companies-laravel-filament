<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\Quote;
use App\Models\ServiceRequest;
use App\Support\Module;
use App\Services\ProposalPdfGenerator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class MarketingController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'modules' => Module::all(),
            'stats' => [
                'quotes' => Quote::query()->count(),
                'intakes' => ServiceRequest::query()->count(),
                'catalog' => CatalogItem::query()->count(),
                'modules_on' => collect(Module::all())->where('enabled', true)->count(),
            ],
        ]);
    }

    public function features(): View
    {
        return view('pages.features', ['modules' => Module::all()]);
    }

    public function workflow(): View
    {
        return view('pages.workflow');
    }

    public function pricing(): View
    {
        return view('pages.pricing');
    }

    public function demo(): View
    {
        return view('pages.demo');
    }

    public function modules(): View
    {
        return view('pages.modules', ['modules' => Module::all()]);
    }

    public function proposal(string $slug): Response
    {
        $quote = Quote::query()->where('slug', $slug)->with(['items', 'customer'])->firstOrFail();
        $html = app(ProposalPdfGenerator::class)->renderHtml($quote);

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}