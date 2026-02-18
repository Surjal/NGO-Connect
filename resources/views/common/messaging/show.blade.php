@extends('layouts.app')

@section('content')
<div class="h-full bg-white flex overflow-hidden min-h-0">
    <!-- Messenger Sidebar (Conversation List) -->
    <div class="w-full md:w-[360px] lg:w-[400px] border-r border-gray-100 flex flex-col bg-white shrink-0 min-h-0 {{ isset($otherUser) ? 'hidden md:flex' : 'flex' }}">
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
            @foreach($conversations as $convUserId => $convMessages)
                @php
                    $convUser = \App\Models\User::find($convUserId);
                    $lastMsg = $convMessages->first();
                    $unread = $convMessages->where('receiver_id', auth()->id())->where('read_at', null)->count();
                    $isActive = isset($otherUser) && $otherUser->id == $convUserId;
                @endphp
                <a href="{{ route('common.messages.show', $convUserId) }}" 
                   class="flex items-center gap-3 px-4 py-3 mx-2 rounded-2xl transition-all hover:bg-gray-50 {{ $isActive ? 'bg-red-50/50' : '' }}">
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
        </div>
    </div>

    <!-- Main Chat Window -->
    <div class="flex-1 flex flex-col bg-white overflow-hidden relative min-h-0 {{ isset($otherUser) ? 'flex' : 'hidden md:flex' }}">
        @if(isset($otherUser))
            <!-- Chat Header -->
            <div class="h-[72px] px-4 md:px-8 border-b border-gray-100 flex items-center justify-between bg-white/80 backdrop-blur-md z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('common.messages.index') }}" class="md:hidden w-8 h-8 flex items-center justify-center text-gray-400">
                        <span class="iconify text-xl" data-icon="fluent:chevron-left-24-filled"></span>
                    </a>
                    <div class="w-10 h-10 rounded-full border border-gray-100 overflow-hidden bg-gray-50">
                        @if($otherUser->profile_photo)
                            <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 font-bold">
                                {{ substr($otherUser->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-gray-900 leading-tight">{{ $otherUser->name }}</h2>
                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active Now
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="w-9 h-9 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-full transition-all">
                        <span class="iconify text-xl" data-icon="fluent:call-24-filled"></span>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-full transition-all">
                        <span class="iconify text-xl" data-icon="fluent:video-24-filled"></span>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-full transition-all">
                        <span class="iconify text-xl" data-icon="fluent:info-24-filled"></span>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto px-6 py-8 space-y-4 custom-scrollbar bg-gray-50/10" id="chat-messages-container">
                @php $lastDate = null; @endphp
                @foreach($messages as $message)
                    @php 
                        $currDate = $message->created_at->format('M d, Y'); 
                    @endphp
                    @if($currDate !== $lastDate)
                        <div class="flex justify-center my-6">
                            <span class="px-4 py-1 rounded-full bg-white border border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest shadow-sm">
                                {{ $currDate }}
                            </span>
                        </div>
                        @php $lastDate = $currDate; @endphp
                    @endif

                    <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} message-item" data-id="{{ $message->id }}">
                        <div class="max-w-[75%] md:max-w-[60%] flex flex-col {{ $message->sender_id == auth()->id() ? 'items-end' : 'items-start' }}">
                            <div class="px-4 py-2.5 rounded-3xl text-[15px] font-medium leading-relaxed group relative
                                {{ $message->sender_id == auth()->id() 
                                    ? 'bg-red-600 text-white rounded-tr-sm shadow-md' 
                                    : 'bg-white text-gray-700 rounded-tl-sm border border-gray-100 shadow-sm' }}">
                                {{ $message->content }}
                                
                                <span class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[9px] px-2 py-0.5 rounded-lg whitespace-nowrap {{ $message->sender_id == auth()->id() ? 'right-0' : 'left-0' }}">
                                    {{ $message->created_at->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Chat Input -->
            <div class="px-6 py-4 bg-white border-t border-gray-100 z-10 shrink-0">
                <form id="chat-form" class="flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                    
                    <div class="flex items-center gap-2 text-red-600 shrink-0">
                        <button type="button" class="w-9 h-9 flex items-center justify-center hover:bg-red-50 rounded-full transition-all">
                            <span class="iconify text-xl" data-icon="fluent:add-circle-24-filled"></span>
                        </button>
                        <button type="button" class="w-9 h-9 flex items-center justify-center hover:bg-red-50 rounded-full transition-all">
                            <span class="iconify text-xl" data-icon="fluent:image-24-filled"></span>
                        </button>
                        <button type="button" class="w-9 h-9 flex items-center justify-center hover:bg-red-50 rounded-full transition-all">
                            <span class="iconify text-xl" data-icon="fluent:sticker-24-filled"></span>
                        </button>
                    </div>

                    <div class="flex-1 relative">
                        <textarea name="content" id="message-input" required rows="1" placeholder="Aa" 
                                  class="w-full bg-gray-100 border-none rounded-full py-2.5 px-5 text-[15px] focus:ring-0 resize-none font-medium text-gray-800 placeholder-gray-500 overflow-hidden leading-tight flex items-center"></textarea>
                    </div>

                    <button type="submit" id="send-btn" class="w-9 h-9 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-red-100 hover:bg-red-700 transition-all transform active:scale-90 shrink-0">
                        <span class="iconify text-lg" data-icon="fluent:send-24-filled"></span>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 bg-gray-50/30">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl shadow-gray-100/50 mb-6 group hover:scale-110 transition-transform cursor-pointer">
                    <span class="iconify text-4xl text-red-500 group-hover:rotate-12 transition-transform" data-icon="fluent:chat-bubbles-help-24-filled"></span>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Select a Conversation</h3>
                <p class="text-sm text-gray-500 font-medium text-center max-w-xs">Connecting with your community starts here. Tap a chat to keep the conversation going.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
    
    #message-input { min-height: 40px; max-height: 120px; }
</style>

@if(isset($otherUser))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chat-messages-container');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const otherUserId = "{{ $otherUser->id }}";
    
    // Auto-scroll to bottom
    const scrollToBottom = () => {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };
    scrollToBottom();

    // Auto-expand textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle Enter key to send
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Handle form submission via AJAX
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (!content) return;

        const formData = new FormData(this);
        
        // Optimistic UI update
        appendMessage({
            content: content,
            sender_id: "{{ auth()->id() }}",
            created_at_formatted: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });
        
        messageInput.value = '';
        messageInput.style.height = 'auto';
        scrollToBottom();

        fetch("{{ route('common.messages.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to send message');
            }
        });
    });

    // Function to append message to UI
    function appendMessage(msg) {
        const isMe = msg.sender_id == "{{ auth()->id() }}";
        const div = document.createElement('div');
        div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} message-item animate-in slide-in-from-bottom-2 duration-300`;
        div.dataset.id = msg.id || '';
        
        div.innerHTML = `
            <div class="max-w-[75%] md:max-w-[60%] flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                <div class="px-4 py-2.5 rounded-3xl text-[15px] font-medium leading-relaxed group relative
                    ${isMe 
                        ? 'bg-red-600 text-white rounded-tr-sm shadow-md' 
                        : 'bg-white text-gray-700 rounded-tl-sm border border-gray-100 shadow-sm'}">
                    ${msg.content}
                    <span class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[9px] px-2 py-0.5 rounded-lg whitespace-nowrap ${isMe ? 'right-0' : 'left-0'}">
                        ${msg.created_at_formatted || 'Just now'}
                    </span>
                </div>
            </div>
        `;
        chatContainer.appendChild(div);
    }

    // Polling for new messages
    let lastId = {{ $messages->last() ? $messages->last()->id : 0 }};
    
    const pollMessages = () => {
        fetch(`{{ url('/messages/api/get-messages') }}/${otherUserId}?last_id=${lastId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    // Update formatted time (optional via JS or passed from backend)
                    msg.created_at_formatted = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    appendMessage(msg);
                    lastId = Math.max(lastId, msg.id);
                });
                scrollToBottom();
            }
        })
        .finally(() => {
            setTimeout(pollMessages, 3000); // Poll every 3 seconds
        });
    };

    setTimeout(pollMessages, 3000);
});
</script>
@endif
@endsection
