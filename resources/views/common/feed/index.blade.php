@extends('layouts.app')

@section('content')

    <!-- Smart Feed Location Prompt -->
    @if(!auth()->user()->location)
        <div id="location-prompt" class="glass-panel p-8 mb-8 relative overflow-hidden group transition-all duration-500 hover:shadow-xl">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700"></div>
            
            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/20">
                        <span class="iconify text-3xl animate-pulse" data-icon="fluent:location-ripple-24-filled"></span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">Boost Your Impact Locally</h3>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed">Discover nearby NGOs and events by sharing your location. We'll optimize your feed automatically.</p>
                    </div>
                </div>
                <button id="get-location-btn" class="btn-primary w-full md:w-auto px-8 py-4 flex items-center justify-center gap-3">
                    <span class="iconify text-xl" data-icon="fluent:gps-24-filled"></span>
                    Detect Location
                </button>
            </div>
            
            <div id="location-loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center gap-4 opacity-0 pointer-events-none transition-opacity duration-300 z-10">
                <span class="iconify text-primary text-3xl animate-spin" data-icon="fluent:spinner-ios-20-filled"></span>
                <span class="text-slate-900 text-sm font-bold tracking-widest uppercase" id="location-status-text">Detecting...</span>
            </div>
        </div>
    @endif

    <!-- Create Post Card -->
    <!-- Create Post Card -->
    @if (auth()->user()->isNgo())
        <div class="glass-panel p-6 mb-8 hover:shadow-lg transition-all duration-300">
            <form action="{{ route('common.post.create') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-primary/20 bg-slate-100 p-0.5">
                        @if (auth()->user()->ngo->logo)
                            <img src="{{ asset('storage/' . auth()->user()->ngo->logo) }}" alt="NGO Logo"
                                class="w-full h-full object-cover rounded-[14px]">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                        @endif
                    </div>
                    <input type="text" name="description" placeholder="Share an update, {{ auth()->user()->name }}..."
                        class="input-premium w-full py-3 px-6">
                </div>

                <div class="flex flex-col gap-4">
                @if (count($milestones) > 0)
                    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="iconify text-amber-500 text-lg" data-icon="fluent:board-20-filled"></span>
                        <select name="milestone_id"
                            class="text-xs font-bold text-slate-500 bg-transparent border-none focus:ring-0 cursor-pointer flex-1">
                            <option value="">Link to Milestone (Optional)</option>
                            @foreach ($milestones as $milestone)
                                <option value="{{ $milestone->id }}">{{ $milestone->event->title }}:
                                    {{ $milestone->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <!-- Image Preview Container -->
                <div id="image-preview-container" class="flex gap-2 overflow-x-auto pb-2 hidden"></div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                        <input type="file" name="post_media[]" id="post_media" accept="image/*" multiple class="hidden">
                        <label for="post_media"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-50 transition-all cursor-pointer group">
                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                <i class="fas fa-image"></i>
                            </div>
                            <span class="text-slate-600 font-bold text-sm">Media</span>
                        </label>

                        <button type="submit" class="btn-primary py-2.5 px-6">
                            Post Update
                            <span class="iconify" data-icon="fluent:send-24-filled"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span class="text-red-700 font-semibold text-sm">Please fix the following:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Posts Feed -->
    <div class="space-y-5">
        @if($posts->count() > 0)
            @include('common.feed.partials.post', ['posts' => $posts])
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Posts Yet</h3>
                <p class="text-gray-400 text-sm">Follow some NGOs to see their posts in your feed.</p>
            </div>
        @endif
    </div>

@endsection

@include('common.feed.partials.modal')

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Location Logic ---
            const locationBtn = document.getElementById('get-location-btn');
            const loadingOverlay = document.getElementById('location-loading');
            const statusText = document.getElementById('location-status-text');

            if (locationBtn) {
                locationBtn.addEventListener('click', function() {
                    if (!window.isSecureContext) {
                        alert('Location detection requires a secure connection (HTTPS).');
                        return;
                    }

                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser.');
                        return;
                    }

                    loadingOverlay.classList.remove('opacity-0', 'pointer-events-none');
                    statusText.innerText = 'Requesting Permission...';

                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            const { latitude, longitude } = position.coords;
                            statusText.innerText = 'Identifying District...';

                            try {
                                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`);
                                const data = await response.json();
                                
                                let district = data.address.county || data.address.state_district || data.address.city || data.address.region;
                                
                                if (district) {
                                    district = district.replace(/ District/g, '').replace(/ Municipality/g, '');
                                    statusText.innerText = `Syncing ${district}...`;
                                    
                                    const syncResponse = await fetch("{{ route('common.feed.location') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ district: district })
                                    });

                                    const syncData = await syncResponse.json();

                                    if (syncData.success) {
                                        statusText.innerText = 'Feed Optimized!';
                                        setTimeout(() => {
                                            location.reload();
                                        }, 800);
                                    } else {
                                        throw new Error('Sync failed');
                                    }
                                } else {
                                    throw new Error('Could not determine district');
                                }
                            } catch (error) {
                                console.error('Location Error:', error);
                                alert('We could not identify your district. Please set it manually in Profile Edit.');
                                loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
                            }
                        },
                        (error) => {
                            console.error('Geolocation Error:', error);
                            loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
                            alert('Location detection failed or permission denied.');
                        },
                        { timeout: 10000, enableHighAccuracy: true }
                    );
                });
            }

            // --- Image Preview Logic ---
            const mediaInput = document.getElementById('post_media');
            const previewContainer = document.getElementById('image-preview-container');
            
            if(mediaInput && previewContainer) {
                mediaInput.addEventListener('change', function(e) {
                    previewContainer.innerHTML = ''; // Clear existing
                    const files = e.target.files;
                    
                    if (files.length > 0) {
                        previewContainer.classList.remove('hidden');
                        
                        Array.from(files).forEach((file, index) => {
                            if (!file.type.match('image.*')) return;
                            
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'relative group w-24 h-24 flex-shrink-0';
                                div.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-full object-cover rounded-xl border border-slate-200 shadow-sm">
                                `;
                                previewContainer.appendChild(div);
                            }
                            reader.readAsDataURL(file);
                        });
                        
                        // Add a "Clear" button
                        const clearBtn = document.createElement('button');
                        clearBtn.type = 'button';
                        clearBtn.className = 'w-24 h-24 flex flex-col items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-colors border border-red-100 group';
                        clearBtn.innerHTML = '<span class="iconify text-2xl mb-1 group-hover:scale-110 transition-transform" data-icon="fluent:delete-24-filled"></span><span class="text-xs font-bold">Clear</span>';
                        clearBtn.onclick = function() {
                            mediaInput.value = '';
                            previewContainer.innerHTML = '';
                            previewContainer.classList.add('hidden');
                        };
                        previewContainer.appendChild(clearBtn);
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endpush