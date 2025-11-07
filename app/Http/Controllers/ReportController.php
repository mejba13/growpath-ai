<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FollowUp;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request)
    {
        // Check permission
        if (! $request->user()->can('view-reports')) {
            abort(403);
        }

        $user = $request->user();
        $canViewAll = $user->can('view-all-prospects');

        // Date range (default to last 30 days)
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Prospect metrics
        $prospectQuery = Prospect::query();
        if (! $canViewAll) {
            $prospectQuery->where('user_id', $user->id);
        }

        $prospectMetrics = [
            'total' => (clone $prospectQuery)->count(),
            'new_this_period' => (clone $prospectQuery)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'active' => (clone $prospectQuery)->active()->count(),
            'won' => (clone $prospectQuery)->where('status', 'won')->count(),
            'lost' => (clone $prospectQuery)->where('status', 'lost')->count(),
            'conversion_rate' => 0,
        ];

        $totalClosed = $prospectMetrics['won'] + $prospectMetrics['lost'];
        if ($totalClosed > 0) {
            $prospectMetrics['conversion_rate'] = ($prospectMetrics['won'] / $totalClosed) * 100;
        }

        // Prospects by status
        $prospectsByStatus = (clone $prospectQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Prospects by priority
        $prospectsByPriority = (clone $prospectQuery)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();

        // Prospects created over time (last 12 months)
        $prospectsOverTime = (clone $prospectQuery)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Client metrics
        $clientQuery = Client::query();
        if (! $canViewAll) {
            $clientQuery->where('user_id', $user->id);
        }

        $clientMetrics = [
            'total' => (clone $clientQuery)->count(),
            'new_this_period' => (clone $clientQuery)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_contract_value' => (clone $clientQuery)->sum('contract_value') ?? 0,
            'avg_health_score' => (clone $clientQuery)->avg('account_health_score') ?? 0,
            'current_payment' => (clone $clientQuery)->where('payment_status', 'current')->count(),
            'overdue_payment' => (clone $clientQuery)->where('payment_status', 'overdue')->count(),
        ];

        // Follow-up metrics
        $followUpQuery = FollowUp::query();
        if (! $canViewAll) {
            $followUpQuery->where('user_id', $user->id);
        }

        $followUpMetrics = [
            'total' => (clone $followUpQuery)->count(),
            'completed' => (clone $followUpQuery)->where('status', 'completed')->count(),
            'pending' => (clone $followUpQuery)->where('status', 'pending')->count(),
            'overdue' => (clone $followUpQuery)->overdue()->count(),
            'completion_rate' => 0,
        ];

        $totalFollowUps = $followUpMetrics['completed'] + $followUpMetrics['pending'];
        if ($totalFollowUps > 0) {
            $followUpMetrics['completion_rate'] = ($followUpMetrics['completed'] / $totalFollowUps) * 100;
        }

        // Top performing users (if can view all)
        $topPerformers = [];
        if ($canViewAll) {
            $topPerformers = Prospect::select('user_id', DB::raw('count(*) as total_prospects'))
                ->where('status', 'won')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('user_id')
                ->orderBy('total_prospects', 'desc')
                ->limit(5)
                ->with('user')
                ->get();
        }

        // Revenue projection
        $revenueProjection = (clone $prospectQuery)
            ->active()
            ->get()
            ->sum(function ($prospect) {
                return ($prospect->annual_revenue ?? 0) * (($prospect->conversion_probability ?? 0) / 100);
            });

        return view('reports.index', compact(
            'prospectMetrics',
            'prospectsByStatus',
            'prospectsByPriority',
            'prospectsOverTime',
            'clientMetrics',
            'followUpMetrics',
            'topPerformers',
            'revenueProjection',
            'startDate',
            'endDate'
        ));
    }
}
