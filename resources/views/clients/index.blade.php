@extends('layouts.dashboard')

@section('page-title', 'Clients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary-brand">Active Clients</h1>
            <p class="mt-1 text-sm text-neutral-600">Manage and track your client relationships</p>
        </div>
        <div class="flex items-center gap-3">
            <x-ui.button variant="secondary" href="{{ route('clients.export', request()->query()) }}">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </x-ui.button>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-accent to-blue-600 text-white rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all font-semibold shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Client
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <x-ui.card :padding="false">
        <div class="p-6">
            <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search clients by company name, industry, or notes..."
                            class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary-accent text-white rounded-md hover:bg-blue-700 transition-colors">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'payment_status', 'company_size', 'industry', 'health_score']))
                        <a href="{{ route('clients.index') }}" class="px-6 py-3 bg-neutral-200 text-neutral-700 rounded-md hover:bg-neutral-300 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-neutral-700 mb-2">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="w-full px-4 py-2 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent">
                            <option value="">All Statuses</option>
                            <option value="current" {{ request('payment_status') === 'current' ? 'selected' : '' }}>Current</option>
                            <option value="overdue" {{ request('payment_status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="company_size" class="block text-sm font-medium text-neutral-700 mb-2">Company Size</label>
                        <select name="company_size" id="company_size" class="w-full px-4 py-2 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent">
                            <option value="">All Sizes</option>
                            <option value="1-10" {{ request('company_size') === '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="11-50" {{ request('company_size') === '11-50' ? 'selected' : '' }}>11-50 employees</option>
                            <option value="51-200" {{ request('company_size') === '51-200' ? 'selected' : '' }}>51-200 employees</option>
                            <option value="201-500" {{ request('company_size') === '201-500' ? 'selected' : '' }}>201-500 employees</option>
                            <option value="501-1000" {{ request('company_size') === '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                            <option value="1001+" {{ request('company_size') === '1001+' ? 'selected' : '' }}>1001+ employees</option>
                        </select>
                    </div>

                    <div>
                        <label for="health_score" class="block text-sm font-medium text-neutral-700 mb-2">Account Health</label>
                        <select name="health_score" id="health_score" class="w-full px-4 py-2 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent">
                            <option value="">All Scores</option>
                            <option value="high" {{ request('health_score') === 'high' ? 'selected' : '' }}>High (80-100)</option>
                            <option value="medium" {{ request('health_score') === 'medium' ? 'selected' : '' }}>Medium (50-79)</option>
                            <option value="low" {{ request('health_score') === 'low' ? 'selected' : '' }}>Low (0-49)</option>
                        </select>
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-medium text-neutral-700 mb-2">Industry</label>
                        <input
                            type="text"
                            name="industry"
                            id="industry"
                            value="{{ request('industry') }}"
                            placeholder="Filter by industry"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Clients Table -->
    <x-ui.card :padding="false">
        @if($clients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Industry</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Contract Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Health Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Renewal Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($clients as $client)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-primary-brand">
                                                <a href="{{ route('clients.show', $client) }}" class="hover:underline">
                                                    {{ $client->company_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-neutral-500">
                                                {{ $client->company_size ? $client->company_size . ' employees' : 'Size not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ $client->industry ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-900">
                                        {{ $client->contract_value ? '$' . number_format($client->contract_value, 2) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentStatusColors = [
                                            'current' => 'success',
                                            'overdue' => 'error',
                                            'cancelled' => 'default',
                                        ];
                                    @endphp
                                    <x-ui.badge :variant="$paymentStatusColors[$client->payment_status] ?? 'default'">
                                        {{ ucfirst($client->payment_status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($client->account_health_score)
                                        @php
                                            $healthColor = 'default';
                                            if ($client->account_health_score >= 80) {
                                                $healthColor = 'success';
                                            } elseif ($client->account_health_score >= 50) {
                                                $healthColor = 'warning';
                                            } else {
                                                $healthColor = 'error';
                                            }
                                        @endphp
                                        <x-ui.badge :variant="$healthColor">
                                            {{ $client->account_health_score }}%
                                        </x-ui.badge>
                                    @else
                                        <span class="text-sm text-neutral-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">
                                        {{ $client->renewal_date ? $client->renewal_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('clients.show', $client) }}" class="text-primary-accent hover:text-blue-700">
                                            View
                                        </a>
                                        @can('update', $client)
                                            <a href="{{ route('clients.edit', $client) }}" class="text-neutral-600 hover:text-neutral-900">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete', $client)
                                            <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-error hover:text-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $clients->links() }}
            </div>
        @else
            <div class="text-center py-12 px-4">
                <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-lg font-medium text-neutral-900 mb-2">No clients found</h3>
                <p class="text-neutral-500">
                    @if(request()->hasAny(['search', 'payment_status', 'company_size', 'industry', 'health_score']))
                        Try adjusting your filters or search query.
                    @else
                        Convert prospects to clients to see them here.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'payment_status', 'company_size', 'industry', 'health_score']))
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-primary-accent text-white rounded-md hover:bg-blue-700 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </x-ui.card>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-success text-white px-6 py-4 rounded-md shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
@endif
@endsection
