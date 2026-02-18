@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('ngo.events', $event->id) }}"
                    class="inline-flex items-center text-red-500 hover:text-red-700 font-semibold transition-colors">
                    <span class="iconify inline-block mr-2" data-icon="fluent:arrow-left-20-filled" data-width="20"
                        data-height="20"></span>
                    Back to Event
                </a>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white rounded-sm overflow-hidden shadow-xl">
                <!-- Header -->
                <div class="bg-gradient-to-l from-red-400 to-red-500 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Edit Event</h1>
                    <p class="text-red-100 mt-2">Update all the details of your event</p>
                </div>

                <!-- Form Content -->
                <form action="{{ route('ngo.event.update', $event->id) }}" method="POST" class="p-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                            <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                            <ul class="list-disc list-inside text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Cover Image Section -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Cover Image</h2>

                        <!-- Current Image Preview -->
                        @if ($event->cover_image_path_name)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}"
                                    class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <label for="cover_image_path_name" class="block text-sm font-semibold text-gray-900">Upload New
                                Image</label>
                            <input type="file" id="cover_image_path_name" name="cover_image_path_name" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 {{ $errors->has('cover_image_path_name') ? 'border-red-500' : '' }}">
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Basic Information</h2>

                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Event Title
                                    *</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('title') ? 'border-red-500' : '' }}">
                                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description
                                    *</label>
                                <textarea id="description" name="description" rows="4" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('description') ? 'border-red-500' : '' }}">{{ old('description', $event->description) }}</textarea>
                                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-900 mb-2">Event Type
                                    *</label>
                                <select id="type" name="type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('type') ? 'border-red-500' : '' }}">
                                    <option value="0" {{ old('type', $event->type) == 0 ? 'selected' : '' }}>Online Event
                                    </option>
                                    <option value="1" {{ old('type', $event->type) == 1 ? 'selected' : '' }}>Offline Event
                                    </option>
                                </select>
                                @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Volunteers Requirement -->
                            <div>
                                <label for="is_volunteers_required"
                                    class="block text-sm font-semibold text-gray-900 mb-2">Volunteers Required *</label>
                                <select name="is_volunteers_required" id="is_volunteers_required"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('is_volunteers_required') ? 'border-red-500' : '' }}"
                                    required>
                                    <option value="1" {{ old('is_volunteers_required') == '1' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ old('is_volunteers_required') == '0' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Date & Time</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Start Date & Time -->
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-900 mb-2">Start Date &
                                    Time *</label>
                                <input type="datetime-local" id="start_date" name="start_date"
                                    value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('start_date') ? 'border-red-500' : '' }}">
                                @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- End Date & Time -->
                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-900 mb-2">End Date & Time
                                    *</label>
                                <input type="datetime-local" id="end_date" name="end_date"
                                    value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('end_date') ? 'border-red-500' : '' }}">
                                @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location & Capacity -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Location & Capacity</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">Location
                                    *</label>
                                <input type="text" id="location" name="location"
                                    value="{{ old('location', $event->location) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('location') ? 'border-red-500' : '' }}">
                                @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Capacity -->
                            <div id="volunteerCapacity">
                                <label for="capacity" class="block text-sm font-semibold text-gray-900 mb-2">Volunteer
                                    Capacity *</label>
                                <input type="number" id="capacity" name="capacity"
                                    value="{{ old('capacity', $event->capacity) }}" min="1" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('capacity') ? 'border-red-500' : '' }}">
                                @error('capacity')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Requirements</h2>

                        <div class="space-y-4">
                            <!-- Requirements -->
                            <div>
                                <textarea id="requirements" name="requirements" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors {{ $errors->has('requirements') ? 'border-red-500' : '' }}">{{ old('requirements', $event->requirements) }}</textarea>
                                @error('requirements')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-md hover:shadow-lg">
                            <span class="iconify mr-2" data-icon="fluent:checkmark-20-filled" data-width="20"
                                data-height="20"></span>
                            Save Changes
                        </button>
                        <a href="{{ route('ngo.events', $event->id) }}"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-md hover:shadow-lg">
                            <span class="iconify mr-2" data-icon="fluent:dismiss-20-filled" data-width="20"
                                data-height="20"></span>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <script>
    $('#is_volunteers_required').change(function (e) {
            e.preventDefault();
            if ($(this).val() == "1") {
                // console.log($(this).val());
                $('#volunteerCapacity').show();

            } else {
               $('#capacity').val("0");
                $('#volunteerCapacity').hide();
            }
        });
        </script>
@endsection