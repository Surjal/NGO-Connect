@extends('layouts.app')

@section('content')
    {{-- Toast Container for Success and Failure Messages --}}
    <div id="toast-container" class="fixed top-5 right-5 space-y-2 z-50"></div>

    <div class="space-y-12 pb-20">
        {{-- Hero Section --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary via-red-600 to-red-500 p-12 text-center text-white shadow-2xl">
            <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">Make Your Mark</h1>
                <p class="text-lg md:text-xl font-medium text-white/80">
                    Discover opportunities to contribute, lead, and grow while making a real difference in your community.
                </p>
            </div>
            <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Filter Bar --}}
        <div class="glass-panel p-6 -mt-20 relative z-20 shadow-2xl border-white/20">
            <form action="{{ route('people.volunteer.opportunities') }}" method="GET" 
                  class="flex flex-wrap gap-4 items-end">
                
                <div class="flex-1 min-w-[280px]">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Search Opportunities</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Try 'Medical', 'Kathmandu', 'Teaching'..." 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary text-sm font-medium transition-all outline-none">
                    </div>
                </div>

                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Event Type</label>
                    <div class="relative">
                        <select name="type" class="block w-full py-3.5 px-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary text-sm font-bold appearance-none cursor-pointer outline-none">
                            <option value="">All Types</option>
                            <option value="0" {{ request('type') == '0' ? 'selected' : '' }}>Online</option>
                            <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Offline</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="status" class="block w-full py-3.5 px-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary text-sm font-bold appearance-none cursor-pointer outline-none">
                            <option value="">Active (Upcoming/Live)</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming Only</option>
                            <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live Now</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Past Events</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none btn-primary py-3.5 px-8">
                        Find Events
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <a href="{{ route('people.volunteer.opportunities') }}" 
                           class="flex-1 lg:flex-none btn-secondary py-3.5 px-8">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Events Grid Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse ($events as $event)
                <div class="glass-panel group hover:shadow-2xl hover:border-primary/20 transition-all duration-500 overflow-hidden flex flex-col">
                    {{-- Event Image --}}
                    <div class="relative h-56 overflow-hidden">
                        @if($event->cover_image_path_name)
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-100 flex items-center justify-center text-red-300">
                                <span class="iconify text-7xl" data-icon="fluent:calendar-sparkle-24-regular"></span>
                            </div>
                        @endif
                        {{-- Type Badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 rounded-xl bg-white/90 backdrop-blur-md text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                                {{ $event->type == 0 ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex-1">
                            <h2 class="text-2xl font-black text-slate-900 mb-3 group-hover:text-primary transition-colors">
                                {{ $event->title }}
                            </h2>

                            <!-- Event Meta -->
                            <div class="flex flex-wrap items-center gap-4 text-slate-400 mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center items-center text-primary/70">
                                        <span class="iconify text-lg" data-icon="fluent:calendar-ltr-24-regular"></span>
                                    </div>
                                    <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center items-center text-primary/70">
                                        <span class="iconify text-lg" data-icon="fluent:location-24-regular"></span>
                                    </div>
                                    <span class="text-xs font-bold">{{ Str::limit($event->location, 25) }}</span>
                                </div>
                            </div>

                            <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8 line-clamp-3">
                                {{ $event->description }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-3 mt-auto">
                            <button type="button"
                                class="w-full font-black py-3 rounded-xl shadow-sm transition-all duration-300 uppercase tracking-widest text-[10px]
                                {{ $event->is_registered 
                                    ? 'bg-emerald-500 text-white cursor-not-allowed' 
                                    : 'btn-primary' 
                                }}"
                                data-event-id="{{ $event->id }}"
                                {{ $event->is_registered ? 'disabled' : '' }}>
                                <span class="flex items-center justify-center gap-2">
                                    @if($event->is_registered)
                                        <i class="fas fa-check-circle text-sm"></i>
                                        Applied
                                    @else
                                        Apply Now
                                    @endif
                                </span>
                            </button>
                            <a href="{{ route('people.volunteer.details', $event->id) }}"
                                class="btn-secondary w-full py-3 text-[10px] font-black uppercase tracking-widest">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <span class="iconify text-6xl" data-icon="fluent:info-24-regular"></span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">No opportunities found</h3>
                    <p class="text-slate-500 font-medium">Try adjusting your filters or check back later.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = "{{ csrf_token() }}";

        document.addEventListener('DOMContentLoaded', () => {
            const allButtons = document.querySelectorAll('button[data-event-id]');
            const toastContainer = document.getElementById('toast-container');

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

            allButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const eventId = button.getAttribute('data-event-id');
                    
                    button.disabled = true;
                    button.style.minWidth = button.offsetWidth + 'px';
                    const originalContent = button.innerHTML;
                    
                    button.innerHTML = `
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                        })

                        const data = await response.json();

                        if (response.ok) {
                            showToast(data.message);
                            button.innerHTML = `
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    Applied
                                </div>
                            `;
                            button.classList.remove('btn-primary');
                            button.classList.add('bg-emerald-500', 'text-white', 'cursor-not-allowed');
                        } else {
                            showToast(data.message || "Failed to apply.", 'failure');
                            button.disabled = false;
                            button.innerHTML = originalContent;
                        }
                    } catch (error) {
                        showToast('Connection error. Please try again.', 'failure');
                        button.disabled = false;
                        button.innerHTML = originalContent;
                    }
                })
            });
        });
    </script>
@endpush
