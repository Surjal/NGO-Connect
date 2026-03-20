@extends('layouts.app')

@section('content')
    <div id="toast-container" class="fixed top-5 right-5 space-y-2 z-50"></div>

    <div class="space-y-12 pb-20">
        {{-- Hero Section --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-purple-800 via-purple-600 to-pink-500 p-12 text-center text-white shadow-2xl">
            <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <circle cx="50" cy="50" r="40" fill="white" stroke="white" stroke-width="2" />
                </svg>
            </div>
            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-md mb-6 border border-white/30 text-sm font-bold tracking-widest uppercase">
                    <i class="fas fa-sparkles text-yellow-300"></i> AI-Powered Recommendations
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">Personalized For You</h1>
                <p class="text-lg md:text-xl font-medium text-white/90">
                    Based on your activity, we've found NGOs and volunteer opportunities that align perfectly with your interests.
                </p>
                
                @if($topCategory)
                    <div class="mt-8 flex flex-col items-center">
                        <span class="text-xs uppercase tracking-widest font-bold text-white/70 mb-2">Your Top Interest</span>
                        <div class="px-6 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 font-black text-xl shadow-inner">
                            {{ $topCategory }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-pink-400/30 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-400/30 rounded-full blur-3xl"></div>
        </div>

        {{-- Section: Recommended Events --}}
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-calendar-star text-2xl text-purple-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Events You'll Love</h2>
                    <p class="text-slate-500 font-medium text-sm">Opportunities matched to your preferences</p>
                </div>
            </div>

            @if($recommendedEvents->isEmpty())
                <div class="glass-panel p-12 text-center border-dashed border-2">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-calendar-times text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">No event recommendations right now</h3>
                    <p class="text-slate-500 font-medium text-sm max-w-sm mx-auto">Interact more with NGOs and events to help our AI learn what you care about!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendedEvents as $event)
                        <div class="glass-panel group hover:shadow-2xl hover:border-purple-500/20 transition-all duration-500 overflow-hidden flex flex-col bg-white">
                            {{-- Image Header --}}
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                @if($event->cover_image_path_name)
                                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-100 to-pink-50 flex items-center justify-center text-purple-200">
                                        <i class="fas fa-calendar-day text-5xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-lg bg-white/95 backdrop-blur-md text-[9px] font-black uppercase tracking-widest text-slate-800 shadow-sm">
                                        {{ $event->type == 0 ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <div class="bg-indigo-600/90 backdrop-blur-md rounded-xl p-2 px-3 flex items-center gap-2 shadow-lg border border-indigo-400/30">
                                        <i class="fas fa-robot text-indigo-200 text-xs"></i>
                                        <span class="text-[10px] font-bold text-white line-clamp-1">
                                            {{ $event->recommendation_reason }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="font-black text-lg text-slate-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                
                                <div class="flex items-center gap-2 text-slate-500 mb-4 text-xs font-semibold">
                                    <i class="fas fa-clock text-slate-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y • h:i A') }}</span>
                                </div>

                                <div class="mt-auto grid {{ $event->is_volunteers_required ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
                                    @if($event->is_volunteers_required)
                                        <button type="button"
                                            class="w-full font-black py-2.5 rounded-xl shadow-sm transition-all duration-300 uppercase tracking-wider text-[10px] bg-purple-600 hover:bg-purple-700 text-white"
                                            data-event-id="{{ $event->id }}">
                                            Apply Now
                                        </button>
                                    @endif
                                    <a href="{{ route('people.volunteer.details', $event->id) }}"
                                        class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-center font-black transition-all duration-300 uppercase tracking-wider text-[10px]">
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Section: Recommended NGOs --}}
        <div class="mt-16">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-building-ngo text-2xl text-indigo-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900">NGOs For You</h2>
                    <p class="text-slate-500 font-medium text-sm">Organizations working on causes you care about</p>
                </div>
            </div>

            @if($recommendedNgos->isEmpty())
                <div class="glass-panel p-12 text-center border-dashed border-2">
                    <h3 class="text-lg font-black text-slate-900 mb-2">No NGO recommendations</h3>
                    <p class="text-slate-500 font-medium text-sm max-w-sm mx-auto">We've run out of verified NGOs to recommend. Follow some to see more!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendedNgos as $ngo)
                        <div class="glass-panel group hover:shadow-2xl hover:border-indigo-500/20 transition-all duration-500 p-6 flex flex-col bg-white">
                            
                            {{-- AI Reason Badge --}}
                            <div class="bg-indigo-50 text-indigo-700 rounded-lg p-2 px-3 flex items-center gap-2 mb-5 border border-indigo-100">
                                <i class="fas fa-lightbulb text-indigo-400 text-xs shadow-sm"></i>
                                <span class="text-[10px] font-bold line-clamp-1">
                                    {{ $ngo->recommendation_reason }}
                                </span>
                            </div>

                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-16 h-16 rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex-shrink-0 bg-slate-50">
                                    @if ($ngo->logo)
                                        <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->ngo_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <i class="fas fa-building text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-black text-lg text-slate-900 truncate mb-1 group-hover:text-indigo-600 transition-colors">
                                        {{ $ngo->ngo_name }}
                                    </h3>
                                    @if($ngo->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                                            {{ $ngo->category }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm font-medium text-slate-500 line-clamp-2 mb-6 flex-1">
                                {{ $ngo->mission ?? $ngo->description ?? 'Dedicated to making a difference in the community.' }}
                            </p>

                            <div class="grid grid-cols-2 gap-2 mt-auto">
                                <button type="button" onclick="followNgo({{ $ngo->user_id }}, this)" 
                                    class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-black text-[10px] uppercase tracking-wider transition-all shadow-sm">
                                    + Follow
                                </button>
                                <a href="{{ route('people.ngo.profile', $ngo->id) }}" 
                                    class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-center font-black uppercase tracking-wider text-[10px] transition-all">
                                    Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Section: Recommended Posts --}}
        <div class="mt-16">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-pink-100 flex items-center justify-center">
                    <i class="fas fa-newspaper text-2xl text-pink-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Posts For You</h2>
                    <p class="text-slate-500 font-medium text-sm">Updates and stories tailored to your interests</p>
                </div>
            </div>

            @if($recommendedPosts->isEmpty())
                <div class="glass-panel p-12 text-center border-dashed border-2">
                    <h3 class="text-lg font-black text-slate-900 mb-2">No post recommendations</h3>
                    <p class="text-slate-500 font-medium text-sm max-w-sm mx-auto">We couldn't find any recent posts matching your exact interests. Check back later!</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($recommendedPosts as $post)
                        <div class="glass-panel p-6 bg-white hover:shadow-xl hover:border-pink-500/20 transition-all duration-300">
                            
                            {{-- AI Reason Badge --}}
                            <div class="bg-pink-50 text-pink-700 rounded-lg p-2 px-3 flex items-center gap-2 border border-pink-100 mb-4 self-start inline-flex">
                                <i class="fas fa-magic text-pink-400 text-xs shadow-sm"></i>
                                <span class="text-[10px] font-bold">
                                    {{ $post->recommendation_reason }}
                                </span>
                            </div>

                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ optional($post->user->ngo)->logo ? asset('storage/' . $post->user->ngo->logo) : asset('logo-nobg.png') }}" 
                                     alt="Author" class="w-12 h-12 rounded-full object-cover border border-slate-100 shadow-sm">
                                <div>
                                    <h4 class="font-black text-slate-900">{{ $post->user->name }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <h3 class="font-bold text-lg text-slate-800 mb-2">{{ $post->title }}</h3>
                            <p class="text-slate-600 text-sm mb-4 line-clamp-3 leading-relaxed">{{ Str::limit($post->description, 200) }}</p>
                            
                            @if($post->medias && $post->medias->count() > 0)
                                <div class="mb-4 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 max-h-64 flex justify-center">
                                    <img src="{{ asset('storage/' . $post->medias->first()->media_path_name) }}" class="object-cover w-full h-full max-h-64">
                                </div>
                            @endif

                            <div class="flex items-center gap-6 mt-4 pt-4 border-t border-slate-100 font-bold text-sm text-slate-500">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-heart text-pink-500"></i> {{ $post->likes_count ?? $post->likes->count() }} Likes
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-comment text-indigo-400"></i> {{ $post->comments_count ?? $post->comments->count() }} Comments
                                </div>
                                <a href="{{ route('common.feed') }}#post-{{ $post->id }}" class="ml-auto text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider text-[10px]">
                                    View Thread <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = "{{ csrf_token() }}";

    // Application logic for Events (reusing logic from volunteer page)
    document.addEventListener('DOMContentLoaded', () => {
        const applyButtons = document.querySelectorAll('button[data-event-id]');
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

        applyButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const eventId = button.getAttribute('data-event-id');
                
                button.disabled = true;
                const originalContent = button.innerHTML;
                
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
                        showToast(data.message);
                        button.innerHTML = '<i class="fas fa-check"></i> Applied';
                        button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                        button.classList.add('bg-emerald-500', 'cursor-not-allowed');
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
            });
        });
    });

    // Follow NGO logic
    async function followNgo(ngoUserId, button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        try {
            const response = await fetch("{{ route('common.ngo.follow') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ngo_id: ngoUserId })
            });

            const data = await response.json();

            if (response.ok) {
                // Change button style to indicate followed state
                button.innerHTML = '<i class="fas fa-check"></i> Followed';
                button.classList.remove('bg-slate-900', 'hover:bg-slate-800', 'text-white');
                button.classList.add('bg-emerald-100', 'text-emerald-700', 'hover:bg-emerald-200');
            } else {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        } catch (error) {
            button.innerHTML = originalText;
            button.disabled = false;
            console.error('Error:', error);
        }
    }
</script>
@endpush
