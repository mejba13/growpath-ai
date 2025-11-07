@extends('layouts.dashboard')

@section('page-title', 'Create Client')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header with Back Button -->
        <div class="mb-8">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center text-neutral-600 hover:text-primary-accent transition-colors mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Clients
            </a>
            <h2 class="text-3xl font-bold text-primary-brand">Create New Client</h2>
            <p class="mt-2 text-neutral-600">Add a new client to your CRM</p>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('clients.store') }}">
                @csrf

                <div class="p-8 space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Company Name <span class="text-error">*</span>
                        </label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            value="{{ old('company_name') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('company_name') border-error @enderror"
                            placeholder="Enter company name"
                        >
                        @error('company_name')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Industry and Company Size -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Industry -->
                        <div>
                            <label for="industry" class="block text-sm font-semibold text-gray-900 mb-2">
                                Industry
                            </label>
                            <input
                                type="text"
                                id="industry"
                                name="industry"
                                value="{{ old('industry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('industry') border-error @enderror"
                                placeholder="e.g., Technology, Healthcare"
                            >
                            @error('industry')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company Size -->
                        <div>
                            <label for="company_size" class="block text-sm font-semibold text-gray-900 mb-2">
                                Company Size
                            </label>
                            <select
                                id="company_size"
                                name="company_size"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('company_size') border-error @enderror"
                            >
                                <option value="">Select size</option>
                                <option value="1-10" {{ old('company_size') === '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                <option value="11-50" {{ old('company_size') === '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                <option value="51-200" {{ old('company_size') === '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                <option value="201-500" {{ old('company_size') === '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                <option value="501+" {{ old('company_size') === '501+' ? 'selected' : '' }}>501+ employees</option>
                            </select>
                            @error('company_size')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contract Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contract Start Date -->
                        <div>
                            <label for="contract_start_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                Contract Start Date
                            </label>
                            <input
                                type="date"
                                id="contract_start_date"
                                name="contract_start_date"
                                value="{{ old('contract_start_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('contract_start_date') border-error @enderror"
                            >
                            @error('contract_start_date')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contract End Date -->
                        <div>
                            <label for="contract_end_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                Contract End Date
                            </label>
                            <input
                                type="date"
                                id="contract_end_date"
                                name="contract_end_date"
                                value="{{ old('contract_end_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('contract_end_date') border-error @enderror"
                            >
                            @error('contract_end_date')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contract Value and Renewal Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contract Value -->
                        <div>
                            <label for="contract_value" class="block text-sm font-semibold text-gray-900 mb-2">
                                Contract Value ($)
                            </label>
                            <input
                                type="number"
                                id="contract_value"
                                name="contract_value"
                                value="{{ old('contract_value') }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('contract_value') border-error @enderror"
                                placeholder="0.00"
                            >
                            @error('contract_value')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Renewal Date -->
                        <div>
                            <label for="renewal_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                Renewal Date
                            </label>
                            <input
                                type="date"
                                id="renewal_date"
                                name="renewal_date"
                                value="{{ old('renewal_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('renewal_date') border-error @enderror"
                            >
                            @error('renewal_date')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Status and Account Health -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-semibold text-gray-900 mb-2">
                                Payment Status
                            </label>
                            <select
                                id="payment_status"
                                name="payment_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('payment_status') border-error @enderror"
                            >
                                <option value="">Select status</option>
                                <option value="current" {{ old('payment_status') === 'current' ? 'selected' : '' }}>Current</option>
                                <option value="overdue" {{ old('payment_status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Health Score -->
                        <div>
                            <label for="account_health_score" class="block text-sm font-semibold text-gray-900 mb-2">
                                Account Health Score (0-100)
                            </label>
                            <input
                                type="number"
                                id="account_health_score"
                                name="account_health_score"
                                value="{{ old('account_health_score') }}"
                                min="0"
                                max="100"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('account_health_score') border-error @enderror"
                                placeholder="Enter score (0-100)"
                            >
                            @error('account_health_score')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-900 mb-2">
                            Notes
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('notes') border-error @enderror"
                            placeholder="Additional notes about this client"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('clients.index') }}" class="px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-primary-accent to-blue-600 rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all shadow-lg">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Client
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
