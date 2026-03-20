<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer'])
            ->latest();

        if ($request->search) {
            $query->whereHas('causer', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        if ($request->action) {
            $query->where('event', $request->action);
        }

        if ($request->model) {
            $modelMap = [
                'Entity' => 'App\Models\Entity',
                'Article' => 'App\Models\Article',
                'Order' => 'App\Models\Order',
                'Invoice' => 'App\Models\Invoice',
            ];

            if (isset($modelMap[$request->model])) {
                $query->where('subject_type', $modelMap[$request->model]);
            }
        }

        return Inertia::render('Logs/Index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['search', 'action', 'model']),
        ]);
    }
}
