<?php

/**
 * -----------------------------------------------------------------------------
 * GrowPath AI CRM - A modern, feature-rich Customer Relationship Management
 * (CRM) SaaS application built with Laravel 12, designed to help growing
 * businesses manage prospects, clients, and sales pipelines efficiently.
 * -----------------------------------------------------------------------------
 *
 * @package    GrowPath AI CRM
 * @author     Engr Mejba Ahmed
 * @role       AI Developer • Software Engineer • Cloud DevOps
 * @website    https://www.mejba.me
 * @poweredBy  Ramlit Limited — https://ramlit.com
 * @version    1.0.0
 * @since      November 7, 2025
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

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner|admin']);
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'plan', 'subscription', 'payments'])
            ->where('company_id', auth()->user()->current_company_id)
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_revenue' => Order::where('company_id', auth()->user()->current_company_id)
                ->where('status', 'completed')
                ->sum('total'),
            'total_orders' => Order::where('company_id', auth()->user()->current_company_id)->count(),
            'pending_orders' => Order::where('company_id', auth()->user()->current_company_id)
                ->where('status', 'pending')
                ->count(),
            'completed_orders' => Order::where('company_id', auth()->user()->current_company_id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if order belongs to current company
        if ($order->company_id !== auth()->user()->current_company_id) {
            abort(403);
        }

        $order->load(['user', 'plan', 'subscription', 'payments', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function update(Request $request, Order $order)
    {
        // Check if order belongs to current company
        if ($order->company_id !== auth()->user()->current_company_id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,refunded,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Order updated successfully.');
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::where('company_id', auth()->user()->current_company_id)
            ->with(['user', 'plan']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Order Number',
                'Customer',
                'Email',
                'Plan',
                'Amount',
                'Status',
                'Payment Method',
                'Date',
            ]);

            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->plan?->name,
                    '$' . number_format($order->total, 2),
                    ucfirst($order->status),
                    ucfirst($order->payment_method),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
