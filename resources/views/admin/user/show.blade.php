@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Back Button & Header -->
        <div class="flex items-center justify-between mb-8">
             <a href="{{ route('admin.users') }}" class="flex items-center text-gray-500 hover:text-gray-900 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center mr-2 shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                <span class="font-bold text-sm">Back to List</span>
            </a>
            <div class="flex gap-3">
                 @if ($user->suspended)
                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold border border-red-200 flex items-center">
                        <i class="fas fa-ban mr-1.5"></i> Suspended
                    </span>
                 @else
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold border border-green-200 flex items-center">
                        <i class="fas fa-check-circle mr-1.5"></i> Active
                    </span>
                 @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Identity -->
            <div class="lg:col-span-1 space-y-6">
                 <!-- Profile Card -->
                <div class="glass-panel p-6 text-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-red-500/20 to-red-500/20"></div>
                    <div class="relative z-10">
                         <div class="w-24 h-24 mx-auto rounded-full bg-white shadow-lg p-1 mb-4 flex-shrink-0 overflow-hidden">
                            @if ($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}"
                                    class="w-full h-full object-cover rounded-full">
                            @else
                                <div class="w-full h-full rounded-full bg-red-50 flex items-center justify-center text-2xl font-bold text-red-500">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h1 class="text-xl font-black text-gray-900 leading-tight mb-1">{{ $user->name }}</h1>
                        <p class="text-sm font-bold text-gray-500 mb-4">{{ $user->email }}</p>
                        
                        <div class="flex justify-center gap-2 mb-6">
                             <a href="mailto:{{ $user->email }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                         
                         <div class="border-t border-gray-100 pt-4 text-left">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Join Date</span>
                                <span class="text-sm font-bold text-gray-700">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="glass-panel p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Admin Actions</h3>
                    <div class="space-y-3">
                         @if ($user->suspended)
                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="unsuspend">
                                <button type="submit" class="w-full py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Unsuspend User
                                </button>
                            </form>
                         @else
                            <button onclick="openSuspendModal()" class="w-full py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-ban"></i> Suspend User
                            </button>
                         @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Registration Information -->
                 <div class="glass-panel p-6">
                     <h3 class="text-lg font-bold text-gray-900 mb-4">User Details</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name</p>
                            <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</p>
                             <p class="text-sm font-bold text-gray-900">{{ $user->email }}</p>
                        </div>
                         <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</p>
                             <p class="text-sm font-bold text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Location</p>
                             <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                {{ $user->location ?? 'N/A' }}
                            </p>
                        </div>
                     </div>
                 </div>
                 
                 @if($user->preferred_categories)
                    <div class="glass-panel p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Interests</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->preferred_categories as $category)
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100">
                                    {{ $category }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                 @endif

                 <!-- Suspension History/Reason -->
                 @if($user->suspended && $user->suspension_reason)
                    <div class="glass-panel p-6 border-l-4 border-red-500">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Suspension Details</h3>
                        <p class="text-sm font-bold text-gray-700 mb-1">Reason:</p>
                        <p class="text-sm text-gray-600 italic">"{{ $user->suspension_reason }}"</p>
                        <p class="text-xs text-gray-400 mt-3">Suspended on {{ \Carbon\Carbon::parse($user->suspended_at)->format('F d, Y') }}</p>
                    </div>
                 @endif
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
                                <h3 class="text-lg font-bold leading-6 text-gray-900">Suspend User</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Temporarily disable this user's access. They will not be able to log in.</p>
                                    <form id="suspendForm" action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="suspend">
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
        function openSuspendModal() { $('#suspendModal').removeClass('hidden'); }
        function closeSuspendModal() { $('#suspendModal').addClass('hidden'); }
    </script>
@endpush
