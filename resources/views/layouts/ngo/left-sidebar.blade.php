{{-- Desktop Sidebar --}}
<div class="hidden {{ request()->routeIs('common.messages.*') ? '' : 'lg:block' }} w-80 h-screen overflow-y-auto scrollbar-hide glass-panel fixed left-0 border-r border-white/20 z-30 pt-4">
    @include('layouts.ngo._sidebar-content')
</div>

{{-- Mobile Slide-out Sidebar --}}
<div id="ngo-sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden hidden" onclick="closeNgoSidebar()"></div>
<div id="ngo-sidebar-mobile" class="fixed left-0 top-0 w-80 max-w-[85vw] h-screen overflow-y-auto scrollbar-hide bg-white/95 backdrop-blur-xl shadow-2xl z-50 lg:hidden transform -translate-x-full transition-transform duration-300 ease-out pt-4">
    {{-- Close Button --}}
    <div class="flex justify-end px-4 mb-2">
        <button onclick="closeNgoSidebar()" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    @include('layouts.ngo._sidebar-content')
</div>

{{-- Mobile Bottom Navigation Bar --}}
<div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-slate-200/60 z-50 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
    <div class="flex items-center justify-around px-2 py-1.5 max-w-lg mx-auto">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-1.5 px-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'text-red-500' : 'text-slate-400' }}">
            <i class="fas fa-chart-pie text-lg mb-0.5"></i>
            <span class="text-[9px] font-bold">Dashboard</span>
        </a>
        <a href="{{ route('ngo.events') }}" class="flex flex-col items-center justify-center py-1.5 px-3 rounded-xl transition-all {{ request()->routeIs('ngo.events*') ? 'text-red-500' : 'text-slate-400' }}">
            <i class="fas fa-calendar-alt text-lg mb-0.5"></i>
            <span class="text-[9px] font-bold">Events</span>
        </a>
        <a href="{{ route('ngo.volunteers') }}" class="flex flex-col items-center justify-center py-1.5 px-3 rounded-xl transition-all {{ request()->routeIs('ngo.volunteers*') ? 'text-red-500' : 'text-slate-400' }}">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg shadow-red-200 -mt-5 mb-0.5">
                <i class="fas fa-hands-helping text-white text-sm"></i>
            </div>
            <span class="text-[9px] font-bold">Volunteers</span>
        </a>
        <a href="{{ route('common.messages.index') }}" class="flex flex-col items-center justify-center py-1.5 px-3 rounded-xl transition-all relative {{ request()->routeIs('common.messages.*') ? 'text-red-500' : 'text-slate-400' }}">
            <i class="fas fa-comment-dots text-lg mb-0.5"></i>
            @php
                $unreadMsgCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
            @endphp
            @if($unreadMsgCount > 0)
                <span class="absolute top-0.5 right-2 w-4 h-4 bg-red-500 text-white text-[8px] font-bold rounded-full flex items-center justify-center">{{ $unreadMsgCount > 9 ? '9+' : $unreadMsgCount }}</span>
            @endif
            <span class="text-[9px] font-bold">Messages</span>
        </a>
        <button onclick="openNgoSidebar()" class="flex flex-col items-center justify-center py-1.5 px-3 rounded-xl transition-all text-slate-400">
            <i class="fas fa-bars text-lg mb-0.5"></i>
            <span class="text-[9px] font-bold">Menu</span>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openNgoSidebar() {
    document.getElementById('ngo-sidebar-overlay').classList.remove('hidden');
    document.getElementById('ngo-sidebar-mobile').classList.remove('-translate-x-full');
    document.body.classList.add('overflow-hidden');
}
function closeNgoSidebar() {
    document.getElementById('ngo-sidebar-overlay').classList.add('hidden');
    document.getElementById('ngo-sidebar-mobile').classList.add('-translate-x-full');
    document.body.classList.remove('overflow-hidden');
}
</script>
@endpush