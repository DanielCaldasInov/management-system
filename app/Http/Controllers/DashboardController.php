<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Order;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('type', 'customer')->sum('total');
        $totalExpenses = Invoice::where('type', 'supplier')->sum('total');

        $pendingInvoicesCount = Invoice::where('type', 'supplier')->where('status', 'pending')->count();
        $pendingInvoicesTotal = Invoice::where('type', 'supplier')->where('status', 'pending')->sum('total');

        $totalCustomers = Entity::where('is_customer', true)->count();

        $recentOrders = Order::with('entity')
            ->where('type', 'customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentInvoices = Invoice::with('entity')
            ->where('type', 'supplier')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'metrics' => [
                'revenue' => (float) $totalRevenue,
                'expenses' => (float) $totalExpenses,
                'pendingCount' => $pendingInvoicesCount,
                'pendingTotal' => (float) $pendingInvoicesTotal,
                'customers' => $totalCustomers,
            ],
            'recentOrders' => $recentOrders,
            'recentInvoices' => $recentInvoices,
        ]);
    }
}
