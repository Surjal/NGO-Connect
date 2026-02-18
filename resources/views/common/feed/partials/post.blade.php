@foreach ($posts as $post)
    @if(!$post->user) @continue @endif
    <article id="post-{{ $post->id }}" class="glass-panel overflow-hidden transition-all duration-300 mb-6 group hover:shadow-xl hover:-translate-y-1">

        <!-- === MILESTONE / IMPACT REPORT TAG === -->
        @if ($post->milestone)
            <div class="bg-gradient-to-r from-amber-500/10 to-transparent px-6 py-4 border-b border-white/20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200/50">
                        <span class="iconify text-xl" data-icon="fluent:board-arrow-right-20-filled"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Impact Milestone</span>
                        <h4 class="text-sm font-bold text-slate-900 leading-tight">
                            {{ $post->milestone->event->title }}: <span class="text-amber-600">{{ $post->milestone->title }}</span>
                        </h4>
                    </div>
                </div>
                <a href="{{ route('people.volunteer.details', $post->milestone->event_id) }}" 
                   class="px-4 py-2 bg-white border border-amber-200 rounded-xl text-[10px] font-black text-amber-600 uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                    View Project
                </a>
            </div>
        @endif

        <!-- === REPORT STATUS BANNER === -->
        @if ($post->user_reported)
            <div class="bg-red-50 border-l-4 border-red-500 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3 text-red-700">
                    <i class="fas fa-flag"></i>
                    <span class="font-bold text-sm">You've reported this post for review</span>
                </div>
                <button class="text-red-600 hover:text-red-800 text-xs font-black uppercase tracking-widest underline"
                    onclick="event.preventDefault(); document.getElementById('undo-report-form-{{ $post->id }}').submit();">
                    Undo
                </button>
                <form id="undo-report-form-{{ $post->id }}" method="POST"
                    action="{{ route('post.undo-report', $post->id) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @elseif ($post->reports_count > 0)
            <div class="bg-amber-50 border-l-4 border-amber-500 px-6 py-3 text-xs font-bold text-amber-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Flagged by {{ $post->reports_count }} {{ Str::plural('user', $post->reports_count) }}
            </div>
        @endif

        <!-- Post Header -->
        <div class="p-6 pb-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-2xl border-2 border-red-100 bg-red-50 flex items-center justify-center p-0.5 relative">
                        @if ($post->user->ngo && $post->user->ngo->logo)
                            <img src="{{ asset('storage/' . $post->user->ngo->logo) }}" alt="NGO Logo" class="w-full h-full object-cover rounded-[14px]">
                        @else
                            <i class="fas fa-hands-helping text-red-500 text-xl"></i>
                        @endif
                        <!-- Verified Badge (Static for now) -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                            <i class="fas fa-check text-[10px] text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('common.ngo.profile', $post->user->id) }}"
                                class="font-black text-slate-900 hover:text-primary transition-colors">
                                @isset($post->user->ngo->ngo_name)
                                    {{ $post->user->ngo->ngo_name ?: $post->user->name }}
                                @else
                                    {{ $post->user->name }}
                                @endisset
                            </a>
                            @if(auth()->check() && auth()->id() != $post->user_id)
                                <button id="follow-btn-{{ $post->id }}" data-ngo-id="{{ $post->user->id }}"
                                    class="follow-button px-3 py-1 rounded-full text-[10px] font-black transition-all uppercase tracking-widest border
                                    {{ $post->is_following ? 'bg-slate-100 text-slate-500 border-slate-200' : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-600 hover:text-white' }}">
                                    {{ $post->is_following ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center mt-1 text-xs font-medium text-slate-400">
                            <i class="far fa-clock mr-1.5"></i>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Post Options -->
                <div class="relative">
                    <button class="post-options-btn p-2 hover:bg-slate-100 rounded-xl transition-all"
                        data-post-id="{{ $post->id }}">
                        <i class="fas fa-ellipsis-v text-slate-400"></i>
                    </button>
                    <div class="post-options-dropdown hidden absolute right-0 top-full mt-2 w-56 glass-panel p-1 z-10 shadow-xl border border-white/20"
                        data-post-id="{{ $post->id }}">
                        <div class="py-1">
                            @if (!$post->user_reported)
                                <button class="report-post-btn w-full text-left px-4 py-3 text-sm font-bold text-slate-600 hover:bg-red-50 hover:text-red-600 rounded-lg flex items-center gap-3 transition-all"
                                    data-post-id="{{ $post->id }}">
                                    <i class="fas fa-flag"></i>
                                    <span>Report Inappropriate</span>
                                </button>
                            @endif

                            @if (auth()->check() && auth()->id() === $post->user_id)
                                <form action="{{ route('ngo.post.delete', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg flex items-center gap-3 transition-all">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete Post</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post Content -->
        <div class="px-6 pb-6">
            <p class="text-slate-600 leading-relaxed font-medium whitespace-pre-wrap">{{ $post->description }}</p>
        </div>

        <!-- Post Media -->
        @if ($post->medias->count() > 0)
            <div class="px-6 pb-6">
                @php
                    $medias = $post->medias;
                    $count = $medias->count();
                    $firstMedia = $medias->first();
                @endphp

                <div class="rounded-[2rem] overflow-hidden border border-slate-100 shadow-inner">
                    @if ($count === 1 && $firstMedia->media_type === 'video')
                        <div class="w-full">
                            <video controls class="w-full max-h-[500px] bg-black">
                                <source src="{{ asset('storage/' . $firstMedia->media_path_name) }}" type="video/mp4">
                                Your browser does not support video.
                            </video>
                        </div>
                    @else
                        <div class="{{ $count > 1 ? 'grid gap-1' : '' }}"
                            style="{{ $count == 2
                                ? 'grid-template-columns: 1fr 1fr;'
                                : ($count >= 3
                                    ? 'grid-template-columns: 1fr 1fr; grid-template-rows: auto auto;'
                                    : '') }}">

                            @if ($count == 1)
                                <img src="{{ asset('storage/' . $medias[0]->media_path_name) }}" alt="Post Image"
                                    class="w-full h-auto max-h-[600px] object-cover cursor-zoom-in image-modal-trigger transition-transform duration-500 hover:scale-[1.02]"
                                    data-post-id="{{ $post->id }}" data-image-index="0">
                            @elseif ($count == 2)
                                @foreach ($medias as $index => $media)
                                    <img src="{{ asset('storage/' . $media->media_path_name) }}" alt="Post Image"
                                        class="w-full h-[400px] object-cover cursor-zoom-in image-modal-trigger hover:opacity-90 transition-opacity"
                                        data-post-id="{{ $post->id }}" data-image-index="{{ $index }}">
                                @endforeach
                            @elseif ($count == 3)
                                <img src="{{ asset('storage/' . $medias[0]->media_path_name) }}" alt="Post Image"
                                    class="w-full h-full object-cover row-span-2 cursor-zoom-in image-modal-trigger hover:opacity-90 transition-opacity"
                                    data-post-id="{{ $post->id }}" data-image-index="0">
                                <div class="flex flex-col gap-1">
                                    <img src="{{ asset('storage/' . $medias[1]->media_path_name) }}" alt="Post Image"
                                        class="w-full h-[250px] object-cover cursor-zoom-in image-modal-trigger hover:opacity-90 transition-opacity"
                                        data-post-id="{{ $post->id }}" data-image-index="1">
                                    <img src="{{ asset('storage/' . $medias[2]->media_path_name) }}" alt="Post Image"
                                        class="w-full h-[250px] object-cover cursor-zoom-in image-modal-trigger hover:opacity-90 transition-opacity"
                                        data-post-id="{{ $post->id }}" data-image-index="2">
                                </div>
                            @else
                                @foreach ($medias->take(4) as $index => $media)
                                    <div class="relative group/media overflow-hidden">
                                        <img src="{{ asset('storage/' . $media->media_path_name) }}" alt="Post Image"
                                            class="w-full h-[300px] object-cover cursor-zoom-in image-modal-trigger transition-transform duration-500 group-hover/media:scale-105"
                                            data-post-id="{{ $post->id }}" data-image-index="{{ $index }}">
                                        @if ($index == 3 && $count > 4)
                                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex flex-col items-center justify-center cursor-pointer image-modal-trigger"
                                                data-post-id="{{ $post->id }}" data-image-index="3">
                                                <span class="text-white text-3xl font-black">+{{ $count - 4 }}</span>
                                                <span class="text-white/80 text-[10px] uppercase font-black tracking-widest mt-1">Photos</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-red-500 border-2 border-white flex items-center justify-center shadow-sm">
                            <i class="fas fa-heart text-white text-[10px]"></i>
                        </div>
                    </div>
                    <span id="likes-{{ $post->id }}" class="text-sm font-bold text-slate-700">
                        {{ $post->likes->count() }} <span class="text-slate-400 font-medium">Likes</span>
                    </span>
                </div>
                <div class="flex items-center gap-2 group cursor-pointer">
                    <span id="comment-{{ $post->id }}" class="text-sm font-bold text-slate-700 group-hover:text-primary transition-colors">
                        {{ $post->comments->count() }}
                    </span>
                    <span class="text-xs font-medium text-slate-400 group-hover:text-primary transition-colors">
                        {{ Str::plural('Comment', $post->comments->count()) }}
                    </span>
                </div>
            </div>

            <!-- Main Actions -->
            <div class="flex items-center gap-3">
                <button data="{{ $post->id }}"
                    class="like-button flex-1 flex items-center justify-center gap-3 py-3.5 rounded-2xl transition-all font-bold text-sm
                    {{ $post->is_liked ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm' }}">
                    <i class="fa-heart {{ $post->is_liked ? 'fas' : 'far' }} text-lg"></i>
                    <span>{{ $post->is_liked ? 'Loved' : 'Love' }}</span>
                </button>

                <button data="{{ $post->id }}"
                    class="comment-button flex-1 flex items-center justify-center gap-3 py-3.5 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 shadow-sm transition-all">
                    <i class="far fa-comment text-lg"></i>
                    <span>Comment</span>
                </button>
            </div>
        </div>
    </article>
@endforeach
