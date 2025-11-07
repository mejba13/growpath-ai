@extends('layouts.dashboard')

@section('page-title', 'Edit Client')

@section('content')
<div class="max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('clients.show', $client) }}" class="text-primary-accent hover:text-blue-600 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Client
        </a>
    </div>

    <x-ui.card title="Edit Client: {{ $client->company_name }}">
        <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div>
                <h3 class="text-lg font-semibold text-primary-brand mb-4">Company Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-neutral-700 mb-2">
                            Company Name <span class="text-error">*</span>
                        </label>
                        <input
                            type="text"
                            name="company_name"
                            id="company_name"
                            value="{{ old('company_name', $client->company_name) }}"
                            required
                            class="w-full px-4 py-3 border rounded-md @error('company_name') border-error @else border-neutral-300 @enderror focus:ring-3 focus:ring-primary-accent"
                        >
                        @error('company_name')
                            <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-medium text-neutral-700 mb-2">Industry</label>
                        <input
                            type="text"
                            name="industry"
                            id="industry"
                            value="{{ old('industry', $client->industry) }}"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>

                    <div>
                        <label for="company_size" class="block text-sm font-medium text-neutral-700 mb-2">Company Size</label>
                        <select name="company_size" id="company_size" class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent">
                            <option value="">Select size</option>
                            <option value="1-10" {{ old('company_size', $client->company_size) == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="11-50" {{ old('company_size', $client->company_size) == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                            <option value="51-200" {{ old('company_size', $client->company_size) == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                            <option value="201-500" {{ old('company_size', $client->company_size) == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                            <option value="501-1000" {{ old('company_size', $client->company_size) == '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                            <option value="1001+" {{ old('company_size', $client->company_size) == '1001+' ? 'selected' : '' }}>1001+ employees</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contract Information -->
            <div>
                <h3 class="text-lg font-semibold text-primary-brand mb-4">Contract Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contract_start_date" class="block text-sm font-medium text-neutral-700 mb-2">Contract Start Date</label>
                        <input
                            type="date"
                            name="contract_start_date"
                            id="contract_start_date"
                            value="{{ old('contract_start_date', $client->contract_start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>

                    <div>
                        <label for="contract_end_date" class="block text-sm font-medium text-neutral-700 mb-2">Contract End Date</label>
                        <input
                            type="date"
                            name="contract_end_date"
                            id="contract_end_date"
                            value="{{ old('contract_end_date', $client->contract_end_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border @error('contract_end_date') border-error @else border-neutral-300 @enderror rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                        @error('contract_end_date')
                            <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_value" class="block text-sm font-medium text-neutral-700 mb-2">Contract Value ($)</label>
                        <input
                            type="number"
                            name="contract_value"
                            id="contract_value"
                            value="{{ old('contract_value', $client->contract_value) }}"
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>

                    <div>
                        <label for="renewal_date" class="block text-sm font-medium text-neutral-700 mb-2">Renewal Date</label>
                        <input
                            type="date"
                            name="renewal_date"
                            id="renewal_date"
                            value="{{ old('renewal_date', $client->renewal_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-neutral-700 mb-2">Payment Status <span class="text-error">*</span></label>
                        <select name="payment_status" id="payment_status" required class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent">
                            <option value="current" {{ old('payment_status', $client->payment_status) == 'current' ? 'selected' : '' }}>Current</option>
                            <option value="overdue" {{ old('payment_status', $client->payment_status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="cancelled" {{ old('payment_status', $client->payment_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="account_health_score" class="block text-sm font-medium text-neutral-700 mb-2">Account Health Score (0-100)</label>
                        <input
                            type="number"
                            name="account_health_score"
                            id="account_health_score"
                            value="{{ old('account_health_score', $client->account_health_score) }}"
                            min="0"
                            max="100"
                            class="w-full px-4 py-3 border @error('account_health_score') border-error @else border-neutral-300 @enderror rounded-md focus:ring-3 focus:ring-primary-accent"
                        >
                        @error('account_health_score')
                            <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Services Purchased -->
            <div>
                <h3 class="text-lg font-semibold text-primary-brand mb-4">Services Purchased</h3>
                <div class="space-y-3">
                    <p class="text-sm text-neutral-600">Add services by entering them below (one per line or comma-separated)</p>
                    <textarea
                        name="services_purchased"
                        id="services_purchased"
                        rows="4"
                        placeholder="E.g., Web Development, SEO Optimization, Marketing Consulting"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                    >{{ old('services_purchased', is_array($client->services_purchased) ? implode(', ', $client->services_purchased) : '') }}</textarea>
                    <p class="text-xs text-neutral-500">Separate multiple services with commas</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <h3 class="text-lg font-semibold text-primary-brand mb-4">Additional Information</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-neutral-700 mb-2">Notes</label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-md focus:ring-3 focus:ring-primary-accent"
                    >{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
                @can('delete', $client)
                    <button
                        type="button"
                        onclick="if(confirm('Are you sure you want to delete this client? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                        class="px-6 py-3 text-sm font-semibold text-white bg-error rounded-xl hover:bg-error/90 transition-colors"
                    >
                        Delete Client
                    </button>
                @endcan
                <div class="flex items-center gap-4">
                    <x-ui.button variant="secondary" type="button" onclick="window.history.back()">
                        Cancel
                    </x-ui.button>
                    <x-ui.button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Client
                    </x-ui.button>
                </div>
            </div>
        </form>

        <!-- Delete Form (hidden) -->
        <form id="delete-form" method="POST" action="{{ route('clients.destroy', $client) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-ui.card>
</div>
@endsection
