@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-slate-900">Edit Organization Profile</h1>
            <p class="text-slate-500 mt-2">Update your NGO's information and public appearance.</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-start gap-3">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden relative">
            <!-- Decorative Top Border -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-500 via-red-600 to-red-500"></div>

            <form id="ngoProfileForm" action="{{ route('ngo.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <!-- Logo Section -->
                <div class="flex flex-col items-center mb-10">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full ring-4 ring-slate-50 shadow-lg overflow-hidden bg-white flex items-center justify-center relative">
                             @if ($ngo && $ngo->logo)
                                <img id="logo-preview" src="{{ asset('storage/' . $ngo->logo) }}" class="w-full h-full object-cover">
                                <div id="logo-placeholder" class="hidden absolute inset-0 bg-slate-50 flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @else
                                <div id="logo-placeholder" class="absolute inset-0 bg-slate-50 flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                                <img id="logo-preview" src="#" class="hidden w-full h-full object-cover relative z-10">
                            @endif
                        </div>
                        <label for="logo" class="absolute bottom-0 right-0 w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-red-700 hover:scale-110 transition-all z-20 group-hover:ring-2 ring-white">
                            <i class="fas fa-camera text-sm"></i>
                        </label>
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden">
                    </div>
                     <p class="text-xs text-slate-400 mt-3 font-medium">Click camera icon to upload new logo</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Organization Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $ngo->user->name ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all placeholder:text-slate-300" required>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-bold text-slate-700 mb-2">Primary Category</label>
                        <div class="relative">
                            <select name="category" id="category"
                                class="w-full px-4 py-3 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all appearance-none bg-white">
                                <option value="">Select Category</option>
                                <option value="Education" {{ old('category', $ngo->category ?? '') == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Health" {{ old('category', $ngo->category ?? '') == 'Health' ? 'selected' : '' }}>Health</option>
                                <option value="Environment" {{ old('category', $ngo->category ?? '') == 'Environment' ? 'selected' : '' }}>Environment</option>
                                <option value="Poverty Alleviation" {{ old('category', $ngo->category ?? '') == 'Poverty Alleviation' ? 'selected' : '' }}>Poverty Alleviation</option>
                                <option value="Human Rights" {{ old('category', $ngo->category ?? '') == 'Human Rights' ? 'selected' : '' }}>Human Rights</option>
                                <option value="Disaster Relief" {{ old('category', $ngo->category ?? '') == 'Disaster Relief' ? 'selected' : '' }}>Disaster Relief</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sub Categories -->
                    <div>
                        <label for="sub_categories" class="block text-sm font-bold text-slate-700 mb-2">Sub Categories</label>
                        <input type="text" name="sub_categories" id="sub_categories"
                            value="{{ old('sub_categories', $ngo->sub_categories ?? '') }}"
                            placeholder="e.g. Scholarship, Medical Camp"
                            class="w-full px-4 py-3 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all placeholder:text-slate-300">
                    </div>

                     <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-bold text-slate-700 mb-2">Headquarters Location</label>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-slate-400"></i>
                            </div>
                            <input type="text" name="location" id="location" value="{{ old('location', $ngo->location ?? '') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all placeholder:text-slate-300" placeholder="Street Address, City, Country">
                        </div>
                    </div>

                    <!-- Mission -->
                    <div class="md:col-span-2">
                        <label for="mission" class="block text-sm font-bold text-slate-700 mb-2">Mission Statement</label>
                        <textarea name="mission" id="mission" rows="3"
                            class="w-full p-4 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all resize-none placeholder:text-slate-300" placeholder="Briefly describe your organization's core mission...">{{ old('mission', $ngo->mission ?? '') }}</textarea>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                         <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Detailed Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="w-full p-4 rounded-xl border border-slate-400 focus:border-red-500 focus:ring-red-500/20 font-medium transition-all placeholder:text-slate-300" placeholder="Tell the world about your organization's history, impact, and ongoing projects...">{{ old('description', $ngo->description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('ngo.profile') }}"
                        class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#ngoProfileForm').on('submit', function(e) {
                let name = $('#name').val().trim();
                if (!name) {
                    e.preventDefault();
                    alert('Please enter the NGO name.');
                }
            });

            // Image Preview Logic
            $('#logo').on('change', function() {
                let file = this.files[0];
                if (file) {
                    if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                        alert('Please select a valid image file (JPG, PNG).');
                        this.value = '';
                        return;
                    }

                    // Show preview
                    let reader = new FileReader();
                    reader.onload = function(e) {
                         $('#logo-preview').attr('src', e.target.result).removeClass('hidden');
                         $('#logo-placeholder').addClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
