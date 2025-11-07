<?php

/**
 * -----------------------------------------------------------------------------
 * GrowPath AI CRM - A modern, feature-rich Customer Relationship Management
 * (CRM) SaaS application built with Laravel 12, designed to help growing
 * businesses manage prospects, clients, and sales pipelines efficiently.
 * -----------------------------------------------------------------------------
 *
 * @author     Engr Mejba Ahmed
 *
 * @role       AI Developer • Software Engineer • Cloud DevOps
 *
 * @website    https://www.mejba.me
 *
 * @poweredBy  Ramlit Limited — https://ramlit.com
 *
 * @version    1.0.0
 *
 * @since      November 7, 2025
 *
 * @copyright  (c) 2025 Engr Mejba Ahmed
 * @license    Proprietary - All Rights Reserved
 *
 * Description:
 * GrowPath AI CRM is a comprehensive SaaS platform for customer relationship
 * management, featuring multi-tenancy, role-based access control, subscription
 * management with Stripe & PayPal integration, and advanced CRM capabilities
 * including prospect tracking, client management, and sales pipeline automation.
 *
 * Powered by Ramlit Limited.
 * -----------------------------------------------------------------------------
 */

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = auth()->user()->companies()->withCount('users')->get();
        $currentCompany = auth()->user()->currentCompany;

        return view('companies.index', compact('companies', 'currentCompany'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $company = Company::create($validated);

        // Attach current user as owner
        $company->users()->attach(auth()->id(), ['role' => 'owner']);

        // Set as current company if this is user's first company
        if (auth()->user()->companies()->count() == 1) {
            auth()->user()->update(['current_company_id' => $company->id]);
        }

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        $this->authorizeCompanyAccess($company);

        $company->load(['users', 'prospects', 'clients']);

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorizeCompanyAccess($company);

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeCompanyAccess($company);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $this->authorizeCompanyAccess($company);

        // Prevent deletion if it's the only company
        if (auth()->user()->companies()->count() <= 1) {
            return back()->with('error', 'Cannot delete your only company.');
        }

        // Switch to another company if deleting current company
        if (auth()->user()->current_company_id == $company->id) {
            $newCompany = auth()->user()->companies()->where('id', '!=', $company->id)->first();
            auth()->user()->update(['current_company_id' => $newCompany->id]);
        }

        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    public function switch(Company $company)
    {
        if (! auth()->user()->belongsToCompany($company)) {
            return back()->with('error', 'You do not have access to this company.');
        }

        auth()->user()->switchCompany($company);
        session(['tenant_id' => $company->id]);

        return redirect()->route('dashboard')
            ->with('success', "Switched to {$company->name}");
    }

    protected function authorizeCompanyAccess(Company $company)
    {
        if (! auth()->user()->belongsToCompany($company)) {
            abort(403, 'You do not have access to this company.');
        }
    }
}
