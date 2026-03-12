<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntityController extends Controller
{
    public function index(Request $request)
    {
        $query = Entity::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $searchField = $request->input('searchField', 'all');

            $query->where(function ($q) use ($search, $searchField) {
                if ($searchField === 'name') {
                    $q->where('name', 'like', "%{$search}%");
                } elseif ($searchField === 'vat_number') {
                    $q->where('vat_number', 'like', "%{$search}%");
                } else {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('vat_number', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'customer') {
                $query->where('is_customer', true);
            } elseif ($request->type === 'supplier') {
                $query->where('is_supplier', true);
            }
        }

        $sortField = $request->input('sortField', 'created_at');
        $sortDirection = $request->input('sortDirection', 'desc');

        $allowedSorts = ['vat_number', 'name', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $entities = $query->paginate(10)->withQueryString();

        return Inertia::render('Entities/Index', [
            'entities' => $entities,
            'filters' => $request->only(['search', 'searchField', 'type', 'sortField', 'sortDirection']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Entities/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
            'vat_number' => 'required|string|unique:entities,vat_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'notes' => 'nullable|string',
            'gdpr_consent' => 'boolean',
        ]);

        $lastEntity = Entity::orderBy('number', 'desc')->first();
        $validated['number'] = $lastEntity ? $lastEntity->number + 1 : 1;

        Entity::create($validated);

        return redirect()->route('entities.index')->with('success', 'Entity created successfully.');
    }

    public function show(Entity $entity)
    {
        return Inertia::render('Entities/Show', [
            'entity' => $entity
        ]);
    }

    public function edit(Entity $entity)
    {
        return Inertia::render('Entities/Edit', [
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, Entity $entity)
    {
        $validated = $request->validate([
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
            'vat_number' => 'required|string|unique:entities,vat_number,'.$entity->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'notes' => 'nullable|string',
            'gdpr_consent' => 'boolean',
        ]);

        $entity->update($validated);

        return redirect()->route('entities.index')->with('success', 'Entity updated successfully.');
    }

    public function destroy(Entity $entity)
    {
        $entity->delete();

        return redirect()->route('entities.index')->with('success', 'Entity deleted successfully.');
    }
}
