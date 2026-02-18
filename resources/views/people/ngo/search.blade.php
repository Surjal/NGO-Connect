@extends('layouts.app')

@section('content')
    <div class="space-y-12 pb-20">
        {{-- Hero / Search Header --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-red-600 via-primary to-red-400 p-12 text-center text-white shadow-2xl">
            <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 50 0 80 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4">Discover Impact</h1>
                <p class="text-lg font-medium text-white/80">
                    Find and follow organizations that align with your values and are making a real difference.
                </p>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <div class="glass-panel p-8 -mt-20 relative z-20 shadow-2xl border-white/20">
            <form method="GET" action="{{ route('people.ngo.search') }}" id="searchForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- NGO Name --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Organization Name</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="name" id="name" value="{{ request('name') }}"
                                placeholder="e.g. Hope Foundation"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary text-sm font-medium transition-all outline-none">
                        </div>
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Location</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-500 transition-colors">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="location" id="location" value="{{ request('location') }}"
                                placeholder="e.g. Kathmandu"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 text-sm font-medium transition-all outline-none">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Impact Category</label>
                        <div class="relative">
                            <select name="category" id="category"
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary text-sm font-bold appearance-none cursor-pointer outline-none">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Subcategory & Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between pt-4 border-t border-slate-100">
                    <div class="w-full sm:w-auto">
                        @if (request('category'))
                            <div class="flex items-center gap-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Subcategory:</label>
                                <select name="subcategory" id="subcategory"
                                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">All Subcategories</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory }}" {{ request('subcategory') == $subcategory ? 'selected' : '' }}>
                                            {{ ucfirst($subcategory) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <p class="text-[10px] font-bold text-slate-400 italic">Select a category to refine your search</p>
                        @endif
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-none btn-primary px-8 py-3 text-[10px] font-black uppercase tracking-widest">
                            Search Now
                        </button>
                        <a href="{{ route('people.ngo.search') }}" class="flex-1 sm:flex-none btn-secondary px-8 py-3 text-[10px] font-black uppercase tracking-widest">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Results Section --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">
                    Discovery Results
                    <span class="ml-2 px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400">
                        {{ $ngos->total() }} Total
                    </span>
                </h2>
            </div>

            @if ($ngos->isEmpty())
                <div class="glass-panel p-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <span class="iconify text-6xl" data-icon="fluent:search-off-24-regular"></span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">No results matching your query</h3>
                    <p class="text-slate-500 font-medium max-w-sm mx-auto">Try broadening your search or exploring different categories.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($ngos as $ngo)
                        <div class="glass-panel p-6 group hover:shadow-2xl hover:border-primary/20 transition-all duration-500 flex flex-col sm:flex-row gap-6">
                            {{-- Logo --}}
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl overflow-hidden bg-white border border-slate-100 p-1 flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                @if ($ngo->logo)
                                    <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->ngo_name }}"
                                        class="w-full h-full object-cover rounded-[1.25rem]">
                                @else
                                    <div class="w-full h-full bg-slate-50 rounded-[1.25rem] flex items-center justify-center text-red-300">
                                        <i class="fas fa-building text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <h3 class="text-lg font-black text-slate-900 group-hover:text-primary transition-colors truncate">{{ $ngo->ngo_name }}</h3>
                                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ $ngo->category }}</span>
                                    </div>
                                    @if ($ngo->location)
                                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">
                                            <i class="fas fa-map-marker-alt text-red-400"></i>
                                            {{ $ngo->location }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500 font-medium line-clamp-2 leading-relaxed mb-6">
                                    {{ $ngo->description }}
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <a href="{{ route('common.ngo.profile', $ngo->user_id) }}"
                                        class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline flex items-center gap-2">
                                        View Mission
                                        <i class="fas fa-arrow-right text-[8px]"></i>
                                    </a>
                                    <button class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-white border border-transparent hover:border-red-100 transition-all">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $ngos->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let searchTimeout;

            // Auto-submit on input (debounced)
            $('#name, #location').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => $('#searchForm').submit(), 800);
            });

            // Submit on select change
            $('#category, #subcategory').on('change', function() {
                $('#searchForm').submit();
            });
        });
    </script>
@endpush
