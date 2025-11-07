@extends('layouts.dashboard')

@section('page-title', $company->name)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header with Back Button -->
        <div class="mb-8">
            <a href="{{ route('companies.index') }}" class="inline-flex items-center text-neutral-600 hover:text-primary-accent transition-colors mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Companies
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-accent to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-primary-brand">{{ $company->name }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            @if($company->is_active)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-success bg-success/10 rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 rounded-full">
                                    Inactive
                                </span>
                            @endif
                            @if(auth()->user()->current_company_id === $company->id)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-primary-accent bg-primary-accent/10 rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Current Company
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-accent to-blue-600 text-white rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all font-semibold shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Company
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Company Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Company Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Company Information</h3>

                    <div class="space-y-4">
                        @if($company->description)
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Description</label>
                                <p class="mt-1 text-gray-900">{{ $company->description }}</p>
                            </div>
                        @endif

                        @if($company->email)
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                                <div class="mt-1 flex items-center text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <a href="mailto:{{ $company->email }}" class="hover:text-primary-accent transition-colors">{{ $company->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($company->phone)
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Phone</label>
                                <div class="mt-1 flex items-center text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <a href="tel:{{ $company->phone }}" class="hover:text-primary-accent transition-colors">{{ $company->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($company->website)
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Website</label>
                                <div class="mt-1 flex items-center text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <a href="{{ $company->website }}" target="_blank" class="hover:text-primary-accent transition-colors">{{ $company->website }}</a>
                                </div>
                            </div>
                        @endif

                        @if($company->address)
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Address</label>
                                <div class="mt-1 flex items-start text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $company->address }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Company Members -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Team Members</h3>
                        <span class="text-sm text-gray-500">{{ $company->users->count() }} {{ Str::plural('member', $company->users->count()) }}</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($company->users as $user)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-primary-accent to-blue-600 rounded-full text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-full capitalize">
                                    {{ $user->pivot->role }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>

                    <div class="space-y-4">
                        <!-- Prospects Count -->
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Prospects</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">{{ $company->prospects->count() }}</span>
                        </div>

                        <!-- Clients Count -->
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Clients</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $company->clients->count() }}</span>
                        </div>

                        <!-- Team Members Count -->
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Team</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $company->users->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Company Details -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Details</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Company Slug</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $company->slug }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $company->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(auth()->user()->current_company_id !== $company->id)
                    <form method="POST" action="{{ route('companies.switch', $company) }}">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-primary-accent to-blue-600 rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all shadow-lg">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Switch to This Company
                            </span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
