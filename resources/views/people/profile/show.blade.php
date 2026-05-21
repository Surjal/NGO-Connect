@extends('layouts.app')

@push('styles')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .profile-tab {
            flex: 1 1 0%;
            border: 1px solid transparent;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            color: rgb(107 114 128);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: color 200ms ease, background-color 200ms ease, border-color 200ms ease, box-shadow 200ms ease;
        }

        .profile-tab-active {
            border-color: rgb(243 244 246);
            background-color: rgb(255 255 255);
            color: rgb(220 38 38);
            box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.06);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen pb-16">
        <!-- Hero Profile Section -->
        <div class="relative mb-8">
            <div class="h-32 md:h-40 bg-red-700 rounded-b-[2rem]"></div>

            <!-- Profile Info Overlay -->
            <div class="max-w-5xl mx-auto px-6">
                <div
                    class="relative -mt-16 md:-mt-12 flex flex-col md:flex-row items-center md:items-end gap-5 md:gap-7 pb-6">
                    <div class="w-32 h-32 md:w-36 md:h-36 rounded-2xl overflow-hidden border-4 border-white bg-slate-50">
                        @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                <span class="iconify text-7xl" data-icon="fluent:person-24-filled"></span>
                            </div>
                        @endif
                    </div>

                    <!-- Name and Action -->
                    <div class="flex-1 text-center md:text-left mb-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                            {{ $user->name }}</h1>
                        <p class="mt-2 flex items-center justify-center gap-2 text-sm text-gray-500 md:justify-start">
                            <span class="iconify text-base text-red-400" data-icon="fluent:mail-24-filled"></span>
                            <span>{{ $user->email }}</span>
                        </p>
                    </div>

                    <div class="flex gap-4 w-full md:w-auto justify-center">
                        <a href="{{ route('people.profile.edit') }}"
                            class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors duration-200 hover:bg-gray-50">
                            <span class="iconify mr-2 text-base" data-icon="fluent:settings-24-filled"></span>
                            Manage Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Stats Grid -->
        <div class="max-w-5xl mx-auto px-6 mb-12">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Impact Score Card -->
                <div class="relative rounded-2xl border border-gray-100 bg-gray-50 p-5">
                    <div
                        class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600">
                        <span class="iconify text-lg" data-icon="fluent:star-24-filled"></span>
                    </div>
                    <span class="block text-xs font-medium uppercase tracking-wide text-gray-400">Impact Points</span>
                    <h3 class="mt-3 text-3xl font-bold tracking-tight text-red-600">
                        {{ $stats['volunteering_count'] * 100 }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">Top 5% Contributor</p>
                </div>

                <!-- Volunteering Card -->
                <div class="relative rounded-2xl border border-gray-100 bg-gray-50 p-5">
                    <div
                        class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-500">
                        <span class="iconify text-lg" data-icon="fluent:heart-24-filled"></span>
                    </div>
                    <span class="block text-xs font-medium uppercase tracking-wide text-gray-400">Events Joined</span>
                    <h3 class="mt-3 text-3xl font-bold tracking-tight text-red-500">{{ $stats['volunteering_count'] }}</h3>
                    <p class="mt-1 text-xs text-gray-400">Active Volunteer</p>
                </div>

                <!-- Passions Card -->
                <div class="relative rounded-2xl border border-gray-100 bg-gray-50 p-5">
                    <div
                        class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-500">
                        <span class="iconify text-lg" data-icon="fluent:sparkles-24-filled"></span>
                    </div>
                    <span class="block text-xs font-medium uppercase tracking-wide text-gray-400">Passions</span>
                    <div class="mt-3">
                        @if (!empty($user->preferred_categories) && count($user->preferred_categories) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->preferred_categories as $cat)
                                    <span
                                        class="rounded-lg border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ $cat }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm font-bold text-slate-700">No interests yet</p>
                            <p class="mt-1 text-xs text-gray-400">Update your profile to add passions</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar: About & Interests -->
                <div class="lg:col-span-4 space-y-5">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5">
                        <h4
                            class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-gray-400">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Personal Bio
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 text-slate-400">
                                    <span class="iconify text-base" data-icon="fluent:location-24-regular"></span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Location</p>
                                    <p class="text-sm font-medium text-slate-700">{{ $user->location ?: 'Global Citizen' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 text-slate-400">
                                    <span class="iconify text-base" data-icon="fluent:calendar-24-regular"></span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Joined</p>
                                    <p class="text-sm font-medium text-slate-700">{{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5">
                        <h4
                            class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-gray-400">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Passions
                        </h4>
                        @if (!empty($user->preferred_categories))
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->preferred_categories as $cat)
                                    <span
                                        class="rounded-lg border border-red-100 bg-red-50 px-3 py-1 text-xs font-medium text-red-700">{{ $cat }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 font-medium italic">No interests selected yet.</p>
                            <a href="{{ route('people.profile.edit') }}"
                                class="mt-4 inline-block text-xs font-medium text-primary transition-colors duration-200 hover:text-red-700">Update
                                Interests</a>
                        @endif
                    </div>
                </div>

                <!-- Main Section: Tabs & Content -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex gap-1 rounded-2xl bg-gray-100 p-1">
                        <button onclick="switchTab('activity')" id="tab-btn-activity"
                            class="tab-btn profile-tab profile-tab-active">
                            Activity
                        </button>
                        <button onclick="switchTab('badges')" id="tab-btn-badges" class="tab-btn profile-tab">
                            Impact
                        </button>
                        <button onclick="switchTab('following')" id="tab-btn-following" class="tab-btn profile-tab">
                            Network
                        </button>
                    </div>

                    <!-- Panel: Activity -->
                    <div id="tab-panel-activity" class="tab-panel space-y-4">
                        @if ($user->volunteeredEvents->isEmpty())
                            <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center">
                                <div
                                    class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                    <span class="iconify text-5xl" data-icon="fluent:feed-24-regular"></span>
                                </div>
                                <h4 class="mb-2 font-semibold tracking-tight text-slate-900">Your impact journey starts here
                                </h4>
                                <p class="mb-8 text-sm font-medium text-slate-500">Join your first event or support a cause
                                    to see your activity here.</p>
                                <a href="{{ route('people.volunteer.opportunities') }}"
                                    class="btn-primary px-8 py-3">Explore Opportunities</a>
                            </div>
                        @else
                            {{-- Volunteering Timeline --}}
                            @if ($user->volunteeredEvents->isNotEmpty())
                                <p
                                    class="border-b border-gray-100 pb-2 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Volunteering</p>
                            @endif
                            @foreach ($user->volunteeredEvents as $event)
                                <div class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-white p-4">
                                    <div
                                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-500">
                                        <span class="iconify text-xl" data-icon="fluent:handshake-24-filled"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-sm font-semibold text-gray-900">Joined "{{ $event->title }}"</h5>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-400">
                                            <span>{{ $event->pivot->created_at->diffForHumans() }}</span>
                                            <span
                                                class="rounded-full bg-green-50 px-2 py-0.5 font-medium text-green-700">{{ $event->pivot->status }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('people.volunteer.details', $event->id) }}"
                                        class="rounded-xl p-2 text-slate-400 transition-colors duration-200 hover:text-primary">
                                        <span class="iconify text-xl" data-icon="fluent:chevron-right-24-filled"></span>
                                    </a>
                                </div>
                            @endforeach


                        @endif
                    </div>

                    <!-- Panel: Badges -->
                    <div id="tab-panel-badges" class="tab-panel hidden space-y-6">
                        <div class="rounded-2xl border border-gray-100 bg-white p-5">
                            <h4
                                class="mb-5 border-b border-slate-100 pb-4 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Recognition & Badges</h4>
                            @if ($user->badges->count() > 0)
                                <div class="grid grid-cols-4 gap-4">
                                    @foreach ($user->badges as $badge)
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="relative flex h-14 w-14 items-center justify-center rounded-2xl border border-amber-100 bg-amber-50">
                                                <span class="iconify text-2xl text-amber-600"
                                                    data-icon="{{ $badge->icon ?? 'fluent:ribbon-24-filled' }}"></span>
                                                <div
                                                    class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-green-500 text-white">
                                                    <i class="fas fa-check text-[8px]"></i>
                                                </div>
                                            </div>
                                            <span
                                                class="mt-2 text-center text-xs font-medium uppercase tracking-wide text-gray-500">{{ $badge->name }}</span>
                                        </div>
                                    @endforeach
                                    @for ($i = 0; $i < max(0, 4 - $user->badges->count()); $i++)
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="flex h-14 w-14 items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50 text-gray-300">
                                                <i class="fas fa-lock text-lg"></i>
                                            </div>
                                            <span
                                                class="mt-2 text-center text-xs font-medium uppercase tracking-wide text-gray-500">Locked</span>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
                                        <span class="iconify text-3xl" data-icon="fluent:trophy-24-regular"></span>
                                    </div>
                                    <p class="text-xs font-medium uppercase tracking-widest text-slate-400">No badges
                                        earned yet. Keep helping!</p>
                                    <div class="mt-4 grid grid-cols-4 gap-4">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="flex h-14 w-14 items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50 text-gray-300">
                                                    <i class="fas fa-lock text-lg"></i>
                                                </div>
                                                <span
                                                    class="mt-2 text-center text-xs font-medium uppercase tracking-wide text-gray-500">Locked</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white p-5">
                            <h4
                                class="mb-5 border-b border-slate-100 pb-4 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Verified Certificates</h4>
                            @forelse($user->certificates as $certificate)
                                <div class="mb-2 flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-100 bg-white text-red-500">
                                        <span class="iconify text-base" data-icon="fluent:certificate-24-filled"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-slate-900">{{ $certificate->event->title }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-gray-400">
                                            {{ $certificate->issued_at->format('M Y') }}</p>
                                    </div>
                                    <button class="text-gray-400 transition-colors duration-200 hover:text-red-500">
                                        <span class="iconify text-lg" data-icon="fluent:arrow-download-24-filled"></span>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <p class="text-xs font-medium uppercase tracking-widest">Your verified certificates
                                        will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Panel: Following -->
                    <div id="tab-panel-following" class="tab-panel hidden space-y-4">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400">
                                    {{ $user->followedNgos->count() }} Connections</p>
                                <h4 class="mt-1 text-sm font-semibold text-slate-900">Your Network</h4>
                            </div>
                            <a href="{{ route('people.ngo.search') }}"
                                class="text-xs font-medium uppercase tracking-wide text-primary transition-colors duration-200 hover:text-red-700">Discovery
                                Mode</a>
                        </div>

                        @forelse($user->followedNgos as $ngo)
                            <a href="{{ route('common.ngo.profile', $ngo->user_id) }}"
                                class="group flex items-center gap-3 rounded-2xl border border-gray-100 bg-white p-3 transition-colors duration-200 hover:border-red-200">
                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-xl bg-red-50 text-sm font-semibold text-red-700">
                                        @if ($ngo->logo)
                                            <img src="{{ asset('storage/' . $ngo->logo) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($ngo->ngo_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h5
                                            class="truncate text-sm font-semibold text-slate-900 transition-colors duration-200 group-hover:text-primary">
                                            {{ $ngo->ngo_name }}</h5>
                                        <p class="mt-0.5 text-xs uppercase tracking-wide text-gray-400">
                                            {{ $ngo->category ?: 'NGO' }}</p>
                                    </div>
                                </div>
                                <span class="text-slate-300 transition-colors duration-200 group-hover:text-red-500">
                                    <span class="iconify text-xl" data-icon="fluent:chevron-right-24-filled"></span>
                                </span>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center">
                                <p class="mb-4 text-sm font-medium uppercase tracking-widest text-slate-400">You haven't
                                    followed any NGOs yet.</p>
                                <a href="{{ route('people.ngo.search') }}" class="btn-secondary px-6 py-2.5">Find
                                    Organizations</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach((panel) => {
                panel.classList.toggle('hidden', panel.id !== 'tab-panel-' + tabId);
            });

            document.querySelectorAll('.tab-btn').forEach((button) => {
                button.classList.remove('profile-tab-active');
            });

            document.getElementById('tab-btn-' + tabId).classList.add('profile-tab-active');
        }
    </script>
@endsection
