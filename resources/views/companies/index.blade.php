@extends('layouts.dashboard')

@section('page-title', 'Companies')

@section('content')
<div class="py-8" x-data="{
    showSwitchModal: false,
    selectedCompany: null,
    switchCompany(companyId, companyName) {
        this.selectedCompany = { id: companyId, name: companyName };
        this.showSwitchModal = true;
    }
}">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-primary-brand">Manage Companies</h2>
                    <p class="mt-2 text-neutral-600">Switch between your companies or create a new one</p>
                </div>
                <a href="{{ route('companies.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-accent to-blue-600 text-white rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all font-semibold shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Company
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-success/10 border border-success/20 text-success px-6 py-4 rounded-xl flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-error/10 border border-error/20 text-error px-6 py-4 rounded-xl flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Companies Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($companies as $company)
                <div class="bg-white rounded-xl shadow-lg border-2 {{ $currentCompany && $currentCompany->id === $company->id ? 'border-primary-accent' : 'border-gray-200' }} hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <!-- Company Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-primary-accent to-blue-600 rounded-xl shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    @if($currentCompany && $currentCompany->id === $company->id)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-primary-accent bg-primary-accent/10 rounded-full mb-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Company Info -->
                        <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $company->name }}</h3>

                        @if($company->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $company->description }}</p>
                        @endif

                        <!-- Company Details -->
                        <div class="space-y-2 mb-4">
                            @if($company->email)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="truncate">{{ $company->email }}</span>
                                </div>
                            @endif

                            @if($company->website)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <a href="{{ $company->website }}" target="_blank" class="truncate hover:text-primary-accent">{{ parse_url($company->website, PHP_URL_HOST) }}</a>
                                </div>
                            @endif

                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>{{ $company->users_count }} {{ Str::plural('member', $company->users_count) }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            @if(!$currentCompany || $currentCompany->id !== $company->id)
                                <button
                                    type="button"
                                    @click="switchCompany({{ $company->id }}, '{{ $company->name }}')"
                                    class="flex-1 px-4 py-2 text-sm font-semibold text-primary-accent bg-primary-accent/10 hover:bg-primary-accent hover:text-white rounded-lg transition-colors duration-200"
                                >
                                    Switch to This
                                </button>
                            @else
                                <button disabled class="flex-1 px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    Current Company
                                </button>
                            @endif

                            <a href="{{ route('companies.show', $company) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <a href="{{ route('companies.edit', $company) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Companies Found</h3>
                        <p class="text-gray-600 mb-6">Get started by creating your first company</p>
                        <a href="{{ route('companies.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-accent text-white rounded-xl hover:bg-primary-accent/90 transition-colors font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Company
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Switch Company Confirmation Modal -->
    <div
        x-show="showSwitchModal"
        x-cloak
        @keydown.escape.window="showSwitchModal = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div
            x-show="showSwitchModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
            @click="showSwitchModal = false"
        ></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                x-show="showSwitchModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary-accent to-blue-600 px-6 py-8 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white" id="modal-title">
                        Switch Company
                    </h3>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <p class="text-gray-600 mb-2">You are about to switch to:</p>
                    <div class="bg-gradient-to-r from-primary-accent/10 to-blue-600/10 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-primary-accent to-blue-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <p class="text-lg font-bold text-gray-900" x-text="selectedCompany?.name"></p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">
                        All your data will be filtered to show only information related to this company.
                    </p>
                </div>

                <!-- Modal Actions -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="showSwitchModal = false"
                        class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <form
                        :action="selectedCompany ? '{{ url('companies') }}/' + selectedCompany.id + '/switch' : ''"
                        method="POST"
                        class="inline"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-accent to-blue-600 rounded-xl hover:from-primary-accent/90 hover:to-blue-600/90 transition-all shadow-lg"
                        >
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Confirm Switch
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@endsection
