@extends('layouts.app')

@section('content')
    <div class="min-h-screen pb-20">
        {{-- Breadcrumbs / Back button --}}
        <div class="mb-8">
            <a href="{{ route('people.volunteer.opportunities') }}"
                class="inline-flex items-center text-slate-400 hover:text-primary font-black text-[10px] uppercase tracking-widest transition-all group">
                <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </span>
                Back to Opportunities
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Content Column --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Hero & Title Card --}}
                <div class="glass-panel overflow-hidden">
                    <div class="relative h-96 overflow-hidden">
                        @if ($event->cover_image_path_name)
                            <img src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-500 flex items-center justify-center">
                                <span class="iconify text-white/20 text-9xl" data-icon="fluent:calendar-sparkle-48-filled"></span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8">
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1.5 rounded-xl bg-white/20 backdrop-blur-md text-[10px] font-black uppercase tracking-widest text-white border border-white/20">
                                    {{ $event->type == 0 ? 'Online' : 'Offline' }}
                                </span>
                                @php
                                    $statusColors = [
                                        'upcoming' => 'bg-red-500',
                                        'live' => 'bg-emerald-500',
                                        'completed' => 'bg-slate-500'
                                    ];
                                    $statusColor = $statusColors[$event->status] ?? 'bg-amber-500';
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl {{ $statusColor }} text-[10px] font-black uppercase tracking-widest text-white shadow-lg">
                                    {{ $event->status }}
                                </span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">{{ $event->title }}</h1>
                        </div>
                    </div>
                    <div class="p-8 md:p-10">
                        <div class="prose prose-slate max-w-none">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">About the Mission</h3>
                            <p class="text-lg text-slate-600 font-medium leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                        </div>

                        @if($event->requirements)
                            <div class="mt-12 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="iconify text-primary text-xl" data-icon="fluent:glance-horizontal-24-filled"></span>
                                    Volunteer Requirements
                                </h3>
                                <div class="text-slate-600 font-medium whitespace-pre-line leading-relaxed">
                                    {{ $event->requirements }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Roadmap Section --}}
                @if($event->milestones->count() > 0)
                    <div class="glass-panel p-8 md:p-10">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">Project Roadmap</h3>
                                <p class="text-sm text-slate-400 font-medium">Track the progress of this initiative</p>
                            </div>
                            @php
                                $completedCount = $event->milestones->where('status', 'completed')->count();
                                $totalCount = $event->milestones->count();
                                $percentage = ($totalCount > 0) ? ($completedCount / $totalCount) * 100 : 0;
                            @endphp
                            <div class="text-right">
                                <span class="block text-3xl font-black text-primary">{{ round($percentage) }}%</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Completed</span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="h-3 bg-slate-100 rounded-full overflow-hidden mb-12 flex">
                            <div class="h-full bg-gradient-to-r from-primary to-red-500 transition-all duration-1000 shadow-lg shadow-primary/20" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>

                        {{-- Milestone List --}}
                        <div class="space-y-6">
                            @foreach($event->milestones as $milestone)
                                <div class="flex gap-6 group">
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-xl flex items-center justify-center transition-all duration-500
                                            {{ $milestone->status === 'completed' ? 'bg-emerald-500 text-white' : ($milestone->status === 'in_progress' ? 'bg-primary text-white animate-pulse' : 'bg-slate-100 text-slate-300') }}">
                                            @if($milestone->status === 'completed')
                                                <i class="fas fa-check text-xs"></i>
                                            @elseif($milestone->status === 'in_progress')
                                                <i class="fas fa-rocket text-xs"></i>
                                            @else
                                                <i class="fas fa-circle text-[6px]"></i>
                                            @endif
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-1 h-full bg-slate-100 my-1 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="pb-8 last:pb-0">
                                        <h4 class="text-base font-black {{ $milestone->status === 'completed' ? 'text-slate-900' : 'text-slate-500' }} group-hover:text-primary transition-colors">
                                            {{ $milestone->title }}
                                        </h4>
                                        <p class="text-sm text-slate-400 font-medium mt-1 leading-relaxed">{{ $milestone->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Action Card --}}
                <div class="glass-panel p-8 sticky top-24">
                    <div class="space-y-6">
                        @if ($event->timing !== 'old')
                            @if ($event->is_registered)
                                <div class="p-6 bg-emerald-50 rounded-3xl border border-emerald-100 text-center">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                                        <i class="fas fa-check text-lg"></i>
                                    </div>
                                    <h4 class="text-emerald-900 font-black tracking-tight">You're Registered!</h4>
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Pending Approval',
                                            'accepted' => 'Confirmed', 
                                            'rejected' => 'Rejected'
                                        ];
                                        $displayStatus = $statusLabels[$volunteerStatus] ?? 'Pending Approval';
                                    @endphp
                                    <p class="text-emerald-600 text-xs font-bold uppercase tracking-widest mt-1">Status: {{ $displayStatus }}</p>
                                </div>
                            @elseif(!$event->is_volunteers_required)
                                <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100 text-center">
                                    <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-sm">
                                        <i class="fas fa-info-circle text-lg"></i>
                                    </div>
                                    <h4 class="text-indigo-900 font-black tracking-tight">Open Attendance</h4>
                                    <p class="text-indigo-600 text-xs font-bold uppercase tracking-widest mt-1">No registration needed</p>
                                </div>
                            @elseif($event->volunteers()->where('status', 'accepted')->count() >= $event->capacity)
                                <div class="p-6 bg-red-50 rounded-3xl border border-red-100 text-center">
                                    <h4 class="text-red-900 font-black tracking-tight">Full Capacity</h4>
                                    <p class="text-red-600 text-xs font-bold uppercase tracking-widest mt-1">This event is fully booked</p>
                                </div>
                            @else
                                <button type="button" id="btn-volunteer-apply" data-event-id="{{ $event->id }}"
                                    class="w-full btn-primary py-4 text-sm font-black uppercase tracking-widest shadow-2xl">
                                    Apply as Volunteer
                                </button>
                            @endif
                        @else
                            <div class="p-6 bg-slate-100 rounded-3xl text-center">
                                <h4 class="text-slate-500 font-black tracking-tight">Event Concluded</h4>
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1">Registration Closed</p>
                            </div>
                        @endif

                        <div class="divide-y divide-slate-100">
                            {{-- Date Stat --}}
                            <div class="py-5 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shadow-sm">
                                    <span class="iconify text-2xl" data-icon="fluent:calendar-ltr-24-filled"></span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Schedule</span>
                                    <span class="block text-sm font-black text-slate-900 mt-1">{{ $event->start_date->format('M d, Y') }}</span>
                                    <span class="block text-[10px] font-bold text-slate-400">{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</span>
                                </div>
                            </div>

                            {{-- Location Stat --}}
                            <div class="py-5 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shadow-sm">
                                    <span class="iconify text-2xl" data-icon="fluent:location-24-filled"></span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Venue</span>
                                    <span class="block text-sm font-black text-slate-900 mt-1">{{ $event->location }}</span>
                                </div>
                            </div>

                            {{-- Capacity Stat --}}
                            <div class="py-5 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 shadow-sm">
                                    <span class="iconify text-2xl" data-icon="fluent:handshake-24-filled"></span>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Volunteers</span>
                                    <div class="flex items-end justify-between mt-1">
                                        <span class="text-sm font-black text-slate-900">{{ $event->volunteers()->where('status', 'accepted')->count() }} / {{ $event->capacity }}</span>
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter">Slots Left</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-slate-100 rounded-full mt-2 overflow-hidden">
                                        <div class="h-full bg-amber-400 rounded-full" style="width: {{ $event->capacity > 0 ? ($event->volunteers()->where('status', 'accepted')->count() / $event->capacity) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Share/Save Actions --}}
                        <div class="pt-6 grid grid-cols-2 gap-3">
                            <button class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-slate-400 hover:text-primary hover:bg-white transition-all">
                                <i class="fas fa-share-alt mr-2"></i> <span class="text-[10px] font-black uppercase tracking-widest">Share</span>
                            </button>
                            <button class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-slate-400 hover:text-red-500 hover:bg-white transition-all">
                                <i class="fas fa-heart mr-2"></i> <span class="text-[10px] font-black uppercase tracking-widest">Like</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- NGO Info Card --}}
                <div class="glass-panel p-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Organized By</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100">
                            @if($event->user->ngo && $event->user->ngo->logo)
                                <img src="{{ asset('storage/' . $event->user->ngo->logo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-red-200">
                                    <i class="fas fa-building text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900">{{ $event->user->ngo->ngo_name ?? $event->user->name }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $event->user->ngo->category ?? 'Organization' }}</p>
                            <a href="{{ route('common.ngo.profile', $event->user_id) }}" class="text-[9px] font-black text-primary uppercase tracking-widest hover:underline mt-1 block">View Profile →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-5 right-5 space-y-2 z-50"></div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const applyBtn = document.getElementById('btn-volunteer-apply');
            const toastContainer = document.getElementById('toast-container');
            const csrfToken = "{{ csrf_token() }}";

            // Function to show toast
            const showToast = (message, type = 'success') => {
                const toast = document.createElement('div');
                toast.className = `max-w-xs w-full px-6 py-4 rounded-2xl shadow-2xl text-white font-bold text-sm ${
                    type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
                } transform transition-all duration-300 translate-x-12 opacity-0`;
                toast.textContent = message;
                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-x-12', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 10);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            };

            if (applyBtn) {
                applyBtn.addEventListener('click', async () => {
                    const eventId = applyBtn.getAttribute('data-event-id');

                    applyBtn.disabled = true;
                    applyBtn.style.minWidth = applyBtn.offsetWidth + 'px';
                    const originalContent = applyBtn.innerHTML;
                    
                    applyBtn.innerHTML = `
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Wait...
                        </div>
                    `;

                    try {
                        const response = await fetch("{{ route('people.volunteer.apply') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken
                            },
                            body: JSON.stringify({ event_id: eventId })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showToast(data.message, 'success');
                            applyBtn.innerHTML = `
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    Applied
                                </div>
                            `;
                            applyBtn.classList.remove('btn-primary');
                            applyBtn.classList.add('bg-emerald-500', 'text-white', 'cursor-not-allowed');
                        } else {
                            showToast(data.message || 'Error occurred.', 'failure');
                            applyBtn.disabled = false;
                            applyBtn.innerHTML = originalContent;
                            applyBtn.style.minWidth = '';
                        }

                    } catch (error) {
                        showToast('Failed to connect. Try again.', 'failure');
                        applyBtn.disabled = false;
                        applyBtn.innerHTML = originalContent;
                        applyBtn.style.minWidth = '';
                    }
                });
            }
        });
    </script>
@endpush
