<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    /**
     * Display the pipeline board.
     */
    public function index(Request $request)
    {
        // Check permission
        if (! $request->user()->can('view-pipeline')) {
            abort(403);
        }

        // Get prospects based on permission
        $query = Prospect::with(['user', 'assignedUser']);

        if (! $request->user()->can('view-all-prospects')) {
            $query->where('user_id', $request->user()->id);
        }

        // Exclude won/lost by default unless filter is applied
        if (! $request->filled('show_closed')) {
            $query->active();
        }

        // Get all prospects and group by status
        $allProspects = $query->get();

        $pipeline = [
            'new' => $allProspects->where('status', 'new'),
            'contacted' => $allProspects->where('status', 'contacted'),
            'qualified' => $allProspects->where('status', 'qualified'),
            'proposal' => $allProspects->where('status', 'proposal'),
            'negotiation' => $allProspects->where('status', 'negotiation'),
            'won' => $allProspects->where('status', 'won'),
            'lost' => $allProspects->where('status', 'lost'),
        ];

        // Calculate pipeline metrics
        $metrics = [
            'total_active' => $allProspects->whereIn('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation'])->count(),
            'total_value' => $allProspects->whereIn('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation'])->sum('annual_revenue'),
            'weighted_value' => $allProspects->whereIn('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation'])->sum(function ($prospect) {
                return ($prospect->annual_revenue ?? 0) * (($prospect->conversion_probability ?? 0) / 100);
            }),
            'win_rate' => $allProspects->whereIn('status', ['won', 'lost'])->count() > 0
                ? ($allProspects->where('status', 'won')->count() / $allProspects->whereIn('status', ['won', 'lost'])->count() * 100)
                : 0,
        ];

        return view('pipeline.index', compact('pipeline', 'metrics'));
    }

    /**
     * Update prospect status via AJAX for drag-and-drop.
     */
    public function updateStatus(Request $request, Prospect $prospect)
    {
        $this->authorize('update', $prospect);

        $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,proposal,negotiation,won,lost'],
        ]);

        $prospect->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Prospect status updated successfully.',
        ]);
    }
}
