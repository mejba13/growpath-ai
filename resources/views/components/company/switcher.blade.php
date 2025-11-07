@if(auth()->check() && auth()->user()->companies->count() > 0)
<div x-data="{
    open: false,
    showSwitchModal: false,
    selectedCompany: null,
    switchCompany(companyId, companyName) {
        this.selectedCompany = { id: companyId, name: companyName };
        this.showSwitchModal = true;
        this.open = false;
    }
}" class="relative">
    <button @click="open = !open" type="button" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-primary-accent/30 transition-all duration-200 shadow-sm">
        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-primary-accent to-blue-600 rounded-lg">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <div class="flex-1 text-left">
            <div class="text-xs text-gray-500">Current Company</div>
            <div class="font-semibold text-gray-900 truncate max-w-[180px]">
                {{ auth()->user()->currentCompany->name ?? 'Select Company' }}
            </div>
        </div>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute right-0 z-50 w-80 mt-2 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
         style="display: none;">

        <div class="p-3 bg-gradient-to-r from-primary-accent/5 to-blue-600/5 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Switch Company</h3>
            <p class="text-xs text-gray-500 mt-0.5">Select a company to manage</p>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @foreach(auth()->user()->companies as $company)
                <button
                    type="button"
                    @if(auth()->user()->current_company_id != $company->id)
                        @click="switchCompany({{ $company->id }}, '{{ $company->name }}')"
                    @endif
                    class="flex items-center w-full px-4 py-3 text-sm text-left transition-colors duration-150 border-b border-gray-100 last:border-0 {{ auth()->user()->current_company_id == $company->id ? 'bg-primary-accent/10 cursor-default' : 'hover:bg-gray-50 cursor-pointer' }}"
                >
                    <div class="flex items-center justify-center w-10 h-10 mr-3 bg-gradient-to-br {{ auth()->user()->current_company_id == $company->id ? 'from-primary-accent to-blue-600' : 'from-gray-400 to-gray-500' }} rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 truncate">{{ $company->name }}</div>
                        @if($company->email)
                            <div class="text-xs text-gray-500 truncate">{{ $company->email }}</div>
                        @endif
                    </div>
                    @if(auth()->user()->current_company_id == $company->id)
                        <div class="flex items-center gap-1 px-2 py-1 text-xs font-semibold text-primary-accent bg-primary-accent/10 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Active
                        </div>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="border-t border-gray-200 bg-gray-50">
            <a href="{{ route('companies.index') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-primary-accent hover:bg-primary-accent/5 transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Manage Companies
            </a>
        </div>
    </div>

    <!-- Switch Company Confirmation Modal -->
    <div
        x-show="showSwitchModal"
        x-cloak
        @keydown.escape.window="showSwitchModal = false"
        class="fixed inset-0 z-[60] overflow-y-auto"
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
                        :action="selectedCompany ? '{{ url('dashboard/companies') }}/' + selectedCompany.id + '/switch' : ''"
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

@endif
