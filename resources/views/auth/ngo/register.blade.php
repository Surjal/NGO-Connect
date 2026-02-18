@extends('layouts.guest')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-12">

        <div class="w-full max-w-4xl">
            <!-- Logo -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-block group">
                    <img src="{{ url('logo-nobg.png') }}" alt="Logo" class="h-12 mx-auto group-hover:scale-105 transition-all duration-300 drop-shadow-md">
                </a>
            </div>

            <!-- Registration Card -->
            <div class="glass-panel overflow-hidden relative">
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>

                <!-- Header + Progress -->
                <div class="relative p-8 pb-6 border-b border-slate-100 text-center">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">NGO Registration</h1>
                    <p class="text-slate-500 font-medium mb-8">Register your organization and make a difference</p>

                    <!-- Step Indicators -->
                    <div class="flex justify-center">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div id="step1-indicator"
                                    class="w-9 h-9 bg-primary text-white rounded-xl flex items-center justify-center text-sm font-black shadow-lg shadow-primary/20">
                                    1</div>
                                <span id="step1-text" class="ml-2 text-sm font-bold text-primary">Basic Details</span>
                            </div>
                            <div class="w-12 h-1 rounded-full bg-slate-200" id="progress1"></div>
                            <div class="flex items-center">
                                <div id="step2-indicator"
                                    class="w-9 h-9 bg-slate-200 text-slate-500 rounded-xl flex items-center justify-center text-sm font-black">
                                    2</div>
                                <span id="step2-text" class="ml-2 text-sm font-bold text-slate-400">Legal Details</span>
                            </div>
                            <div class="w-12 h-1 rounded-full bg-slate-200" id="progress2"></div>
                            <div class="flex items-center">
                                <div id="step3-indicator"
                                    class="w-9 h-9 bg-slate-200 text-slate-500 rounded-xl flex items-center justify-center text-sm font-black">
                                    3</div>
                                <span id="step3-text" class="ml-2 text-sm font-bold text-slate-400">Contact Person</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="relative p-8 md:p-10">
                    @if (session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 p-4 mb-6 rounded-xl">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.ngo') }}" enctype="multipart/form-data"
                        id="ngoRegistrationForm">
                        @csrf
                        <!-- Step 1: Basic Details -->
                        <div id="step1" class="step-content">
                            <div class="bg-primary/5 border border-primary/20 text-primary p-4 mb-6 rounded-xl">
                                <p class="text-sm font-medium">
                                    <strong>Note:</strong> Fields marked with * are required. Please fill them.
                                </p>
                            </div>

                            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-6">Basic Details</h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="ngo_name" class="block text-sm font-bold text-slate-700">NGO Name *</label>
                                        <input type="text" name="ngo_name" id="ngo_name" value="{{ old('ngo_name') }}"
                                            class="input-premium"
                                            placeholder="Enter NGO name" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="registration_date" class="block text-sm font-bold text-slate-700">Registration Date *</label>
                                        <input type="date" name="registration_date" id="registration_date"
                                            value="{{ old('registration_date') }}"
                                            class="input-premium" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="category" class="block text-sm font-bold text-slate-700">Category *</label>
                                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                                            class="input-premium"
                                            placeholder="e.g., Education, Health, Environment" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="subcategory" class="block text-sm font-bold text-slate-700">Subcategory</label>
                                        <input type="text" name="subcategory" id="subcategory" value="{{ old('subcategory') }}"
                                            class="input-premium"
                                            placeholder="e.g., Child Education, Mental Health">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="address" class="block text-sm font-bold text-slate-700">Address *</label>
                                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                                            class="input-premium"
                                            placeholder="Enter complete address" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="ngo_phone" class="block text-sm font-bold text-slate-700">Phone Number *</label>
                                        <input type="tel" name="ngo_phone" id="ngo_phone" value="{{ old('ngo_phone') }}"
                                            class="input-premium"
                                            placeholder="Enter phone number" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="email" class="block text-sm font-bold text-slate-700">Email Address *</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            class="input-premium"
                                            placeholder="Enter email address" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="password" class="block text-sm font-bold text-slate-700">Password *</label>
                                        <input type="password" name="password" id="password" value="{{ old('password') }}"
                                            class="input-premium"
                                            placeholder="Enter password" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirm Password *</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="input-premium"
                                            placeholder="Confirm password" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="logo" class="block text-sm font-bold text-slate-700">NGO Logo</label>
                                        <input type="file" name="logo" id="logo"
                                            class="input-premium text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                            accept="image/jpeg,image/png">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="photos" class="block text-sm font-bold text-slate-700">Photos (up to 5)</label>
                                    <input type="file" name="photos[]" id="photos" multiple
                                        class="input-premium text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                        accept="image/jpeg,image/png">
                                </div>

                                <div class="space-y-2">
                                    <label for="mission" class="block text-sm font-bold text-slate-700">Mission</label>
                                    <textarea name="mission" id="mission" rows="3"
                                        class="input-premium"
                                        placeholder="Describe your organization's mission (optional)">{{ old('mission') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Legal Details -->
                        <div id="step2" class="step-content hidden">
                            <div class="bg-primary/5 border border-primary/20 text-primary p-4 mb-6 rounded-xl">
                                <p class="text-sm font-medium">
                                    <strong>Note:</strong> Fields marked with * are required. Please fill them.
                                </p>
                            </div>

                            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-6">Legal Details</h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="registration_number" class="block text-sm font-bold text-slate-700">Registration Number (DAO) *</label>
                                        <input type="text" name="registration_number" id="registration_number"
                                            value="{{ old('registration_number') }}"
                                            class="input-premium"
                                            placeholder="Enter DAO registration number" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="registration_district" class="block text-sm font-bold text-slate-700">Registration District (DAO) *</label>
                                        <input type="text" name="registration_district" id="registration_district"
                                            value="{{ old('registration_district') }}"
                                            class="input-premium"
                                            placeholder="Enter registration district" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="last_renewal_date" class="block text-sm font-bold text-slate-700">Last Renewal Date *</label>
                                        <input type="date" name="last_renewal_date" id="last_renewal_date"
                                            value="{{ old('last_renewal_date') }}"
                                            class="input-premium" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="pan_number" class="block text-sm font-bold text-slate-700">PAN Number *</label>
                                        <input type="text" name="pan_number" id="pan_number" value="{{ old('pan_number') }}"
                                            class="input-premium"
                                            placeholder="Enter PAN number" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Contact Person -->
                        <div id="step3" class="step-content hidden">
                            <div class="bg-primary/5 border border-primary/20 text-primary p-4 mb-6 rounded-xl">
                                <p class="text-sm font-medium">
                                    <strong>Note:</strong> Fields marked with * are required. Please fill them.
                                </p>
                            </div>

                            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-6">Contact Person Details</h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="contact_full_name" class="block text-sm font-bold text-slate-700">Full Name *</label>
                                        <input type="text" name="contact_full_name" id="contact_full_name"
                                            value="{{ old('contact_full_name') }}"
                                            class="input-premium"
                                            placeholder="Enter contact person's full name" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="contact_position" class="block text-sm font-bold text-slate-700">Position / Role in NGO *</label>
                                        <input type="text" name="contact_position" id="contact_position"
                                            value="{{ old('contact_position') }}"
                                            class="input-premium"
                                            placeholder="e.g., President, Vice-President, Secretary" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="contact_phone" class="block text-sm font-bold text-slate-700">Phone Number *</label>
                                        <input type="tel" name="contact_phone" id="contact_phone"
                                            value="{{ old('contact_phone') }}"
                                            class="input-premium"
                                            placeholder="Enter contact phone number" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="contact_email" class="block text-sm font-bold text-slate-700">Email Address *</label>
                                        <input type="email" name="contact_email" id="contact_email"
                                            value="{{ old('contact_email') }}"
                                            class="input-premium"
                                            placeholder="Enter contact email address" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="contact_password" class="block text-sm font-bold text-slate-700">Password *</label>
                                        <input type="password" name="contact_password" id="contact_password"
                                            value="{{ old('contact_password') }}"
                                            class="input-premium"
                                            placeholder="Enter contact person's password" required>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="contact_password_confirmation" class="block text-sm font-bold text-slate-700">Confirm Password *</label>
                                        <input type="password" name="contact_password_confirmation"
                                            id="contact_password_confirmation"
                                            class="input-premium"
                                            placeholder="Confirm contact person's password" required>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="contact_address" class="block text-sm font-bold text-slate-700">Address *</label>
                                    <input type="text" name="contact_address" id="contact_address"
                                        value="{{ old('contact_address') }}"
                                        class="input-premium"
                                        placeholder="Enter contact person's address" required>
                                </div>

                                <!-- Declaration & Signature -->
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <h3 class="text-lg font-black text-slate-900 tracking-tight mb-4">Declaration & Signature</h3>
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" name="declaration" id="declaration"
                                            class="mt-1 w-5 h-5 text-primary border-slate-300 rounded-lg focus:ring-primary/30"
                                            required>
                                        <label for="declaration" class="text-sm text-slate-600 leading-relaxed font-medium">
                                            I hereby certify that all the information provided and documents uploaded are true,
                                            accurate, and complete to the best of my knowledge. I understand that any false
                                            information may result in the rejection of this application or cancellation of
                                            registration.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between mt-10">
                            <button type="button" id="prevBtn"
                                class="btn-secondary px-8 py-3 hidden">
                                <i class="fas fa-arrow-left"></i>
                                Previous
                            </button>

                            <div class="text-center" id="signinBtn">
                                <p class="text-slate-500 text-sm font-medium">
                                    Already have an account?
                                    <a href="{{ route('login') }}"
                                        class="text-primary hover:text-primary-hover font-bold transition-colors">
                                        Sign In
                                    </a>
                                </p>
                            </div>

                            <div>
                                <button type="button" id="nextBtn" class="btn-primary px-8 py-3">
                                    Next
                                    <i class="fas fa-arrow-right"></i>
                                </button>

                                <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 hidden">
                                    <i class="fas fa-check-circle"></i>
                                    Submit Registration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let currentStep = 1;
            const totalSteps = 3;

            function showStep(step) {
                // Hide all steps
                for (let i = 1; i <= totalSteps; i++) {
                    $(`#step${i}`).addClass('hidden');
                    $(`#step${i}-indicator`).removeClass('bg-primary text-white shadow-lg shadow-primary/20').addClass('bg-slate-200 text-slate-500');
                    $(`#step${i}-text`).removeClass('text-primary').addClass('text-slate-400');
                }

                // Show current step
                $(`#step${step}`).removeClass('hidden');
                $(`#step${step}-indicator`).removeClass('bg-slate-200 text-slate-500').addClass('bg-primary text-white shadow-lg shadow-primary/20');
                $(`#step${step}-text`).removeClass('text-slate-400').addClass('text-primary');

                // Update progress bars
                for (let i = 1; i < step; i++) {
                    $(`#progress${i}`).removeClass('bg-slate-200').addClass('bg-primary');
                }
                for (let i = step; i < totalSteps; i++) {
                    $(`#progress${i}`).removeClass('bg-primary').addClass('bg-slate-200');
                }

                // Show/hide navigation buttons
                if (step === 1) {
                    $('#prevBtn').addClass('hidden');
                } else {
                    $('#prevBtn').removeClass('hidden');
                }

                if (step === totalSteps) {
                    $('#nextBtn').addClass('hidden');
                    $('#submitBtn').removeClass('hidden');
                } else {
                    $('#nextBtn').removeClass('hidden');
                    $('#submitBtn').addClass('hidden');
                }
            }

            function validateStep(step) {
                const stepElement = $(`#step${step}`);
                const requiredFields = stepElement.find('[required]');

                for (let i = 0; i < requiredFields.length; i++) {
                    const field = $(requiredFields[i]);
                    if (!field.val().trim()) {
                        field.focus();
                        alert('Please fill in all required fields before proceeding.');
                        return false;
                    }
                }
                return true;
            }

            $('#nextBtn').on('click', function () {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });

            $('#prevBtn').on('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // AJAX form submission
            $('#ngoRegistrationForm').on('submit', function (e) {
                e.preventDefault();

                if (!validateStep(currentStep)) {
                    return;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...');
                    },
                    success: function (response) {
                        alert('NGO registration submitted successfully!');
                        window.location.href = response.redirect || '/';
                    },
                    error: function (xhr) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> Submit Registration');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = 'Please fix the following errors:\n';

                            $.each(errors, function (field, messages) {
                                errorMessage += '- ' + messages.join(', ') + '\n';
                            });

                            alert(errorMessage);
                        } else {
                            alert('An error occurred while submitting the form. Please try again.');
                        }
                    }
                });
            });

            // Initialize first step
            showStep(1);
        });
    </script>

@endsection