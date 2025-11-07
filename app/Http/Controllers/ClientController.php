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

use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Client::class, 'client');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::with(['user', 'prospect'])
            ->where(function ($query) use ($request) {
                // If user doesn't have permission to view all clients, only show their own
                if (! $request->user()->can('view-all-clients')) {
                    $query->where('user_id', $request->user()->id);
                }
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Filter by company size
        if ($request->filled('company_size')) {
            $query->where('company_size', $request->input('company_size'));
        }

        // Filter by industry
        if ($request->filled('industry')) {
            $query->where('industry', 'like', "%{$request->input('industry')}%");
        }

        // Filter by account health
        if ($request->filled('health_score')) {
            $healthScore = $request->input('health_score');
            if ($healthScore === 'high') {
                $query->where('account_health_score', '>=', 80);
            } elseif ($healthScore === 'medium') {
                $query->whereBetween('account_health_score', [50, 79]);
            } elseif ($healthScore === 'low') {
                $query->where('account_health_score', '<', 50);
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSortFields = ['company_name', 'created_at', 'contract_value', 'renewal_date', 'account_health_score'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $clients = $query->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:255'],
            'contract_start_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date', 'after_or_equal:contract_start_date'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['nullable', 'string', 'in:current,overdue,pending'],
            'account_health_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'renewal_date' => ['nullable', 'date'],
            'services_purchased' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();

        $client = Client::create($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['user', 'prospect']);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Export clients to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        // Get clients with same filtering as index
        $query = Client::with(['user', 'prospect'])
            ->where(function ($query) use ($request) {
                if (! $request->user()->can('view-all-clients')) {
                    $query->where('user_id', $request->user()->id);
                }
            });

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('company_size')) {
            $query->where('company_size', $request->input('company_size'));
        }

        $clients = $query->get();

        // Generate CSV
        $filename = 'clients_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Company Name',
                'Industry',
                'Company Size',
                'Contract Start Date',
                'Contract End Date',
                'Contract Value',
                'Payment Status',
                'Account Health Score',
                'Renewal Date',
                'Services Purchased',
                'Converted At',
                'Created At',
            ]);

            // Data rows
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->company_name,
                    $client->industry,
                    $client->company_size,
                    $client->contract_start_date?->format('Y-m-d'),
                    $client->contract_end_date?->format('Y-m-d'),
                    $client->contract_value,
                    $client->payment_status,
                    $client->account_health_score,
                    $client->renewal_date?->format('Y-m-d'),
                    is_array($client->services_purchased) ? implode(', ', $client->services_purchased) : '',
                    $client->converted_at?->format('Y-m-d H:i'),
                    $client->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
