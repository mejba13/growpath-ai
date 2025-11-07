@extends('layouts.dashboard')

@section('page-title', 'Edit Company')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header with Back Button -->
        <div class="mb-8">
            <a href="{{ route('companies.index') }}" class="inline-flex items-center text-neutral-600 hover:text-primary-accent transition-colors mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Companies
            </a>
            <h2 class="text-3xl font-bold text-primary-brand">Edit Company</h2>
            <p class="mt-2 text-neutral-600">Update your company information</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('companies.update', $company) }}">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Company Name <span class="text-error">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $company->name) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('name') border-error @enderror"
                            placeholder="Enter company name"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                            Company Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $company->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('email') border-error @enderror"
                            placeholder="contact@company.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $company->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('phone') border-error @enderror"
                            placeholder="+1 (555) 123-4567"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-semibold text-gray-900 mb-2">
                            Website
                        </label>
                        <input
                            type="url"
                            id="website"
                            name="website"
                            value="{{ old('website', $company->website) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('website') border-error @enderror"
                            placeholder="https://www.company.com"
                        >
                        @error('website')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                            Address
                        </label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('address') border-error @enderror"
                            placeholder="Enter company address"
                        >{{ old('address', $company->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Description
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition-colors @error('description') border-error @enderror"
                            placeholder="Brief description of your company"
                        >{{ old('description', $company->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Active Status Toggle -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <label for="is_active" class="text-sm font-semibold text-gray-900">
                                Company Status
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                Inactive companies won't be available for selection
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >
                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $company->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 text-primary-accent border-gray-300 rounded focus:ring-primary-accent focus:ring-2"
                            >
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <button
                        type="button"
                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this company? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                        class="px-6 py-3 text-sm font-semibold text-white bg-error rounded-xl hover:bg-error/90 transition-colors"
                    >
                        Delete Company
                    </button>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('companies.index') }}" class="px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-primary-accent to-blue-600 rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all shadow-lg">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Company
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form (hidden) -->
            <form id="delete-form" method="POST" action="{{ route('companies.destroy', $company) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection
