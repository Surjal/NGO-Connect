@extends('layouts.app')
@section('content')

    <div class="max-w-7xl mx-auto px-4 px-8 mb-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Volunteer Management</h1>
                <p class="text-gray-500 mt-2">Review and verify community members who applied for your events.</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('ngo.volunteers') }}" method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-end mb-8">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Search Event</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Event title..." 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm">
                </div>
            </div>

            <div class="w-40">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="block w-full py-2 px-3 border border-gray-200 bg-white rounded-lg focus:ring-red-500 focus:border-red-500 text-sm">
                    <option value="">All Applicants</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Only</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Already Verified</option>
                </select>
            </div>

            <div class="flex gap-2 text-sm font-semibold">
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('ngo.volunteers') }}" class="px-5 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if ($events->isEmpty())
            <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                <span class="iconify text-6xl text-gray-300 mx-auto block mb-6" data-icon="fluent:people-team-20-filled" data-width="64" data-height="64"></span>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
                <p class="text-gray-500">When people apply for your events, they will appear here for verification.</p>
            </div>
        @else
            @foreach ($events as $event)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center text-sm font-bold">
                        <h2 class="text-lg font-bold text-gray-900">{{ $event->title }}</h2>
                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                            {{ $event->volunteers->count() }} {{ Str::plural('Applicant', $event->volunteers->count()) }}
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            @forelse ($event->volunteers as $volunteer)
                                <div class="flex items-center space-x-4 p-4 border border-gray-50 hover:border-red-100 rounded-xl transition-all hover:shadow-sm">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0 ring-2 ring-white">
                                        @if ($volunteer->profile_photo)
                                            <img src="{{ asset('storage/' . $volunteer->profile_photo) }}"
                                                alt="{{ $volunteer->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                                <i class="fas fa-user text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">{{ $volunteer->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $volunteer->email }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if ($volunteer->pivot->status === 'accepted')
                                            <div class="flex items-center text-green-600 bg-green-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-green-100">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Verified
                                            </div>
                                        @else
                                            <div class="flex items-center text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-orange-100 mr-2">
                                                Pending
                                            </div>
                                            <form action="{{ route('ngo.volunteers.verify', [$event->id, $volunteer->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-5 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
                                                    Accept Application
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <i class="fas fa-user-clock text-4xl mb-3 opacity-50"></i>
                                    <p class="text-sm font-medium">No applicants found for this event yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
