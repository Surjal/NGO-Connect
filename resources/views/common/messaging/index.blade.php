@extends('layouts.app')

@section('content')
<div class="h-full bg-white flex overflow-hidden">
    <!-- Messenger Sidebar (Conversation List) -->
    <div class="w-full md:w-[360px] lg:w-[400px] border-r border-gray-100 flex flex-col bg-white shrink-0">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Chats</h1>
            <div class="flex gap-2">
                <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-all">
                    <span class="iconify" data-icon="fluent:settings-24-regular"></span>
                </button>
            </div>
        </div>
        
        <!-- Search bar -->
        <div class="p-4">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <span class="iconify" data-icon="fluent:search-24-regular"></span>
                </span>
                <input type="text" placeholder="Search Messenger" 
                       class="w-full bg-gray-100 border-none rounded-2xl py-2.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-red-500/20 transition-all placeholder-gray-500">
            </div>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto pb-20 custom-scrollbar">
            @if(count($conversations) > 0)
                @foreach($conversations as $convUserId => $convMessages)
                    @php
                        $convUser = \App\Models\User::find($convUserId);
                        $lastMsg = $convMessages->first();
                        $unread = $convMessages->where('receiver_id', auth()->id())->where('read_at', null)->count();
                    @endphp
                    <a href="{{ route('common.messages.show', $convUserId) }}" 
                       class="flex items-center gap-3 px-4 py-3 mx-2 rounded-2xl transition-all hover:bg-gray-50">
                        <div class="relative shrink-0">
                            <div class="w-14 h-14 rounded-full border border-gray-100 overflow-hidden bg-gray-50">
                                @if($convUser->profile_photo)
                                    <img src="{{ asset('storage/' . $convUser->profile_photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-500 to-red-600 text-white font-bold text-lg">
                                        {{ substr($convUser->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            @if($convUser->isVerified())
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center border-2 border-white">
                                    <span class="iconify text-red-500 text-xs" data-icon="fluent:checkmark-badge-24-filled"></span>
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h4 class="text-sm font-bold text-gray-900 truncate {{ $unread > 0 ? 'font-black' : '' }}">{{ $convUser->name }}</h4>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $lastMsg->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            <p class="text-[13px] {{ $unread > 0 ? 'text-gray-900 font-bold' : 'text-gray-500' }} truncate">
                                {{ $lastMsg->sender_id == auth()->id() ? 'You: ' : '' }}{{ $lastMsg->content }}
                            </p>
                        </div>
                        @if($unread > 0)
                            <div class="w-2.5 h-2.5 bg-red-600 rounded-full shrink-0"></div>
                        @endif
                    </a>
                @endforeach
            @else
                <div class="p-8 text-center mt-10">
                    <p class="text-sm text-gray-400 font-medium">No conversations yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Chat Window (Empty State) -->
    <div class="hidden md:flex flex-1 flex-col items-center justify-center p-8 bg-gray-50/30">
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl shadow-gray-100/50 mb-6 group hover:scale-110 transition-transform cursor-pointer">
            <span class="iconify text-4xl text-red-500 group-hover:rotate-12 transition-transform" data-icon="fluent:chat-bubbles-help-24-filled"></span>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Select a Conversation</h3>
        <p class="text-sm text-gray-500 font-medium text-center max-w-xs">Connecting with your community starts here. Tap a chat to keep the conversation going.</p>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
</style>
@endsection
