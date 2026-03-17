<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function edit()
    {
        $company = Company::firstOrFail();

        return Inertia::render('Settings/Company', [
            'company' => $company,
        ]);
    }

    public function update(Request $request)
    {
        $company = Company::firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        $company->update($validated);

        return redirect()->back()->with('success', 'Company profile updated successfully.');
    }
}
