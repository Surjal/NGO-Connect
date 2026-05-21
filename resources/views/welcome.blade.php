@extends('layouts.app')

@section('content')
<div class="space-y-32 pb-32 overflow-hidden">
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center justify-center pt-20">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-primary to-secondary opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-8 animate-bounce">
                <span class="flex h-2 w-2 rounded-full bg-primary"></span>
                <span class="text-xs font-bold text-primary uppercase tracking-widest">Bridging Hearts, Transforming Lives</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black text-slate-900 mb-8 tracking-tighter leading-tight drop-shadow-sm">
                Empower Your <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Purpose.</span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-lg text-slate-500 mb-12 leading-relaxed font-medium">
                The leading platform connecting passionate volunteers with world-changing NGOs. Join us in making a real, lasting impact today.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary text-base px-10 py-4 w-full sm:w-auto">
                    Get Started Now
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#impact" class="px-10 py-4 rounded-xl border border-slate-200 bg-white text-slate-600 font-bold hover:bg-slate-50 transition-all w-full sm:w-auto">
                    See Our Impact
                </a>
            </div>

            <!-- Dashboard Mockup/Visual -->
            <div class="mt-24 relative p-2 bg-white/50 border border-white rounded-3xl shadow-2xl backdrop-blur-sm max-w-4xl mx-auto group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 to-secondary/10 rounded-3xl -z-10 blur-xl group-hover:blur-2xl transition-all"></div>
                <div class="bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 aspect-video flex items-center justify-center text-slate-300">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-desktop text-6xl mb-4 opacity-50"></i>
                        <p class="font-bold text-sm tracking-widest uppercase">Experience the platform</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stats -->
    <section id="impact" class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-premium text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-building text-2xl text-primary"></i>
                </div>
                <p class="text-4xl font-black text-slate-900 mb-2">500+</p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Partner NGOs</p>
            </div>
            <div class="card-premium text-center">
                <div class="w-16 h-16 bg-rose/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-2xl text-secondary"></i>
                </div>
                <p class="text-4xl font-black text-slate-900 mb-2">10k+</p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Active Volunteers</p>
            </div>
            <div class="card-premium text-center">
                <div class="w-16 h-16 bg-amber/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hand-holding-dollar text-2xl text-accent"></i>
                </div>
                <p class="text-4xl font-black text-slate-900 mb-2">$2M+</p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Raised for Charity</p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <h2 class="text-4xl font-black text-slate-900 mb-4">How It Works</h2>
            <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="relative group">
                <div class="absolute -inset-4 bg-slate-50 rounded-3xl -z-10 group-hover:bg-white transition-all duration-300 group-hover:shadow-xl"></div>
                <div class="mb-8">
                    <div class="w-20 h-20 bg-red-500 rounded-3xl flex items-center justify-center shadow-xl shadow-red-200 transform group-hover:-rotate-6 transition-transform">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4">1. Register</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Create your profile as an individual volunteer or as an NGO looking for support. It takes less than 2 minutes.</p>
            </div>

            <div class="relative group">
                <div class="absolute -inset-4 bg-slate-50 rounded-3xl -z-10 group-hover:bg-white transition-all duration-300 group-hover:shadow-xl"></div>
                <div class="mb-8">
                    <div class="w-20 h-20 bg-amber-500 rounded-3xl flex items-center justify-center shadow-xl shadow-amber-200 transform group-hover:rotate-6 transition-transform">
                        <i class="fas fa-search text-white text-3xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4">2. Discover</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Browse through thousands of events, opportunities, and verified NGOs that align with your passions and skills.</p>
            </div>

            <div class="relative group">
                <div class="absolute -inset-4 bg-slate-50 rounded-3xl -z-10 group-hover:bg-white transition-all duration-300 group-hover:shadow-xl"></div>
                <div class="mb-8">
                    <div class="w-20 h-20 bg-red-500 rounded-3xl flex items-center justify-center shadow-xl shadow-red-200 transform group-hover:-rotate-6 transition-transform">
                        <i class="fas fa-bolt text-white text-3xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4">3. Impact</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Volunteer for events, and earn badges as you contribute to positive global change.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="max-w-7xl mx-auto px-6">
        <div class="bg-slate-900 rounded-[3rem] p-12 md:p-24 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/20 rounded-full blur-[100px] -ml-48 -mb-48"></div>
            
            <div class="relative z-10">
                <h2 class="text-4xl md:text-6xl font-black text-white mb-8">Ready to make a <span class="text-primary italic">difference?</span></h2>
                <p class="text-slate-400 text-lg mb-12 max-w-2xl mx-auto font-medium leading-relaxed">
                    Join the NGO Connect network today and be part of the most active social platform for good.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('register') }}" class="btn-primary px-12 py-4">Join as Individual</a>
                    <a href="{{ route('register', ['role' => 'ngo']) }}" class="text-white font-bold hover:text-primary transition-all">Register an NGO</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
