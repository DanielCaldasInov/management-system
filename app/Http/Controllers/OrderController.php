<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Order;
use Illuminate\Http\Request;
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
}
