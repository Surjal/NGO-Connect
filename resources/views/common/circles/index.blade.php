@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="relative bg-red-700 px-6 py-8 sm:px-8 sm:py-10">
                    <div class="absolute inset-0 opacity-100"
                        style="background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 20px 20px;">
                    </div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div class="flex items-start gap-4 sm:gap-5">
                            <div
                                class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-white shadow-sm">
                                @if ($ngo->ngo && $ngo->ngo->logo)
                                    <img src="{{ asset('storage/' . $ngo->ngo->logo) }}" alt="{{ $ngo->name }}"
                                        class="h-full w-full object-cover">
                                @elseif ($ngo->profile_photo)
                                    <img src="{{ asset('storage/' . $ngo->profile_photo) }}" alt="{{ $ngo->name }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-red-600">{{ strtoupper(substr($ngo->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-100">Community Circle</p>
                                <h1 class="mt-2 text-2xl font-bold text-white sm:text-3xl">{{ $ngo->name }}</h1>
                                <p class="mt-2 max-w-2xl text-sm text-red-50/90">
                                    A shared space for supporters, volunteers, and followers to ask questions, exchange ideas,
                                    and stay close to {{ $ngo->name }}.
                                </p>
                                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-red-50">
                                    @if ($ngo->ngo && $ngo->ngo->category)
                                        <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1.5 font-medium">
                                            {{ $ngo->ngo->category }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-2 text-red-100">
                                        <i class="fas fa-comments text-red-200"></i>
                                        <span class="font-semibold text-white">{{ $threads->count() }}</span>
                                        <span>{{ \Illuminate\Support\Str::plural('thread', $threads->count()) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
                            <a href="{{ route('common.ngo.profile', $ngo->id) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-medium text-white transition-colors duration-200 hover:bg-white/15">
                                <span class="iconify" data-icon="fluent:person-accounts-20-filled"></span>
                                NGO Profile
                            </a>
                            <button type="button" onclick="document.getElementById('newThreadModal').classList.remove('hidden')"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-red-600 transition-colors duration-200 hover:bg-red-50 active:scale-95">
                                <span class="iconify" data-icon="fluent:add-circle-20-filled"></span>
                                Start Discussion
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 border-b border-gray-100 bg-gray-50 px-6 py-5 sm:grid-cols-3 sm:px-8">
                    <div class="rounded-2xl border border-gray-100 bg-white p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Open Threads</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $threads->count() }}</p>
                        <p class="mt-1 text-xs text-gray-400">Current conversations in this circle.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Replies Shared</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $threads->sum(fn($thread) => $thread->replies->count()) }}</p>
                        <p class="mt-1 text-xs text-gray-400">Community responses across all discussions.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Latest Activity</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900">
                            {{ optional($threads->first())->created_at?->diffForHumans() ?? 'No activity yet' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">Most recent thread started in this circle.</p>
                    </div>
                </div>

                <div class="px-6 py-6 sm:px-8">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Discussion Board</h2>
                            <p class="mt-1 text-sm text-gray-400">Browse community questions, ideas, and updates.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if ($threads->count() > 0)
                            @foreach ($threads as $thread)
                                <a href="{{ route('common.circles.show', $thread->id) }}"
                                    class="group block rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-200 hover:border-red-100 hover:shadow-md">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0 flex-1">
                                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-red-600">
                                                    Discussion
                                                </span>
                                                <span class="text-xs text-gray-400">{{ $thread->created_at->diffForHumans() }}</span>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 transition-colors duration-200 group-hover:text-red-600">
                                                {{ $thread->title }}
                                            </h3>
                                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-gray-500">
                                                {{ $thread->content }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-3 sm:justify-end">
                                            <div class="flex -space-x-2">
                                                @foreach ($thread->replies->take(3) as $reply)
                                                    <div
                                                        class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full border-2 border-white bg-gray-100 text-xs font-semibold text-gray-500">
                                                        @if ($reply->user->profile_photo)
                                                            <img src="{{ asset('storage/' . $reply->user->profile_photo) }}"
                                                                class="h-full w-full object-cover" alt="{{ $reply->user->name }}">
                                                        @else
                                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                @endforeach
                                                @if ($thread->replies->count() > 3)
                                                    <div
                                                        class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-gray-50 text-[10px] font-semibold text-gray-500">
                                                        +{{ $thread->replies->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">{{ $thread->replies->count() }}</p>
                                                <p class="text-xs uppercase tracking-wide text-gray-400">
                                                    {{ \Illuminate\Support\Str::plural('Reply', $thread->replies->count()) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-xl bg-gray-50 text-sm font-semibold text-gray-500">
                                                @if ($thread->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $thread->user->profile_photo) }}"
                                                        class="h-full w-full object-cover" alt="{{ $thread->user->name }}">
                                                @else
                                                    {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-gray-900">{{ $thread->user->name }}</p>
                                                <p class="text-xs text-gray-400">Started this conversation</p>
                                            </div>
                                        </div>
                                        <span class="iconify text-lg text-gray-300 transition-colors duration-200 group-hover:text-red-500"
                                            data-icon="fluent:chevron-right-20-filled"></span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-gray-300 shadow-sm">
                                    <span class="iconify text-2xl" data-icon="fluent:chat-bubbles-question-24-regular"></span>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-gray-900">No discussions yet</h3>
                                <p class="mt-2 text-sm text-gray-400">
                                    Start the first thread and give this community a place to connect.
                                </p>
                                <button type="button"
                                    onclick="document.getElementById('newThreadModal').classList.remove('hidden')"
                                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-red-500 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-red-600 active:scale-95">
                                    Start Discussion
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    <div id="newThreadModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity"
            onclick="document.getElementById('newThreadModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative w-full transform overflow-hidden rounded-2xl border border-gray-100 bg-white text-left shadow-xl transition-all sm:my-8 sm:max-w-lg">
                    <form action="{{ route('common.circles.storeThread', $ngo->id) }}" method="POST" class="p-6 sm:p-7">
                        @csrf
                        <div class="mb-6">
                            <h3 id="modal-title" class="text-xl font-bold text-gray-900">Start a discussion</h3>
                            <p class="mt-1 text-sm text-gray-500">Ask a question, share an update, or invite community input.</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">Thread Title</label>
                                <input type="text" name="title" required placeholder="What would you like to discuss?"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 placeholder-gray-300 transition-colors duration-200 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100">
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">Content</label>
                                <textarea name="content" rows="5" required placeholder="Provide some context for your discussion..."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 placeholder-gray-300 transition-colors duration-200 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <button type="button" onclick="document.getElementById('newThreadModal').classList.add('hidden')"
                                class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 rounded-xl bg-red-500 px-4 py-3 text-sm font-semibold text-white transition-colors duration-200 hover:bg-red-600 active:scale-95">
                                Post Thread
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
