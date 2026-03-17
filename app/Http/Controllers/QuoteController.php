<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Company;
use App\Models\Entity;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::with('entity')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes,
        ]);
    }

    public function show(Quote $quote)
    {
        $quote->load(['entity', 'lines']);

        return Inertia::render('Quotes/Show', [
            'quote' => $quote,
        ]);
    }

    public function create()
    {
        $customers = Entity::orderBy('name')->get();

        $articles = Article::where('is_active', true)->orderBy('name')->get();

        $year = date('Y');
        $lastQuote = Quote::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $lastQuote ? intval(substr($lastQuote->reference, -4)) + 1 : 1;
        $reference = 'PROP-'.$year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return Inertia::render('Quotes/Create', [
            'customers' => $customers,
            'articles' => $articles,
            'defaultReference' => $reference,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'reference' => 'required|string|unique:quotes,reference',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
            'subtotal' => 'required|numeric',
            'vat_total' => 'required|numeric',
            'total' => 'required|numeric',
            'lines' => 'required|array|min:1',
            'lines.*.article_id' => 'nullable|exists:articles,id',
            'lines.*.description' => 'required|string',
            'lines.*.quantity' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.vat_percentage' => 'required|numeric|min:0',
            'lines.*.subtotal' => 'required|numeric',
            'lines.*.vat_amount' => 'required|numeric',
            'lines.*.total' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            $quote = Quote::create([
                'entity_id' => $validated['entity_id'],
                'reference' => $validated['reference'],
                'issue_date' => $validated['issue_date'],
                'valid_until' => $validated['valid_until'],
                'status' => 'draft',
                'subtotal' => $validated['subtotal'],
                'vat_total' => $validated['vat_total'],
                'total' => $validated['total'],
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['lines'] as $line) {
                $quote->lines()->create([
                    'article_id' => $line['article_id'],
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'vat_percentage' => $line['vat_percentage'],
                    'subtotal' => $line['subtotal'],
                    'vat_amount' => $line['vat_amount'],
                    'total' => $line['total'],
                ]);
            }
        });

        return redirect()->route('quotes.index')->with('success', 'Quote created successfully.');
    }

    public function updateStatus(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected',
        ]);

        $quote->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Quote status updated to '.ucfirst($validated['status']));
    }

    public function downloadPdf(Quote $quote)
    {
        $quote->load(['entity', 'lines']);
        $company = Company::first();

        $data = [
            'quote' => $quote,
            'company' => $company,
        ];

        $pdf = Pdf::loadView('pdf.quote', $data);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Quote_'.$quote->reference.'.pdf');
    }
}
