@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumbs & Nav -->
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('common.circles.index', $thread->ngo_id) }}" class="inline-flex items-center text-gray-400 hover:text-red-500 font-bold text-xs uppercase tracking-widest transition-colors">
                <span class="iconify mr-2" data-icon="fluent:arrow-left-20-filled"></span>
                Back to {{ $thread->ngo->name }} Circle
            </a>
        </div>

        <!-- Main Thread Post -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
            <div class="p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 font-bold border border-gray-100">
                        @if($thread->user->profile_photo)
                            <img src="{{ asset('storage/' . $thread->user->profile_photo) }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            {{ substr($thread->user->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <h4 class="text-base font-black text-gray-900 leading-none mb-1">{{ $thread->user->name }}</h4>
                        <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <h1 class="text-3xl font-black text-gray-900 mb-6 leading-tight">{{ $thread->title }}</h1>
                <div class="prose prose-red max-w-none text-gray-600 font-medium leading-relaxed">
                    {!! nl2br(e($thread->content)) !!}
                </div>
            </div>
            
            <div class="bg-gray-50/50 px-10 py-4 border-t border-gray-50 flex items-center justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="iconify text-red-500" data-icon="fluent:chat-20-filled"></span>
                    {{ $thread->replies->count() }} Replies
                </span>
            </div>
        </div>

        <!-- Replies Section -->
        <div class="space-y-6 mb-12">
            @foreach($thread->replies as $reply)
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm ml-8 md:ml-12 relative">
                    <!-- Connector line logic would go here in CSS -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 font-bold border border-gray-100">
                            @if($reply->user->profile_photo)
                                <img src="{{ asset('storage/' . $reply->user->profile_photo) }}" class="w-full h-full object-cover rounded-xl">
                            @else
                                {{ substr($reply->user->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-gray-900 leading-none mb-1">{{ $reply->user->name }}</h5>
                            <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 font-medium leading-relaxed">
                        {!! nl2br(e($reply->content)) !!}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Input -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <h3 class="text-lg font-black text-gray-900 mb-6">Join the conversation</h3>
            <form action="{{ route('common.circles.storeReply', $thread->id) }}" method="POST">
                @csrf
                <textarea name="content" required rows="4" placeholder="Write your reply..." 
                          class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-3xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-medium text-gray-800 placeholder-gray-300"></textarea>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-100 hover:bg-red-700 transition-all transform active:scale-95">
                        Post Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
