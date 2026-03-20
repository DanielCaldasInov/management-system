<?php

namespace App\Http\Controllers;

use App\Mail\SupplierInvoiceMail;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($supplierInvoice->attachment_path) {
            Storage::disk('local')->delete($supplierInvoice->attachment_path);
        }

        $path = $request->file('attachment')->store('attachments', 'local');

        $supplierInvoice->update([
            'attachment_path' => $path,
        ]);

        return back();
    }

    public function downloadAttachment(Invoice $supplierInvoice)
    {
        if (! $supplierInvoice->attachment_path || ! Storage::disk('local')->exists($supplierInvoice->attachment_path)) {
            abort(404);
        }

        return Storage::disk('local')->response($supplierInvoice->attachment_path);
    }

    public function sendEmail(Invoice $supplierInvoice)
    {
        if ($supplierInvoice->type !== 'supplier') {
            abort(404);
        }

        if (! $supplierInvoice->entity->email) {
            return back()->with('error', 'This supplier does not have an email.');
        }

        Mail::to($supplierInvoice->entity->email)
            ->send(new SupplierInvoiceMail($supplierInvoice));

        if ($supplierInvoice->status === 'draft') {
            $supplierInvoice->update(['status' => 'pending']);
        }

        return back()->with('success', 'Email succesfully sent to '.$supplierInvoice->entity->email);
    }
}
