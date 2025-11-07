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

use App\Http\Requests\StoreProspectRequest;
use App\Http\Requests\UpdateProspectRequest;
use App\Models\Client;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Prospect::class, 'prospect');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Prospect::with(['user', 'assignedUser'])
            ->where(function ($query) use ($request) {
                // Show own prospects or all if has permission
                if (! $request->user()->can('view-all-prospects')) {
                    $query->where('user_id', $request->user()->id);
                }
            });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by industry
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $prospects = $query->paginate(15)->withQueryString();

        // Get unique industries and sources for filters
        $industries = Prospect::whereNotNull('industry')->distinct()->pluck('industry');
        $sources = Prospect::whereNotNull('source')->distinct()->pluck('source');

        return view('prospects.index', compact('prospects', 'industries', 'sources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        return view('prospects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProspectRequest $request)
    {
        $prospect = new Prospect($request->validated());
        $prospect->user_id = $request->user()->id;
        $prospect->save();

        return redirect()
            ->route('prospects.show', $prospect)
            ->with('success', 'Prospect created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prospect $prospect)
    {
        $prospect->load(['user', 'assignedUser', 'followUps' => function ($query) {
            $query->orderBy('due_date', 'asc');
        }]);

        return view('prospects.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prospect $prospect)
    {
        $users = User::all();

        return view('prospects.edit', compact('prospect', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProspectRequest $request, Prospect $prospect)
    {
        $prospect->update($request->validated());

        return redirect()
            ->route('prospects.show', $prospect)
            ->with('success', 'Prospect updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prospect $prospect)
    {
        $prospect->delete();

        return redirect()
            ->route('prospects.index')
            ->with('success', 'Prospect deleted successfully.');
    }

    /**
     * Convert a prospect to a client.
     */
    public function convert(Prospect $prospect)
    {
        // Check if user can update this prospect
        $this->authorize('update', $prospect);

        // Check if already converted
        if ($prospect->status === 'won' && $prospect->client) {
            return redirect()
                ->route('prospects.show', $prospect)
                ->with('error', 'This prospect has already been converted to a client.');
        }

        // Create client from prospect data
        $client = Client::create([
            'prospect_id' => $prospect->id,
            'user_id' => $prospect->user_id,
            'company_name' => $prospect->company_name,
            'industry' => $prospect->industry,
            'company_size' => $prospect->company_size,
            'payment_status' => 'current',
            'account_health_score' => 75, // Default starting score
            'notes' => $prospect->notes,
            'converted_at' => now(),
        ]);

        // Update prospect status to won
        $prospect->update(['status' => 'won']);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Prospect successfully converted to client!');
    }

    /**
     * Bulk delete prospects.
     */
    public function bulkDestroy(Request $request)
    {
        $this->authorize('viewAny', Prospect::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:prospects,id'],
        ]);

        $prospects = Prospect::whereIn('id', $request->ids)->get();

        // Check if user can delete each prospect
        foreach ($prospects as $prospect) {
            if (! $request->user()->can('delete', $prospect)) {
                return back()->with('error', 'You do not have permission to delete some of these prospects.');
            }
        }

        $count = $prospects->count();
        Prospect::whereIn('id', $request->ids)->delete();

        return back()->with('success', "{$count} prospect(s) deleted successfully.");
    }

    /**
     * Bulk update prospect status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $this->authorize('viewAny', Prospect::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:prospects,id'],
            'status' => ['required', 'in:new,contacted,qualified,proposal,negotiation,won,lost'],
        ]);

        $prospects = Prospect::whereIn('id', $request->ids)->get();

        // Check if user can update each prospect
        foreach ($prospects as $prospect) {
            if (! $request->user()->can('update', $prospect)) {
                return back()->with('error', 'You do not have permission to update some of these prospects.');
            }
        }

        $count = $prospects->count();
        Prospect::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return back()->with('success', "{$count} prospect(s) updated to ".ucfirst($request->status).'.');
    }

    /**
     * Bulk assign prospects to user.
     */
    public function bulkAssign(Request $request)
    {
        $this->authorize('viewAny', Prospect::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:prospects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $prospects = Prospect::whereIn('id', $request->ids)->get();

        // Check if user can update each prospect
        foreach ($prospects as $prospect) {
            if (! $request->user()->can('update', $prospect)) {
                return back()->with('error', 'You do not have permission to update some of these prospects.');
            }
        }

        $count = $prospects->count();
        Prospect::whereIn('id', $request->ids)->update(['assigned_to' => $request->assigned_to]);

        $assignedTo = $request->assigned_to ? User::find($request->assigned_to)->name : 'unassigned';

        return back()->with('success', "{$count} prospect(s) assigned to {$assignedTo}.");
    }

    /**
     * Export prospects to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Prospect::class);

        // Get prospects with same filtering as index
        $query = Prospect::with(['user', 'assignedUser'])
            ->where(function ($query) use ($request) {
                if (! $request->user()->can('view-all-prospects')) {
                    $query->where('user_id', $request->user()->id);
                }
            });

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $prospects = $query->get();

        // Generate CSV
        $filename = 'prospects_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($prospects) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Company Name',
                'Contact Name',
                'Email',
                'Phone',
                'Website',
                'Industry',
                'Company Size',
                'Annual Revenue',
                'City',
                'State',
                'Country',
                'Status',
                'Priority',
                'Source',
                'Conversion Probability',
                'Assigned To',
                'Next Follow-Up',
                'Created At',
            ]);

            // Data rows
            foreach ($prospects as $prospect) {
                fputcsv($file, [
                    $prospect->company_name,
                    $prospect->contact_name,
                    $prospect->email,
                    $prospect->phone,
                    $prospect->website,
                    $prospect->industry,
                    $prospect->company_size,
                    $prospect->annual_revenue,
                    $prospect->city,
                    $prospect->state,
                    $prospect->country,
                    $prospect->status,
                    $prospect->priority,
                    $prospect->source,
                    $prospect->conversion_probability,
                    $prospect->assignedUser?->name,
                    $prospect->next_follow_up_at?->format('Y-m-d H:i'),
                    $prospect->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
