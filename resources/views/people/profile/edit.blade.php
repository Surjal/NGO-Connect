@extends('layouts.app')

@section('content')
    <div class="w-full py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit Profile</h1>
                <p class="text-xs text-gray-500 font-medium mt-1">Manage your account details and preferences</p>
            </div>
            <a href="{{ route('people.profile') }}" class="flex items-center gap-2 text-gray-500 hover:text-red-500 font-bold transition-colors text-xs">
                <span class="iconify text-lg" data-icon="fluent:arrow-left-20-filled"></span>
                Back
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl">
                <div class="flex gap-3">
                    <span class="iconify text-red-500 text-xl flex-shrink-0" data-icon="fluent:error-circle-20-filled"></span>
                    <ul class="list-disc list-inside text-xs font-bold text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="profileForm" action="{{ route('people.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section: Identity -->
            <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 md:p-10">
                    <div class="flex flex-col lg:flex-row gap-10">
                        <!-- Profile Photo Sidebar -->
                        <div class="w-full lg:w-1/3 flex flex-col items-center justify-center space-y-6 pb-8 lg:pb-0 lg:border-r lg:border-gray-50 pr-0 lg:pr-10">
                            <div class="relative">
                                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-8 border-gray-50 bg-gray-50 shadow-inner group hover:border-red-50 transition-all duration-300">
                                    <img id="photo-preview" 
                                        src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : '' }}" 
                                        alt="Current Profile Photo"
                                        class="w-full h-full object-cover {{ !auth()->user()->profile_photo ? 'hidden' : '' }}">
                                    <div id="photo-placeholder" class="w-full h-full flex items-center justify-center {{ auth()->user()->profile_photo ? 'hidden' : '' }}">
                                        <span class="iconify text-gray-200 text-7xl" data-icon="fluent:person-20-filled"></span>
                                    </div>
                                    
                                    <!-- Hover Overlay -->
                                    <label for="profile_photo" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                                        <span class="iconify text-white text-3xl" data-icon="fluent:camera-add-24-filled"></span>
                                    </label>
                                </div>
                                
                                <!-- Standalone Camera Button for Mobile/Non-hover -->
                                <label for="profile_photo" class="absolute bottom-1 right-1 w-10 h-10 bg-red-600 text-white rounded-full flex lg:hidden items-center justify-center cursor-pointer shadow-lg border-2 border-white">
                                    <span class="iconify text-xl" data-icon="fluent:camera-20-filled"></span>
                                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden">
                                </label>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-1">Profile Image</h3>
                                <p class="text-[10px] text-gray-400 font-medium leading-relaxed">JPG or PNG allowed.<br>Maximum 2MB file size.</p>
                                <label for="profile_photo" class="hidden lg:inline-flex mt-4 px-4 py-2 border border-gray-200 text-[10px] font-black uppercase tracking-widest text-gray-600 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    Change Photo
                                </label>
                            </div>
                        </div>

                        <!-- Info Inputs Main Area -->
                        <div class="flex-1 space-y-8">
                            <div>
                                <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                                    Identity & Location
                                </h2>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label for="name" class="block text-[10px] font-black text-gray-500 uppercase tracking-tighter mb-2 ml-1">Account Display Name</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500">
                                                <span class="iconify text-xl" data-icon="fluent:person-20-filled"></span>
                                            </div>
                                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-500 focus:bg-white transition-all font-bold text-gray-700 text-sm"
                                                placeholder="Enter your full name" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="location" class="block text-[10px] font-black text-gray-500 uppercase tracking-tighter mb-2 ml-1">Service Area (District)</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500">
                                                <span class="iconify text-xl" data-icon="fluent:location-20-filled"></span>
                                            </div>
                                            <select name="location" id="location"
                                                class="block w-full pl-12 pr-10 py-4 bg-gray-50/50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-500 focus:bg-white transition-all font-bold text-gray-700 appearance-none text-sm">
                                                <option value="">Select District</option>
                                                @php
                                                    $districts = ['Achham','Arghakhanchi','Baglung','Baitadi','Bajhang','Bajura','Banke','Bara','Bardiya','Bhaktapur','Bhojpur','Chitwan','Dadeldhura','Dailekh','Dang','Darchula','Dhading','Dhankuta','Dhanusa','Dolakha','Dolpa','Doti','Gorkha','Gulmi','Humla','Ilam','Jajarkot','Jhapa','Jumla','Kailali','Kalikot','Kanchanpur','Kapilvastu','Kaski','Kathmandu','Kavrepalanchok','Khotang','Lalitpur','Lamjung','Mahottari','Makwanpur','Manang','Morang','Mugu','Mustang','Myagdi','Nawalpur','Nuwakot','Okhaldhunga','Palpa','Panchthar','Parbat','Parsa','Pyuthan','Ramechhap','Rasuwa','Rautahat','Rolpa','Rukum','Rupandehi','Salyan','Sankhuwasabha','Saptari','Sarlahi','Sindhuli','Sindhupalchok','Siraha','Solukhumbu','Sunsari','Surkhet','Syangja','Tanahu','Taplejung','Terhathum','Udayapur'];
                                                @endphp
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district }}" {{ old('location', auth()->user()->location) == $district ? 'selected' : '' }}>
                                                        {{ $district }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                                <span class="iconify text-xl" data-icon="fluent:chevron-down-20-filled"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Interests -->
            <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 md:p-8">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                        Interests & Personalization
                    </h2>
                    <p class="text-xs text-gray-500 font-medium mb-8">Choose topics you care about to personalize your experience</p>

                    @php
                        $categories = [
                            ['name' => 'Education', 'icon' => 'fluent:book-education-20-filled'],
                            ['name' => 'Health', 'icon' => 'fluent:heart-pulse-20-filled'],
                            ['name' => 'Environment', 'icon' => 'fluent:leaf-one-20-filled'],
                            ['name' => 'Human Rights', 'icon' => 'fluent:scales-20-filled'],
                            ['name' => 'Child Welfare', 'icon' => 'fluent:teddy-20-filled'],
                            ['name' => 'Women Empowerment', 'icon' => 'fluent:person-support-20-filled'],
                            ['name' => 'Disaster Relief', 'icon' => 'fluent:shield-alert-20-filled'],
                            ['name' => 'Animal Welfare', 'icon' => 'fluent:animal-dog-20-filled'],
                            ['name' => 'Arts & Culture', 'icon' => 'fluent:paint-brush-20-filled'],
                            ['name' => 'Sports', 'icon' => 'fluent:sport-20-filled'],
                            ['name' => 'Technology', 'icon' => 'fluent:laptop-20-filled'],
                            ['name' => 'Agriculture', 'icon' => 'fluent:food-20-filled'],
                            ['name' => 'Community Development', 'icon' => 'fluent:people-community-20-filled'],
                        ];
                        $userCategories = old('preferred_categories', auth()->user()->preferred_categories ?? []);
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($categories as $cat)
                            @php $id = 'cat-' . Str::slug($cat['name']); @endphp
                            <div class="relative">
                                <input type="checkbox" name="preferred_categories[]" value="{{ $cat['name'] }}" id="{{ $id }}"
                                    class="interest-input sr-only"
                                    {{ in_array($cat['name'], $userCategories) ? 'checked' : '' }}>
                                <label for="{{ $id }}" class="interest-card block p-4 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-red-200 transition-all shadow-sm cursor-pointer group relative overflow-hidden">
                                    <div class="flex items-center gap-4 relative z-10">
                                        <!-- Icon Container -->
                                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-red-500 group-hover:border-red-100 transition-all shadow-sm icon-box">
                                            <span class="iconify text-2xl" data-icon="{{ $cat['icon'] }}"></span>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <span class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition-colors block">{{ $cat['name'] }}</span>
                                            <div class="flex items-center gap-1 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <div class="w-1 h-1 bg-red-400 rounded-full"></div>
                                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-tighter">Impact Theme</span>
                                            </div>
                                        </div>

                                        <!-- Selection Indicator -->
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center transition-all check-indicator">
                                            <span class="iconify text-white text-sm scale-0 transition-transform opacity-0" data-icon="fluent:checkmark-20-filled"></span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <button type="submit" class="w-full sm:w-auto px-12 py-4 bg-red-600 text-white font-black uppercase tracking-widest rounded-3xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-[0.98] text-xs">
                    Apply Changes
                </button>
                <a href="{{ route('people.profile') }}"
                    class="w-full sm:w-auto px-12 py-4 bg-gray-100 text-gray-500 font-black uppercase tracking-widest rounded-3xl hover:bg-gray-200 transition-all text-center text-xs">
                    Discard
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* Premium Checkbox Toggle Visuals */
        .interest-input:checked + .interest-card {
            background-color: #fef2f2 !important; /* red-50 */
            border-color: #ef4444 !important; /* red-500 */
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.1), 0 4px 6px -2px rgba(239, 68, 68, 0.05) !important;
        }
        
        .interest-input:checked + .interest-card .icon-box {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: white !important;
            transform: scale(1.05);
        }

        .interest-input:checked + .interest-card .check-indicator {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
        }

        .interest-input:checked + .interest-card .check-indicator .iconify {
            transform: scale(1) !important;
            opacity: 1 !important;
        }

        .interest-input:checked + .interest-card .opacity-0 {
            opacity: 1 !important;
        }

        .interest-input:checked + .interest-card span {
            color: #b91c1c !important; /* red-700 */
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Photo Preview Logic
            const photoInput = document.getElementById('profile_photo');
            const photoPreview = document.getElementById('photo-preview');
            const photoPlaceholder = document.getElementById('photo-placeholder');

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                            alert('Please select a valid image file (JPG, PNG).');
                            this.value = '';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size exceeds 2MB limit.');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                            photoPreview.classList.remove('hidden');
                            photoPlaceholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush

