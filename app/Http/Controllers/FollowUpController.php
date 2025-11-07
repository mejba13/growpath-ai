<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowUpRequest;
use App\Http\Requests\UpdateFollowUpRequest;
use App\Models\FollowUp;
use App\Models\Prospect;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(FollowUp::class, 'follow_up');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FollowUp::with(['user', 'prospect'])
            ->where('user_id', $request->user()->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter by date range
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            if ($dateFilter === 'today') {
                $query->whereDate('due_date', today());
            } elseif ($dateFilter === 'week') {
                $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'overdue') {
                $query->where('status', 'pending')
                    ->where('due_date', '<', now());
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('prospect', function ($pq) use ($search) {
                        $pq->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'due_date');
        $sortDirection = $request->input('sort_direction', 'asc');

        $allowedSortFields = ['due_date', 'created_at', 'priority', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $followUps = $query->paginate(15)->withQueryString();

        // Get counts for quick stats
        $stats = [
            'total' => FollowUp::where('user_id', $request->user()->id)->count(),
            'today' => FollowUp::where('user_id', $request->user()->id)->dueToday()->count(),
            'overdue' => FollowUp::where('user_id', $request->user()->id)->overdue()->count(),
            'pending' => FollowUp::where('user_id', $request->user()->id)->pending()->count(),
        ];

        return view('follow-ups.index', compact('followUps', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $prospects = Prospect::where('user_id', $request->user()->id)
            ->active()
            ->orderBy('company_name')
            ->get();

        // Pre-select prospect if provided in query string
        $selectedProspectId = $request->query('prospect_id');

        return view('follow-ups.create', compact('prospects', 'selectedProspectId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpRequest $request)
    {
        $followUp = new FollowUp($request->validated());
        $followUp->user_id = $request->user()->id;
        $followUp->save();

        // Redirect based on context
        if ($request->input('redirect_to') === 'prospect' && $followUp->prospect_id) {
            return redirect()
                ->route('prospects.show', $followUp->prospect_id)
                ->with('success', 'Follow-up created successfully.');
        }

        return redirect()
            ->route('follow-ups.index')
            ->with('success', 'Follow-up created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, FollowUp $followUp)
    {
        $prospects = Prospect::where('user_id', $request->user()->id)
            ->active()
            ->orderBy('company_name')
            ->get();

        return view('follow-ups.edit', compact('followUp', 'prospects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowUpRequest $request, FollowUp $followUp)
    {
        $followUp->update($request->validated());

        // Redirect based on context
        if ($request->input('redirect_to') === 'prospect' && $followUp->prospect_id) {
            return redirect()
                ->route('prospects.show', $followUp->prospect_id)
                ->with('success', 'Follow-up updated successfully.');
        }

        return redirect()
            ->route('follow-ups.index')
            ->with('success', 'Follow-up updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();

        return redirect()
            ->route('follow-ups.index')
            ->with('success', 'Follow-up deleted successfully.');
    }

    /**
     * Mark a follow-up as completed.
     */
    public function complete(Request $request, FollowUp $followUp)
    {
        $this->authorize('update', $followUp);

        $followUp->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome_notes' => $request->input('outcome_notes'),
        ]);

        return back()->with('success', 'Follow-up marked as completed.');
    }
}
