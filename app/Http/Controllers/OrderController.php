<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $orders = Order::with(['entity', 'quote'])
            ->where('type', 'customer')
            ->when($search, function ($query, $search) {
                $query->where('reference', 'like', "%$search%")
                    ->orWhereHas('entity', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['entity', 'lines.article', 'lines.supplier']);

        $suppliers = Entity::orderBy('name')->get();

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'suppliers' => $suppliers,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,closed',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Order status updated to '.ucfirst($validated['status']));
    }

    public function updateLines(Request $request, Order $order)
    {
        $validated = $request->validate([
            'lines' => 'required|array',
            'lines.*.id' => 'required|exists:order_lines,id',
            'lines.*.supplier_id' => 'nullable|exists:entities,id',
            'lines.*.cost_price' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['lines'] as $lineData) {
            $order->lines()->where('id', $lineData['id'])->update([
                'supplier_id' => $lineData['supplier_id'],
                'cost_price' => $lineData['cost_price'],
            ]);
        }

        return redirect()->back()->with('success', 'Suppliers and Cost Prices updated successfully!');
    }

    public function generateSupplierOrders(Order $order)
    {
        if ($order->status !== 'closed') {
            return redirect()->back()->with('error', 'Only closed orders can generate supplier orders.');
        }

        $order->load('lines');

        $linesWithSuppliers = $order->lines->whereNotNull('supplier_id');

        if ($linesWithSuppliers->isEmpty()) {
            return redirect()->back()->with('error', 'No suppliers assigned to any lines.');
        }

        $groupedLines = $linesWithSuppliers->groupBy('supplier_id');

        DB::transaction(function () use ($groupedLines, $order) {
            $year = date('Y');

            $lastOrder = Order::whereYear('created_at', $year)->latest('id')->first();
            $nextNumber = $lastOrder ? intval(substr($lastOrder->reference, -4)) + 1 : 1;

            foreach ($groupedLines as $supplierId => $lines) {
                $subtotal = 0;
                $vatTotal = 0;

                foreach ($lines as $line) {
                    $cost = $line->cost_price ?? 0;
                    $lineSubtotal = $line->quantity * $cost;
                    $lineVat = $lineSubtotal * ($line->vat_percentage / 100);

                    $subtotal += $lineSubtotal;
                    $vatTotal += $lineVat;
                }

                $reference = 'ENC-'.$year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;

                $supplierOrder = Order::create([
                    'type' => 'supplier',
                    'entity_id' => $supplierId,
                    'reference' => $reference,
                    'issue_date' => now()->format('Y-m-d'),
                    'status' => 'draft',
                    'subtotal' => $subtotal,
                    'vat_total' => $vatTotal,
                    'total' => $subtotal + $vatTotal,
                    'notes' => 'Gerado automaticamente a partir da Encomenda de Cliente: '.$order->reference,
                ]);

                foreach ($lines as $line) {
                    $cost = $line->cost_price ?? 0;
                    $lineSubtotal = $line->quantity * $cost;
                    $lineVat = $lineSubtotal * ($line->vat_percentage / 100);

                    $supplierOrder->lines()->create([
                        'article_id' => $line->article_id,
                        'supplier_id' => null,
                        'description' => $line->description,
                        'quantity' => $line->quantity,
                        'unit_price' => $cost,
                        'cost_price' => null,
                        'vat_percentage' => $line->vat_percentage,
                        'subtotal' => $lineSubtotal,
                        'vat_amount' => $lineVat,
                        'total' => $lineSubtotal + $lineVat,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Supplier Orders generated successfully!');
    }
}
