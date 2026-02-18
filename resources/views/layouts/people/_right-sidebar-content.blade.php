{{-- Featured Events Section --}}
<div class="px-6 mb-8">
    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Featured Events</h3>
    <div class="space-y-4">
        <a href="#" class="block p-4 bg-slate-50 hover:bg-white hover:shadow-xl rounded-2xl transition-all duration-300 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-200 transition-transform group-hover:rotate-12">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Community</p>
            </div>
            <h4 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors leading-snug">Community Empowerment Drive</h4>
            <p class="text-[10px] text-slate-500 mt-1 font-medium italic">by Nepal Foundation</p>
        </a>

        <a href="#" class="block p-4 bg-slate-50 hover:bg-white hover:shadow-xl rounded-2xl transition-all duration-300 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-200 transition-transform group-hover:rotate-12">
                    <i class="fas fa-ribbon text-white text-sm"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Health Awareness</p>
            </div>
            <h4 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors leading-snug">Cancer Awareness Event</h4>
            <p class="text-[10px] text-slate-500 mt-1 font-medium italic">Health Nepal Initiative</p>
        </a>
    </div>
</div>

{{-- Quick Links --}}
<div class="px-6 mb-8">
    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 gap-2">
        <a href="{{ route('people.ngo.search') }}" class="flex flex-col items-center justify-center p-3 bg-slate-50 hover:bg-white hover:shadow-md rounded-2xl transition-all group">
            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mb-2 group-hover:bg-amber-100">
                <i class="fas fa-search text-amber-500 text-xs"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-600">Search</span>
        </a>
        <a href="{{ route('people.volunteer.opportunities') }}" class="flex flex-col items-center justify-center p-3 bg-slate-50 hover:bg-white hover:shadow-md rounded-2xl transition-all group">
            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center mb-2 group-hover:bg-red-100">
                <i class="fas fa-hands-helping text-red-500 text-xs"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-600">Join</span>
        </a>
    </div>
</div>

{{-- Footer --}}
<div class="px-6 mt-auto pb-8">
    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">© {{ date('Y') }} NGO Connect</p>
        <div class="flex gap-3">
            <a href="#" class="text-slate-300 hover:text-primary transition-colors"><i class="fab fa-twitter text-xs"></i></a>
            <a href="#" class="text-slate-300 hover:text-primary transition-colors"><i class="fab fa-facebook text-xs"></i></a>
        </div>
    </div>
</div>
