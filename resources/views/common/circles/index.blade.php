@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- NGO Header -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm mb-8 flex items-center justify-between overflow-hidden relative">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-50 rounded-full blur-3xl"></div>
            <div class="relative flex items-center gap-6">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white text-3xl font-bold shadow-xl shadow-red-100 ring-8 ring-red-50">
                    @if($ngo->profile_photo)
                        <img src="{{ asset('storage/' . $ngo->profile_photo) }}" class="w-full h-full object-cover rounded-3xl">
                    @else
                        {{ substr($ngo->name, 0, 1) }}
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 mb-1">{{ $ngo->name }}</h1>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="iconify text-red-500" data-icon="fluent:people-community-20-filled"></span>
                        Community Circle
                    </p>
                </div>
            </div>
            
            <button onclick="document.getElementById('newThreadModal').classList.remove('hidden')" 
                    class="relative px-6 py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-100 hover:bg-red-700 transition-all flex items-center gap-2">
                <span class="iconify text-lg" data-icon="fluent:add-circle-20-filled"></span>
                Start Discussion
            </button>
        </div>

        <!-- Threads List -->
        <div class="space-y-4">
            @if($threads->count() > 0)
                @foreach($threads as $thread)
                    <a href="{{ route('common.circles.show', $thread->id) }}" 
                       class="block bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded-full mb-3 inline-block">
                                    Discussion
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors mb-2">{{ $thread->title }}</h3>
                                <p class="text-sm text-gray-500 font-medium line-clamp-2 leading-relaxed">
                                    {{ $thread->content }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <div class="flex -space-x-2">
                                    @foreach($thread->replies->take(3) as $reply)
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center overflow-hidden">
                                            @if($reply->user->profile_photo)
                                                <img src="{{ asset('storage/' . $reply->user->profile_photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-[8px] font-bold text-gray-400">{{ substr($reply->user->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($thread->replies->count() > 3)
                                        <div class="w-7 h-7 rounded-full bg-gray-50 border-2 border-white flex items-center justify-center text-[8px] font-black text-gray-400">
                                            +{{ $thread->replies->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                    {{ $thread->replies->count() }} {{ Str::plural('Reply', $thread->replies->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-400">
                                    @if($thread->user->profile_photo)
                                        <img src="{{ asset('storage/' . $thread->user->profile_photo) }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        {{ substr($thread->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold text-gray-400">By {{ $thread->user->name }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-300">{{ $thread->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="bg-white rounded-[2.5rem] p-16 text-center border border-gray-100 shadow-sm">
                    <span class="iconify text-4xl text-gray-200 mx-auto mb-4" data-icon="fluent:chat-bubbles-question-24-regular"></span>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Circle is quiet...</h3>
                    <p class="text-sm text-gray-400 font-medium mb-8">Start the first discussion to engage with this NGO's community.</p>
                    <button onclick="document.getElementById('newThreadModal').classList.remove('hidden')" 
                            class="px-8 py-4 bg-gray-50 text-gray-400 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-red-50 hover:text-red-500 transition-all">
                        Define Topic
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- New Thread Modal -->
<div id="newThreadModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('newThreadModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form action="{{ route('common.circles.storeThread', $ngo->id) }}" method="POST" class="p-10">
                @csrf
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Start a Discussion</h3>
                    <p class="text-sm text-gray-500 font-medium">Ask a question or share an idea with the community.</p>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Thread Title</label>
                        <input type="text" name="title" required placeholder="What would you like to discuss?" 
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-bold text-gray-800 placeholder-gray-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Content</label>
                        <textarea name="content" rows="4" required placeholder="Provide some context for your discussion..." 
                                  class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-medium text-gray-800 placeholder-gray-300"></textarea>
                    </div>
                </div>
                <div class="mt-10 flex gap-4">
                    <button type="button" onclick="document.getElementById('newThreadModal').classList.add('hidden')"
                            class="flex-1 px-8 py-4 bg-gray-50 text-gray-400 font-bold rounded-2xl hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" 
                            class="flex-1 px-8 py-4 bg-red-600 text-white font-bold rounded-2xl shadow-xl shadow-red-100 hover:bg-red-700 transition-all transform active:scale-95">Post Thread</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
