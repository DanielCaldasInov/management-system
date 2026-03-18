<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $orders = Order::with('entity')
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
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('SupplierOrders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Order $supplierOrder)
    {
        if ($supplierOrder->type !== 'supplier') {
            abort(404);
        }

        $supplierOrder->load(['entity', 'lines.article']);

        return Inertia::render('SupplierOrders/Show', [
            'order' => $supplierOrder,
        ]);
    }

    public function downloadPdf(Order $supplierOrder)
    {
        if ($supplierOrder->type !== 'supplier') {
            abort(404);
        }

        $supplierOrder->load(['entity', 'lines']);
        $company = Company::first();

        $data = [
            'quote' => $supplierOrder,
            'company' => $company,
            'document_title' => 'PURCHASE ORDER',
        ];

        $pdf = Pdf::loadView('pdf.quote', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Purchase_Order_'.$supplierOrder->reference.'.pdf');
    }
}
