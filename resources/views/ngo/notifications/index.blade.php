@extends('layouts.app')
@section('content')
    @if ($notifications->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-bell text-6xl text-gray-300 mx-auto block mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Notifications</h3>
            <p class="text-gray-600">You have no notifications at this time.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($notifications as $notification)
                <div
                    class="p-4 bg-white rounded-lg shadow hover:bg-gray-50 flex items-start space-x-3 @if (!$notification->read_at) bg-red-50 @endif">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell text-gray-500"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500">{{ $notification->data['message'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        @if (!$notification->read_at)
                            <form action="{{ route('ngo.notifications.read', $notification->id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                    <i class="fas fa-check-circle inline-block mr-1"></i> Mark as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{ $notifications->links() }}
    @endif
@endsection
