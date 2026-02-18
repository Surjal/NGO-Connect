@extends('layouts.app')

@push('styles')
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endpush

@section('content')
    <div class="min-h-screen pb-16">
        <!-- Hero Profile Section -->
        <div class="relative mb-8">
            <!-- Professional Gradient Banner -->
            <div class="h-48 md:h-72 bg-gradient-to-br from-primary via-red-500 to-red-400 rounded-b-[2.5rem] md:rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 mix-blend-overlay">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                    </svg>
                </div>
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Profile Info Overlay -->
            <div class="max-w-5xl mx-auto px-6">
                <div class="relative -mt-20 md:-mt-32 flex flex-col md:flex-row items-center md:items-end gap-6 md:gap-8 pb-8">
                    <!-- Profile Photo with Glow -->
                    <div class="relative group">
                        <div class="absolute -inset-1.5 bg-gradient-to-tr from-white to-red-100 rounded-[2.5rem] blur opacity-40 group-hover:opacity-60 transition duration-1000"></div>
                        <div class="relative w-32 h-32 md:w-48 md:h-48 rounded-[2.2rem] overflow-hidden border-4 border-white bg-slate-50 shadow-2xl transition-transform duration-500 group-hover:scale-[1.02]">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                    <span class="iconify text-7xl" data-icon="fluent:person-24-filled"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Name and Action -->
                    <div class="flex-1 text-center md:text-left mb-2">
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">{{ $user->name }}</h1>
                        <p class="text-slate-500 font-bold flex items-center justify-center md:justify-start gap-2 mt-2">
                            <span class="iconify text-red-400 text-xl" data-icon="fluent:mail-24-filled"></span>
                            <span class="text-sm md:text-lg">{{ $user->email }}</span>
                        </p>
                    </div>

                    <div class="flex gap-4 w-full md:w-auto justify-center">
                        <a href="{{ route('people.profile.edit') }}"
                            class="inline-flex items-center px-8 md:px-10 py-3.5 bg-white border border-slate-200 text-slate-700 font-black rounded-2xl shadow-sm hover:shadow-xl hover:bg-slate-50 transition-all duration-300 text-xs md:text-sm uppercase tracking-widest">
                            <span class="iconify mr-2.5 text-lg" data-icon="fluent:settings-24-filled"></span>
                            Manage Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Stats Grid -->
        <div class="max-w-5xl mx-auto px-6 mb-12">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8">
                <!-- Impact Score Card -->
                <div class="glass-panel p-8 relative overflow-hidden group hover:shadow-2xl transition-all">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:scale-125 transition-transform duration-700">
                        <span class="iconify text-8xl text-red-600" data-icon="fluent:star-24-filled"></span>
                    </div>
                    <span class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Impact Points</span>
                    <h3 class="text-4xl md:text-5xl font-black text-red-600 tracking-tight">
                        {{ ($stats['volunteering_count'] * 100) + (int)($stats['total_donated'] / 10) }}
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-3 font-bold uppercase tracking-widest">Top 5% Contributor</p>
                </div>

                <!-- Volunteering Card -->
                <div class="glass-panel p-8 relative overflow-hidden group hover:shadow-2xl transition-all">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:scale-125 transition-transform duration-700">
                        <span class="iconify text-8xl text-red-500" data-icon="fluent:heart-24-filled"></span>
                    </div>
                    <span class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Events Joined</span>
                    <h3 class="text-4xl md:text-5xl font-black text-red-500 tracking-tight">{{ $stats['volunteering_count'] }}</h3>
                    <p class="text-[10px] text-slate-400 mt-3 font-bold uppercase tracking-widest">Active Volunteer</p>
                </div>

                <!-- Donation Card -->
                <div class="glass-panel p-8 relative overflow-hidden group hover:shadow-2xl transition-all">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:scale-125 transition-transform duration-700">
                        <span class="iconify text-8xl text-emerald-500" data-icon="fluent:money-hand-24-filled"></span>
                    </div>
                    <span class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Donated</span>
                    <h3 class="text-4xl md:text-5xl font-black text-emerald-500 tracking-tight">Rs.{{ number_format($stats['total_donated']) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-3 font-bold uppercase tracking-widest">Supporting {{ $stats['donations_count'] }} Causes</p>
                </div>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar: About & Interests -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="glass-panel p-8">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Personal Bio
                        </h4>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <span class="iconify text-xl" data-icon="fluent:location-24-regular"></span>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Location</p>
                                    <p class="text-sm font-bold text-slate-700 mt-1">{{ $user->location ?: 'Global Citizen' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <span class="iconify text-xl" data-icon="fluent:calendar-24-regular"></span>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Joined</p>
                                    <p class="text-sm font-bold text-slate-700 mt-1">{{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel p-8">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Passions
                        </h4>
                        @if(!empty($user->preferred_categories))
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->preferred_categories as $cat)
                                    <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">{{ $cat }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 font-medium italic">No interests selected yet.</p>
                            <a href="{{ route('people.profile.edit') }}" class="inline-block mt-4 text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Update Interests →</a>
                        @endif
                    </div>
                </div>

                <!-- Main Section: Tabs & Content -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Custom Styled Tabs -->
                    <div class="bg-white/50 backdrop-blur-md rounded-3xl p-1.5 flex gap-1 border border-slate-200 shadow-inner">
                        <button onclick="switchTab('activity')" id="tab-btn-activity" class="tab-btn flex-1 py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all text-primary bg-white shadow-sm ring-1 ring-slate-100">
                            Activity
                        </button>
                        <button onclick="switchTab('badges')" id="tab-btn-badges" class="tab-btn flex-1 py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-900">
                            Impact
                        </button>
                        <button onclick="switchTab('following')" id="tab-btn-following" class="tab-btn flex-1 py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-900">
                            Network
                        </button>
                    </div>

                    <!-- Panel: Activity -->
                    <div id="tab-panel-activity" class="tab-panel space-y-4">
                        @if($user->volunteeredEvents->isEmpty() && $user->donations->isEmpty())
                            <div class="glass-panel p-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                                    <span class="iconify text-5xl" data-icon="fluent:feed-24-regular"></span>
                                </div>
                                <h4 class="text-slate-900 font-black tracking-tight mb-2">Your impact journey starts here</h4>
                                <p class="text-slate-500 text-sm font-medium mb-8">Join your first event or support a cause to see your activity here.</p>
                                <a href="{{ route('people.volunteer.opportunities') }}" class="btn-primary px-8 py-3">Explore Opportunities</a>
                            </div>
                        @else
                            {{-- Volunteering Timeline --}}
                            @foreach($user->volunteeredEvents as $event)
                                <div class="glass-panel p-6 flex items-center justify-between group hover:border-primary/20 transition-all">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <span class="iconify text-3xl" data-icon="fluent:handshake-24-filled"></span>
                                        </div>
                                        <div>
                                            <h5 class="text-base font-black text-slate-900 leading-none">Joined "{{ $event->title }}"</h5>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $event->pivot->created_at->diffForHumans() }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-tighter">{{ $event->pivot->status }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('people.volunteer.details', $event->id) }}" class="p-3 bg-slate-50 rounded-xl text-slate-400 hover:text-primary transition-all">
                                        <span class="iconify text-xl" data-icon="fluent:chevron-right-24-filled"></span>
                                    </a>
                                </div>
                            @endforeach

                            {{-- Donation Timeline --}}
                            @foreach($user->donations as $donation)
                                <div class="glass-panel p-6 flex items-center justify-between group hover:border-emerald-200 transition-all">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <span class="iconify text-3xl" data-icon="fluent:money-hand-24-filled"></span>
                                        </div>
                                        <div>
                                            <h5 class="text-base font-black text-slate-900 leading-none">Donated Rs.{{ number_format($donation->donation_amount) }}</h5>
                                            <p class="text-xs font-bold text-slate-400 mt-2 uppercase tracking-tight">to {{ $donation->ngo->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-lg">
                                        {{ $donation->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Panel: Badges -->
                    <div id="tab-panel-badges" class="tab-panel hidden space-y-8">
                        <div class="glass-panel p-8">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Recognition & Badges</h4>
                            @if($user->badges->count() > 0)
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-8">
                                    @foreach($user->badges as $badge)
                                        <div class="flex flex-col items-center group">
                                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center border-2 border-white shadow-xl relative group-hover:scale-110 transition-transform duration-500">
                                                <span class="iconify text-4xl text-amber-600" data-icon="{{ $badge->icon ?? 'fluent:ribbon-24-filled' }}"></span>
                                                <div class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                                                    <i class="fas fa-check text-[10px] text-white"></i>
                                                </div>
                                            </div>
                                            <span class="mt-4 text-[10px] font-black uppercase tracking-widest text-slate-700 text-center">{{ $badge->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <span class="iconify text-3xl" data-icon="fluent:trophy-24-regular"></span>
                                    </div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">No badges earned yet. Keep helping!</p>
                                </div>
                            @endif
                        </div>

                        <div class="glass-panel p-8">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Verified Certificates</h4>
                            @forelse($user->certificates as $certificate)
                                <div class="flex items-center justify-between p-4 mb-3 bg-slate-50 rounded-2xl border border-slate-100 hover:border-primary/20 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm">
                                            <span class="iconify text-xl" data-icon="fluent:certificate-24-filled"></span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900 tracking-tight">{{ $certificate->event->title }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $certificate->issued_at->format('M Y') }}</p>
                                        </div>
                                    </div>
                                    <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                                        <span class="iconify text-xl" data-icon="fluent:arrow-download-24-filled"></span>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <p class="text-xs font-bold uppercase tracking-widest">Your verified certificates will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Panel: Following -->
                    <div id="tab-panel-following" class="tab-panel hidden space-y-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Your Network</h4>
                            <a href="{{ route('people.ngo.search') }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Discovery Mode →</a>
                        </div>
                        
                        @forelse($user->followedNgos as $ngo)
                            <a href="{{ route('common.ngo.profile', $ngo->user_id) }}" class="glass-panel p-5 flex items-center justify-between group hover:border-red-200 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 p-0.5">
                                        @if($ngo->logo)
                                            <img src="{{ asset('storage/' . $ngo->logo) }}" class="w-full h-full object-cover rounded-[14px]">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-red-200 bg-red-50/50">
                                                <i class="fas fa-building text-base"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-black text-slate-900 group-hover:text-primary transition-colors">{{ $ngo->ngo_name }}</h5>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $ngo->category ?: 'NGO' }}</p>
                                    </div>
                                </div>
                                <span class="p-2 text-slate-300 group-hover:text-primary transition-colors">
                                    <span class="iconify text-xl" data-icon="fluent:chevron-right-24-filled"></span>
                                </span>
                            </a>
                        @empty
                            <div class="glass-panel p-12 text-center">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">You haven't followed any NGOs yet.</p>
                                <a href="{{ route('people.ngo.search') }}" class="btn-secondary px-6 py-2.5">Find Organizations</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Panels
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('tab-panel-' + tabId).classList.remove('hidden');
            
            // Buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('text-primary', 'bg-white', 'shadow-sm', 'ring-1', 'ring-slate-100');
                b.classList.add('text-slate-500');
            });
            
            const btn = document.getElementById('tab-btn-' + tabId);
            btn.classList.add('text-primary', 'bg-white', 'shadow-sm', 'ring-1', 'ring-slate-100');
            btn.classList.remove('text-slate-500');
        }
    </script>
@endsection
