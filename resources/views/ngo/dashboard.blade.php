@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">NGO Dashboard</h1>
            <p class="text-slate-500 font-medium mt-1">Welcome back, {{ auth()->user()->name }}. Here's what's happening with your organization.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('ngo.events.create') }}" class="btn-primary px-6 py-3">
                <span class="iconify" data-icon="fluent:add-circle-24-filled"></span>
                Create Event
            </a>
            <button type="button" onclick="openCreatePostModal()" class="btn-secondary px-6 py-3">
                Create Post
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Events Stat -->
        <div class="glass-panel px-5 py-4 flex items-center gap-4 group hover:border-primary/30 transition-all duration-300">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 shadow-sm group-hover:scale-110 transition-transform">
                <span class="iconify text-2xl" data-icon="fluent:calendar-ltr-24-filled"></span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Events</span>
                <span class="text-xl font-black text-slate-900">{{ $totalEvents }}</span>
            </div>
        </div>

        <!-- Followers Stat -->
        <div class="glass-panel px-5 py-4 flex items-center gap-4 group hover:border-red-200 transition-all duration-300">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 shadow-sm group-hover:scale-110 transition-transform">
                <span class="iconify text-2xl" data-icon="fluent:people-community-24-filled"></span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Followers</span>
                <span class="text-xl font-black text-slate-900">{{ $totalFollowers }}</span>
            </div>
        </div>

        <!-- Volunteers Stat -->
        <div class="glass-panel px-5 py-4 flex items-center gap-4 group hover:border-amber-200 transition-all duration-300">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shadow-sm group-hover:scale-110 transition-transform">
                <span class="iconify text-2xl" data-icon="fluent:handshake-24-filled"></span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Volunteers</span>
                <span class="text-xl font-black text-slate-900">{{ $totalVolunteers }}</span>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column: Upcoming Events & Quick Actions -->
        <div class="lg:col-span-3 space-y-6">
            @include('ngo.partials.churn_widget')

            <!-- Upcoming Events Card -->
            <div class="glass-panel overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Upcoming Events</h2>
                    <a href="{{ route('ngo.events') }}" class="text-xs font-black text-primary uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($upcomingEvents as $event)
                        <div class="p-6 flex items-center justify-between hover:bg-slate-50/50 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-400 overflow-hidden group-hover:bg-primary/10 group-hover:text-primary transition-all">
                                    @if($event->cover_image_path_name)
                                        <img src="{{ asset('storage/' . $event->cover_image_path_name) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="iconify text-2xl" data-icon="fluent:calendar-sparkle-24-regular"></span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $event->title }}</h3>
                                    <div class="flex items-center gap-3 mt-1 text-xs font-medium text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-calendar"></i>
                                            {{ $event->start_date->format('M d, Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-clock"></i>
                                            {{ $event->start_date->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    {{ $event->type == 0 ? 'Online' : 'Offline' }}
                                </span>
                                <a href="{{ route('ngo.event.details', $event->id) }}" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="iconify text-xl" data-icon="fluent:chevron-right-24-filled"></span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400">
                            <p class="text-sm font-medium">No upcoming events scheduled.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Interactions / Feed Stats -->
            <div class="glass-panel p-6">
                <h2 class="text-lg font-black text-slate-900 tracking-tight mb-6">Recent Post Performance</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($recentPosts as $post)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/30 hover:border-primary/20 transition-all group">
                            <p class="text-sm text-slate-600 font-medium line-clamp-2 mb-4">{{ $post->description ?: 'Update with media' }}</p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                                <div class="flex items-center gap-4 text-xs font-bold">
                                    <span class="flex items-center gap-1 text-red-500">
                                        <i class="fas fa-heart"></i> {{ $post->likes_count }}
                                    </span>
                                    <span class="flex items-center gap-1 text-red-500">
                                        <i class="fas fa-comment"></i> {{ $post->comments_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Navigation -->
        <div class="space-y-6">
            <!-- Quick Navigation Card -->
            <div class="glass-panel p-6">
                <h2 class="text-lg font-black text-slate-900 tracking-tight mb-6">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('ngo.volunteers') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:bg-white transition-all group">
                        <span class="iconify text-2xl text-slate-400 group-hover:text-primary mb-2" data-icon="fluent:people-community-20-regular"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-slate-900">Volunteers</span>
                    </a>
                    <a href="{{ route('ngo.followers') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:bg-white transition-all group">
                        <span class="iconify text-2xl text-slate-400 group-hover:text-primary mb-2" data-icon="fluent:heart-20-regular"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-slate-900">Followers</span>
                    </a>
                    <a href="{{ route('ngo.notifications') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:bg-white transition-all group">
                        <span class="iconify text-2xl text-slate-400 group-hover:text-primary mb-2" data-icon="fluent:alert-20-regular"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-slate-900">Alerts</span>
                    </a>
                    <a href="{{ route('ngo.profile.edit') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:bg-white transition-all group">
                        <span class="iconify text-2xl text-slate-400 group-hover:text-primary mb-2" data-icon="fluent:edit-20-regular"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-slate-900">Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('modals')
<!-- Create Post Modal -->
<div id="createPostModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreatePostModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-modal-enter">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Create post</h3>
                <button onclick="closeCreatePostModal()" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all">
                    <span class="iconify text-2xl" data-icon="fluent:dismiss-24-filled"></span>
                </button>
            </div>

            <form action="{{ route('common.post.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <!-- User Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-primary/20 bg-slate-100 p-0.5">
                            @if (auth()->user()->ngo && auth()->user()->ngo->logo)
                                <img src="{{ asset('storage/' . auth()->user()->ngo->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-[14px]">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary">
                                    <i class="fas fa-building text-lg"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="block font-black text-slate-900 leading-none mb-1">{{ auth()->user()->name }}</span>
                            <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500">
                                <span class="iconify text-xs" data-icon="fluent:people-community-16-filled"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Followers</span>
                                <span class="iconify text-xs" data-icon="fluent:chevron-down-16-filled"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Textarea -->
                    <textarea name="description" rows="4" 
                        class="w-full border-none focus:ring-0 text-lg font-medium text-slate-700 placeholder:text-slate-400 resize-none px-0"
                        placeholder="What's on your mind, {{ auth()->user()->name }}?"></textarea>

                    <!-- Milestone Selector (Conditional) -->
                    @if (isset($milestones) && count($milestones) > 0)
                    <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-2xl border border-slate-100 group focus-within:border-primary/30 transition-all">
                        <span class="iconify text-amber-500 text-xl" data-icon="fluent:board-24-filled"></span>
                        <select name="milestone_id" class="text-xs font-bold text-slate-600 bg-transparent border-none focus:ring-0 cursor-pointer flex-1">
                            <option value="">Link to Milestone (Optional)</option>
                            @foreach ($milestones as $milestone)
                                <option value="{{ $milestone->id }}">{{ $milestone->event->title }}: {{ $milestone->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Selected Images Preview -->
                    <div id="modal-image-preview" class="grid grid-cols-2 gap-2 max-h-[200px] overflow-y-auto scrollbar-hide"></div>

                    <!-- Add to post toolbar -->
                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-200">
                        <span class="text-sm font-bold text-slate-700 ml-2">Add to your post</span>
                        <div class="flex items-center gap-1">
                            <input type="file" name="post_media[]" id="modal_post_media" accept="image/*" multiple class="hidden">
                            <label for="modal_post_media" class="w-10 h-10 rounded-full flex items-center justify-center text-emerald-500 hover:bg-emerald-50 transition-all cursor-pointer" title="Add Photo">
                                <span class="iconify text-2xl" data-icon="fluent:image-add-24-filled"></span>
                            </label>
                            <button type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-blue-500 hover:bg-blue-50 transition-all" title="Tag People">
                                <span class="iconify text-2xl" data-icon="fluent:person-tag-24-filled"></span>
                            </button>
                            <button type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-amber-500 hover:bg-amber-50 transition-all" title="Feeling/Activity">
                                <span class="iconify text-2xl" data-icon="fluent:emoji-24-filled"></span>
                            </button>
                            <button type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition-all" title="Add Location">
                                <span class="iconify text-2xl" data-icon="fluent:location-24-filled"></span>
                            </button>
                            <button type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-indigo-500 hover:bg-indigo-50 transition-all" title="GIF">
                                <span class="iconify text-2xl" data-icon="fluent:gif-24-filled"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50/50">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white py-3.5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes modal-enter {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-enter {
    animation: modal-enter 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
</style>
@endpush

@push('scripts')
<script>
function openCreatePostModal() {
    document.getElementById('createPostModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreatePostModal() {
    document.getElementById('createPostModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Clear form
    const form = document.querySelector('#createPostModal form');
    form.reset();
    document.getElementById('modal-image-preview').innerHTML = '';
}

document.getElementById('modal_post_media').addEventListener('change', function(e) {
    const preview = document.getElementById('modal-image-preview');
    preview.innerHTML = '';
    const files = e.target.files;

    if (files.length > 0) {
        Array.from(files).forEach((file, index) => {
            if (!file.type.match('image.*')) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group rounded-xl overflow-hidden aspect-video border border-slate-200';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-red-400 transition-colors">
                            <span class="iconify text-2xl" data-icon="fluent:delete-24-filled"></span>
                        </button>
                    </div>
                `;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('createPostModal').classList.contains('hidden')) {
        closeCreatePostModal();
    }
});
</script>
@endpush
