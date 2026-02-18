@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>
    <div class="bg-white p-6 rounded-lg shadow">
        @if ($notifications->isEmpty())
            <p class="text-gray-600">No notifications available.</p>
        @else
            <ul class="space-y-4">
                @foreach ($notifications as $notification)
                    <li
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg {{ $notification->read_at ? 'opacity-75' : 'bg-red-50' }}">
                        <div class="flex items-center space-x-3">
                            <iconify-icon icon="mdi:bell" class="text-red-500 text-xl"></iconify-icon>
                            <div>
                                <p class="text-gray-900">{{ $notification->data['message'] }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if (!$notification->read_at)
                            <form action="{{ route('people.notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:underline text-sm">Mark as
                                    Read</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            {{ $notifications->links() }}
        @endif
    </div>
@endsection
