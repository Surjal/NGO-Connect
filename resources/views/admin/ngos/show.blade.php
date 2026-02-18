@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Back Button & Header -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('admin.ngos') }}" class="flex items-center text-gray-500 hover:text-gray-900 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center mr-2 shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                <span class="font-bold text-sm">Back to List</span>
            </a>
            <div class="flex gap-3">
                 @if ($ngo->ngo->suspended)
                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold border border-red-200 flex items-center">
                        <i class="fas fa-ban mr-1.5"></i> Suspended
                    </span>
                 @elseif ($ngo->ngo->verified)
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold border border-green-200 flex items-center">
                        <i class="fas fa-check-circle mr-1.5"></i> Verified
                    </span>
                 @else
                     <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-xs font-bold border border-amber-200 flex items-center">
                        <i class="fas fa-clock mr-1.5"></i> Pending Verification
                    </span>
                 @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Identity & Contact -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="glass-panel p-6 text-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-red-500/20 to-red-500/20"></div>
                    <div class="relative z-10">
                        <div class="w-24 h-24 mx-auto rounded-2xl bg-white shadow-lg p-1 mb-4 flex-shrink-0">
                            @if ($ngo->ngo && $ngo->ngo->logo)
                                <img src="{{ Storage::url($ngo->ngo->logo) }}" alt="{{ $ngo->name }}"
                                    class="w-full h-full object-cover rounded-xl">
                            @else
                                <div class="w-full h-full rounded-xl bg-red-50 flex items-center justify-center text-2xl font-bold text-red-500">
                                    {{ substr($ngo->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h1 class="text-xl font-black text-gray-900 leading-tight mb-1">{{ $ngo->name }}</h1>
                        <p class="text-sm font-bold text-red-600 mb-4">{{ $ngo->ngo->category }}</p>
                        
                        <div class="flex justify-center gap-2 mb-6">
                             @if ($ngo->ngo->website)
                                <a href="{{ $ngo->ngo->website }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-globe"></i>
                                </a>
                             @endif
                             <a href="mailto:{{ $ngo->email }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 text-left">
                             <div class="mb-3">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact Person</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $ngo->owner->name ?? 'N/A' }}</span>
                                </div>
                                <p class="text-xs text-gray-500 ml-8">{{ $ngo->ngo->contact_position ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact Number</p>
                                 <div class="flex items-center gap-2">
                                     <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $ngo->ngo->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="glass-panel p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Admin Actions</h3>
                    <div class="space-y-3">
                        @if ($ngo->ngo->suspended)
                             <form action="{{ route('admin.ngos.suspend', $ngo->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Unsuspend Account
                                </button>
                            </form>
                        @elseif ($ngo->ngo->verified)
                            <button onclick="openSuspendModal()" class="w-full py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-ban"></i> Suspend Account
                            </button>
                        @else
                           <form action="{{ route('admin.ngos.verify', $ngo->id) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Verify & Approve
                                </button>
                            </form>
                            <button onclick="openRejectModal()" class="w-full py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-500 hover:border-red-200 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Reject Application
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Mission -->
                <div class="glass-panel p-6">
                     <h3 class="text-lg font-bold text-gray-900 mb-4">About the Organization</h3>
                     <div class="prose prose-sm text-gray-600 max-w-none">
                        <div class="mb-4">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Mission</h4>
                            <p>{{ $ngo->ngo->mission ?? 'No mission statement provided.' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Description</h4>
                            <p>{{ $ngo->ngo->description ?? 'No description provided.' }}</p>
                        </div>
                     </div>
                     
                     <!-- Subcategories Badge List -->
                     @if ($ngo->ngo->subcategory)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                             <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Focus Areas</h4>
                             <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $ngo->ngo->subcategory) as $sub)
                                    <span class="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-bold border border-gray-200">
                                        {{ trim($sub) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                     @endif
                </div>

                <!-- Registration Details -->
                <div class="glass-panel p-6">
                     <h3 class="text-lg font-bold text-gray-900 mb-4">Legal & Registration</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Registration No.</p>
                            <p class="text-sm font-bold text-gray-900 font-mono">{{ $ngo->ngo->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">PAN Number</p>
                            <p class="text-sm font-bold text-gray-900 font-mono">{{ $ngo->ngo->pan_number ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Registration Date</p>
                            <p class="text-sm font-bold text-gray-900">{{ $ngo->ngo->registration_date ? \Carbon\Carbon::parse($ngo->ngo->registration_date)->format('d M, Y') : 'N/A' }}</p>
                        </div>
                         <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Last Renewal</p>
                            <p class="text-sm font-bold text-gray-900">{{ $ngo->ngo->last_renewal_date ? \Carbon\Carbon::parse($ngo->ngo->last_renewal_date)->format('d M, Y') : 'N/A' }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 md:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Registered Address</p>
                            <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                {{ $ngo->ngo->address ?? 'N/A' }} ({{ $ngo->ngo->registration_district ?? '' }})
                            </p>
                        </div>
                     </div>
                </div>

                <!-- Documents -->
                 @if ($ngo->ngo->documents && count($ngo->ngo->documents) > 0)
                    <div class="glass-panel p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             @foreach ($ngo->ngo->documents as $document)
                                <a href="{{ asset('storage/' . $document->path) }}" download class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center text-lg mr-3">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate group-hover:text-red-700 transition-colors">{{ $document->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $document->type }}</p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-red-500">
                                        <i class="fas fa-download"></i>
                                    </div>
                                </a>
                             @endforeach
                        </div>
                    </div>
                 @endif

                 <!-- Gallery -->
                 @if ($ngo->ngo->photos && count($ngo->ngo->photos) > 0)
                    <div class="glass-panel p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Photo Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                             @foreach ($ngo->ngo->photos as $photo)
                                <div class="rounded-xl overflow-hidden h-32 border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer">
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Gallery" class="w-full h-full object-cover">
                                </div>
                             @endforeach
                        </div>
                    </div>
                 @endif

            </div>
        </div>
    </div>

    <!-- Modals -->
    {{-- Rejection Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Reject Application</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Are you sure you want to reject this NGO? This action cannot be undone. Please provide a reason.</p>
                                    <form id="rejectForm" action="{{ route('admin.ngos.reject', $ngo->id) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Reason for rejection..." required></textarea>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" form="rejectForm" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Reject</button>
                        <button type="button" onclick="closeRejectModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Suspend Modal --}}
     <div id="suspendModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-ban text-orange-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900">Suspend Account</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Temporarily disable this NGO's access. They will need to contact support to be reinstated.</p>
                                    <form id="suspendForm" action="{{ route('admin.ngos.suspend', $ngo->id) }}" method="POST">
                                        @csrf
                                        <textarea name="suspension_reason" id="suspension_reason" rows="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" placeholder="Reason for suspension..." required></textarea>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" form="suspendForm" class="inline-flex w-full justify-center rounded-xl bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">Suspend</button>
                        <button type="button" onclick="closeSuspendModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function openRejectModal() { $('#rejectModal').removeClass('hidden'); }
        function closeRejectModal() { $('#rejectModal').addClass('hidden'); }
        function openSuspendModal() { $('#suspendModal').removeClass('hidden'); }
        function closeSuspendModal() { $('#suspendModal').addClass('hidden'); }
    </script>
@endpush
