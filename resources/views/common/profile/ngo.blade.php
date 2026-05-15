@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-white border-b border-red-100/80">
        <div class="absolute left-0 top-0 z-0 h-44 w-full overflow-hidden rounded-b-2xl bg-red-700 pointer-events-none">
            <div class="absolute inset-0 opacity-100 pointer-events-none"
                style="background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 20px 20px;">
            </div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 pb-10 pt-20">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="shrink-0">
                    <div
                        class="relative z-20 flex h-36 w-36 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-white ring-4 ring-white shadow-lg">
                        @if ($ngo->ngo && $ngo->ngo->logo)
                            <img src="{{ asset('storage/' . $ngo->ngo->logo) }}" alt="{{ $ngo->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-linear-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <span class="text-4xl font-bold text-white">{{ substr($ngo->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex-1 min-w-0 pb-2">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-col items-start gap-2">
                                <h1 title="{{ $ngo->name }}"
                                    class="max-w-3xl text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl truncate">
                                    {{ $ngo->name }}
                                </h1>
                                @if ($ngo->ngo->verified)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-green-400/30 bg-green-400 px-3 py-1 text-xs font-semibold text-white backdrop-blur-md shadow-sm">
                                        <i class="fas fa-badge-check text-green-500"></i>
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <div class="mt-6 flex flex-wrap md:flex-nowrap items-center gap-3 text-sm text-gray-100">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/20 px-4 py-2 text-sm font-medium text-red-500 backdrop-blur-md shadow-sm transition-colors duration-200 hover:bg-white/25">
                                    <i class="fas fa-layer-group text-red-500"></i>
                                    {{ $ngo->ngo->category }}
                                </span>
                                {{-- @if ($ngo->ngo->subcategory)
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/20 px-4 py-2 text-sm font-medium text-red-500 backdrop-blur-md shadow-sm transition-colors duration-200 hover:bg-white/25">
                                        <i class="fas fa-sparkles text-red-500"></i>
                                        {{ $ngo->ngo->subcategory }}
                                    </span>
                                @endif --}}
                                <div id="profile-followers-count-wrapper"
                                    class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/95 px-4 py-2 shadow-sm flex-shrink-0 min-w-[10rem]">
                                    <span
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-white/12 text-red-500">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="leading-tight text-white text-sm">
                                        <div class="flex items-baseline gap-1">
                                            <span id="profile-followers-count"
                                                class="text-xl font-black text-red-500 inline-block text-right font-mono min-w-[2ch]">{{ $followersCount ?? 0 }}</span>
                                            <span class="text-sm font-semibold text-slate-700">followers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (auth()->check() && auth()->id() != $ngo->id)
                            <div
                                class="relative z-20 flex-shrink-0 flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:w-auto xl:justify-end">
                                <a href="{{ route('common.circles.index', $ngo->id) }}"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full border border-white/60 bg-white/95 px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-white hover:text-red-600 active:scale-95">
                                    <span class="iconify" data-icon="fluent:people-community-20-filled"></span>
                                    <span>Circle</span>
                                </a>

                                <a href="{{ route('common.messages.show', $ngo->id) }}"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full border border-white/60 bg-white/95 px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-white hover:text-red-600 active:scale-95">
                                    <span class="iconify" data-icon="fluent:mail-20-filled"></span>
                                    <span>Message</span>
                                </a>

                                <button id="profile-follow-btn" data-ngo-id="{{ $ngo->id }}"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 active:scale-95 min-w-[6rem]
                                        {{ $isFollowing
                                            ? 'border border-white/60 bg-white/95 text-gray-700 hover:text-red-600'
                                            : 'bg-red-500 text-white shadow-sm hover:bg-red-600' }}">
                                    <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }} mr-2"></i>
                                    <span>{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                </button>
                            </div>
                        @endif

                        @if (auth()->check() && auth()->id() == $ngo->id)
                            <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center xl:w-auto xl:justify-end">
                                <a href="{{ route('ngo.profile.edit') }}"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full border border-white/60 bg-white/95 px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-white hover:text-red-500 active:scale-95">
                                    <span class="iconify" data-icon="fluent:edit-20-filled"></span>
                                    <span>Edit Info</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Organization Details</h2>
                        <span
                            class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-red-500">About</span>
                    </div>

                    <div class="space-y-4">
                        <div class="border-t border-gray-100 pt-4">
                            <p
                                class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-red-500">
                                <i class="fas fa-align-left"></i>
                                <span>Description</span>
                            </p>
                            <p class="border-l-2 border-red-200 pl-3 text-sm leading-relaxed text-gray-700">
                                {{ $ngo->ngo->description ?? 'No description provided.' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <p
                                class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-red-500">
                                <i class="fas fa-bullseye"></i>
                                <span>Mission</span>
                            </p>
                            <p class="border-l-2 border-red-200 pl-3 text-sm leading-relaxed text-gray-700">
                                {{ $ngo->ngo->mission ?? 'Mission not provided.' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <p
                                class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-red-500">
                                <i class="fas fa-address-card"></i>
                                <span>Contact Information</span>
                            </p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-start gap-3 border-l-2 border-red-200 pl-3">
                                    <i class="fas fa-phone mt-0.5 text-red-400"></i>
                                    <span class="text-gray-600 font-medium">Phone:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $ngo->ngo->phone ?? 'NA.' }}
                                    </span>
                                </div>

                                <div class="flex items-start gap-3 border-l-2 border-red-200 pl-3">
                                    <i class="fas fa-location-dot mt-0.5 text-red-400"></i>
                                    <span class="text-gray-600 font-medium">Address:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $ngo->ngo->address ?? 'NA' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <p
                                class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-red-500">
                                <i class="fas fa-file-shield"></i>
                                <span>Registration Details</span>
                            </p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-start gap-3 border-l-2 border-red-200 pl-3">
                                    <i class="fas fa-id-card mt-0.5 text-red-400"></i>
                                    <span class="text-gray-600 font-medium">Reg. Number:</span>
                                    <span class="text-gray-600">{{ $ngo->ngo->registration_number }} </span>
                                </div>
                                <div class="flex items-start gap-3 border-l-2 border-red-200 pl-3">
                                    <i class="fas fa-map-location-dot mt-0.5 text-red-400"></i>
                                    <span class="text-gray-600 font-medium">District:</span>
                                    <span class="text-gray-600">{{ $ngo->ngo->registration_district }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div
                        class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wide text-gray-400 transition-colors duration-200 group-hover:text-red-500">
                                    Total Events</p>
                                <p class="mt-1 text-2xl font-bold text-gray-900" id="total-events">{{ $eventsCount }}
                                </p>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 transition-colors duration-200 group-hover:bg-red-100">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wide text-gray-400 transition-colors duration-200 group-hover:text-red-500">
                                    Total Donations</p>
                                <p class="mt-1 text-2xl font-bold text-gray-900" id="total-donations">
                                    ${{ number_format($stats['total_donations'] ?? 0, 2) }}</p>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 transition-colors duration-200 group-hover:bg-red-100">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wide text-gray-400 transition-colors duration-200 group-hover:text-red-500">
                                    Total Followers</p>
                                <p class="mt-1 text-2xl font-bold text-gray-900" id="total-followers">
                                    {{ $followersCount ?? 0 }}
                                </p>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 transition-colors duration-200 group-hover:bg-red-100">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Posts</h2>
                                <p class="mt-0.5 text-sm text-gray-400">Updates, stories, and community highlights</p>
                            </div>
                        </div>
                    </div>
                    <div id="posts-filter"
                        class="flex gap-2 overflow-x-auto border-b border-gray-100 px-5 py-3 relative z-20 pointer-events-auto">
                        <button type="button" data-type="all"
                            class="rounded-full bg-red-500 px-4 py-1.5 text-sm font-semibold text-white transition-all duration-200 hover:bg-red-600 active:scale-95 pointer-events-auto">
                            All
                        </button>
                        <button type="button" data-type="media"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-red-200 hover:text-red-500 active:scale-95 pointer-events-auto">
                            Media
                        </button>
                        <button type="button" data-type="events"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-red-200 hover:text-red-500 active:scale-95 pointer-events-auto">
                            Events
                        </button>
                    </div>

                    <div id="posts-container" class="space-y-5 p-5">
                        @include('common.feed.partials.post', ['post' => $posts])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#profile-follow-btn').on('click', function() {
                var btn = $(this);
                var ngoId = btn.data('ngo-id');

                $.ajax({
                    url: '{{ route('common.ngo.follow') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ngo_id: ngoId
                    },
                    success: function(res) {
                        var countEl = $('#profile-followers-count');
                        var currentCount = parseInt(countEl.text()) || 0;

                        if (res.following) {
                            btn.removeClass(
                                    'bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200'
                                )
                                .addClass('bg-red-500 text-white hover:bg-red-600 shadow-sm');
                            btn.find('i').removeClass('fa-user-plus').addClass('fa-user-check');
                            btn.find('span').text('Following');
                            countEl.text(currentCount + 1);
                        } else {
                            btn.removeClass('bg-red-500 text-white hover:bg-red-600 shadow-sm')
                                .addClass(
                                    'bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200'
                                );
                            btn.find('i').removeClass('fa-user-check').addClass('fa-user-plus');
                            btn.find('span').text('Follow');
                            countEl.text(Math.max(0, currentCount - 1));
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

            // Posts filter click handler
            $('#posts-filter').on('click', 'button', function() {
                var type = $(this).data('type') || 'all';
                var ngoId = {{ $ngo->id }};
                var url = '{{ route('common.ngo.profile.feed', ['id' => $ngo->id]) }}';

                // Toggle active styles
                $('#posts-filter button').removeClass('bg-red-500 text-white font-semibold').addClass(
                    'border-gray-200 text-gray-500');
                $(this).removeClass('border-gray-200 text-gray-500').addClass(
                    'bg-red-500 text-white font-semibold');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        type: type
                    },
                    success: function(html) {
                        $('#posts-container').html(html);
                    },
                    error: function() {
                        alert('Could not load content. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush

@include('common.feed.partials.modal')
