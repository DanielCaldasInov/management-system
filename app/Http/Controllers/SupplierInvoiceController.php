<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SupplierInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $invoices = Invoice::with('entity')
            ->where('type', 'supplier')
            ->when($search, function ($query, $search) {
                $query->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('entity', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('issue_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('SupplierInvoices/Index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Invoice $supplierInvoice)
    {
        if ($supplierInvoice->type !== 'supplier') {
            abort(404);
        }

        $supplierInvoice->load(['entity', 'lines.article']);

        return Inertia::render('SupplierInvoices/Show', [
            'invoice' => $supplierInvoice,
        ]);
    }

    public function uploadAttachment(Request $request, Invoice $supplierInvoice)
    {
        if ($supplierInvoice->type !== 'supplier') {
            abort(404);
        }

        $request->validate([
            'attachment' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Máx 5MB
        ]);

        if ($supplierInvoice->attachment_path) {
            Storage::disk('public')->delete($supplierInvoice->attachment_path);
        }

        $path = $request->file('attachment')->store('invoices/attachments', 'public');

        $supplierInvoice->update([
            'attachment_path' => $path,
        ]);

        return back()->with('success', 'Attachment uploaded successfully.');
    }
}
