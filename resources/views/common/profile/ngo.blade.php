@extends('layouts.app')

@section('content')

    <!-- Header with Background -->
    <div class="relative bg-white border-b border-gray-200">
        <!-- Banner/Cover -->
        <div class="h-48 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 w-full absolute top-0 left-0 z-0">
            <div class="absolute inset-0 bg-black/20 pattern-dots"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 pt-24 pb-8 relative z-10">
            <div class="flex items-end gap-6">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 rounded-full ring-4 ring-white shadow-lg bg-white overflow-hidden flex items-center justify-center">
                        @if($ngo->ngo && $ngo->ngo->logo)
                            <img src="{{ asset('storage/' . $ngo->ngo->logo) }}" alt="{{ $ngo->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <span class="text-4xl font-bold text-white">{{ substr($ngo->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Section -->
                <div class="flex-1 pb-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $ngo->name }}</h1>
                            <div class="flex items-center gap-3 text-sm mt-2">
                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 font-medium border border-red-100 flex items-center gap-1">
                                    <i class="fas fa-tag text-xs"></i> {{ $ngo->ngo->category }}
                                </span>
                                @if($ngo->ngo->subcategory) 
                                    <span class="px-3 py-1 rounded-full bg-red-50/60 text-red-500 font-medium border border-red-100">
                                        {{ $ngo->ngo->subcategory }}
                                    </span>
                                @endif
                                <span class="text-gray-500 flex items-center gap-1 ml-1" id="profile-followers-count-wrapper">
                                    <i class="fas fa-users text-gray-400"></i>
                                    <span id="profile-followers-count" class="font-bold text-gray-900">{{ $followersCount }}</span> followers
                                </span>
                            </div>
                        </div>

                        {{-- Interaction Buttons --}}
                        @if(auth()->check() && auth()->id() != $ngo->id)
                            <div class="flex items-center gap-3">
                                <a href="{{ route('common.circles.index', $ngo->id) }}" 
                                   class="px-5 py-2.5 bg-gray-50 text-gray-600 rounded-full font-semibold text-sm hover:bg-red-50 hover:text-red-500 border border-transparent hover:border-red-100 transition-all shadow-sm flex items-center gap-2">
                                    <span class="iconify" data-icon="fluent:people-community-20-filled"></span>
                                    <span>Circle</span>
                                </a>
                                
                                <a href="{{ route('common.messages.show', $ngo->id) }}" 
                                   class="px-5 py-2.5 bg-white text-gray-600 border border-gray-200 rounded-full font-semibold text-sm hover:bg-gray-50 transition-all shadow-sm flex items-center gap-2">
                                    <span class="iconify" data-icon="fluent:mail-20-filled"></span>
                                    <span>Message</span>
                                </a>

                                <button id="profile-follow-btn"
                                    data-ngo-id="{{ $ngo->id }}"
                                    class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 transform active:scale-95 shadow-md
                                        {{ $isFollowing
                                            ? 'bg-white text-gray-700 hover:text-red-600 border border-gray-200 hover:border-red-200'
                                            : 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:shadow-red-200 hover:from-red-600 hover:to-red-700' }}">
                                    <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }} mr-2"></i>
                                    <span>{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                </button>
                            </div>
                        @endif

                        @if(auth()->check() && auth()->id() == $ngo->id)
                            <div class="flex items-center gap-3">
                                <a href="{{ route('ngo.profile.edit') }}" 
                                   class="px-5 py-2.5 bg-white text-gray-600 border border-gray-200 rounded-full font-semibold text-sm hover:bg-gray-50 hover:text-red-500 border-transparent hover:border-red-100 transition-all shadow-sm flex items-center gap-2">
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Sidebar - Organization Details & Charts -->
            <div class="lg:col-span-1">
                <!-- Basic Details Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Organization Details</h2>

                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <p class="text-sm font-semibold text-red-500">Description</p>
                            <p class="text-sm text-gray-900">
                                {{ $ngo->ngo->description ?? 'No description provided.' }}
                            </p>
                        </div>

                        <!-- Mission -->
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-red-500">Mission</p>
                            <p class="text-sm text-gray-900">
                                {{ $ngo->ngo->mission ?? 'Misson not provided.' }}
                            </p>
                        </div>

                        <!-- Contact Information -->
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-red-500 mb-3">Contact Information</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-600 font-medium">Phone:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $ngo->ngo->phone ?? 'NA.' }}
                                        </p>
                                </div>

                                <div class="flex items-start gap-2">
                                    <span class="text-gray-600 font-medium">Address:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $ngo->ngo->address ?? 'NA' }}
                                        </p>
                                </div>

                            </div>
                        </div>

                        <!-- Registration Details -->
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-red-500 mb-3">Registration Details</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-600 font-medium">Reg. Number:</span>
                                    <span class="text-gray-600">{{ $ngo->ngo->registration_number}} </span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-600 font-medium">District:</span>
                                    <span class="text-gray-600">{{ $ngo->ngo->registration_district }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="space-y-4">
                    <!-- Total Events -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium group-hover:text-red-600 transition-colors">Total Events</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2" id="total-events">{{ $eventsCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Donations -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium group-hover:text-green-600 transition-colors">Total Donations</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2" id="total-donations">
                                    ${{ number_format($stats['total_donations'] ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                <svg class="w-6 h-6 text-green-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Followers -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium group-hover:text-red-600 transition-colors">Total Followers</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2" id="total-followers">
                                    {{ $followersCount ?? 0 }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                {{-- <div class="bg-white rounded-lg shadow-md p-6 mt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Activity Overview</h3>
                    <canvas id="activityChart" class="w-full"></canvas>
                </div> --}}
            </div>

            <!-- Posts Feed -->
            <div class="lg:col-span-2">
                <div>
                    <div class="bg-white rounded-lg shadow-md border-b border-gray-200 px-6 py-4 mb-5">
                        <h2 class="text-xl font-bold text-gray-900">Posts</h2>
                    </div>

                    <!-- Posts Container -->
                    <div id="posts-container" class="space-y-6">
                        @include('common.feed.partials.post', ['post' => $posts])
                    </div>
                    {{-- <div id="posts-container" class="divide-y divide-gray-200">
                        @include('common.feed.partials.post',['post' => $posts])
                    </div> --}}

                    <!-- Load More Button -->
                    {{-- <div class="border-t border-gray-200 px-6 py-4 text-center" id="load-more-container"
                        style="display: none;">
                        <button id="load-more-btn"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Load More Posts
                        </button>
                    </div> --}}

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
            url: '{{ route("common.ngo.follow") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ngo_id: ngoId
            },
            success: function(res) {
                var countEl = $('#profile-followers-count');
                var currentCount = parseInt(countEl.text()) || 0;

                if (res.following) {
                    btn.removeClass('bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200')
                       .addClass('bg-red-500 text-white hover:bg-red-600 shadow-sm');
                    btn.find('i').removeClass('fa-user-plus').addClass('fa-user-check');
                    btn.find('span').text('Following');
                    countEl.text(currentCount + 1);
                } else {
                    btn.removeClass('bg-red-500 text-white hover:bg-red-600 shadow-sm')
                       .addClass('bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200');
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
});
</script>
@endpush

@include('common.feed.partials.modal')