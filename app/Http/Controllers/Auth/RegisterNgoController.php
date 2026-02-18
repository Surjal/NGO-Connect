<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Ngo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterNgoController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.ngo.register');
    }

    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'ngo_name' => ['required', 'string', 'max:255'],
            'registration_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:255'],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'ngo_phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'registration_number' => ['required', 'string', 'max:255'],
            'registration_district' => ['required', 'string', 'max:255'],
            'last_renewal_date' => ['required', 'date'],
            'pan_number' => ['required', 'string'],
            'contact_full_name' => ['required', 'string', 'max:255'],
            'contact_position' => ['nullable','string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'contact_password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact_address' => ['required', 'string', 'max:255'],
            'declaration' => ['nullable','required', 'accepted'],
            'mission' => ['nullable','string','max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Start a database transaction
            $result = DB::transaction(function () use ($request) {
                // Handle logo upload
                $logoPath = null;
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('logos', 'public');
                }

                // Handle photos upload (up to 5)
                $photoPaths = [];
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $photoPaths[] = $photo->store('photos', 'public');
                    }
                }

                // Create contact person user in users table
                $contactUser = User::create([
                    'name' => $request->contact_full_name,
                    'email' => $request->contact_email,
                    'password' => Hash::make($request->contact_password),
                    'phone' => $request->contact_phone,
                    'role_id' => 2, // Role 2 is for contact persons
                    'verified' => false,
                ]);

                // Create NGO user in users table
                $ngoUser = User::create([
                    'name' => $request->ngo_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => 1, // Role 1 is for NGOs
                    'owner_id' => $contactUser->id,
                    'verified' => false,
                ]);

                // Create NGO record in ngos table
                $ngo = Ngo::create([
                    'user_id' => $ngoUser->id,
                    'ngo_name' => $request->ngo_name,
                    'registration_date' => $request->registration_date,
                    'category' => $request->category,
                    'subcategory' => $request->subcategory,
                    'address' => $request->address,
                    'phone' => $request->ngo_phone,
                    'registration_number' => $request->registration_number,
                    'registration_district' => $request->registration_district,
                    'last_renewal_date' => $request->last_renewal_date,
                    'pan_number' => $request->pan_number,
                    'mission' => $request->mission,
                    'contact_position' => $request->contact_position,
                    'description' => null,
                    'photos' => $photoPaths ? json_encode($photoPaths) : null,
                    'logo' => $logoPath,
                ]);

                // Send welcome email to contact person with NGO details
                // Mail::to($contactUser->email)->send(new WelcomeMail($contactUser, $ngo));

                // return [
                //     'contact_user' => $contactUser,
                //     'ngo_user' => $ngoUser,
                //     'ngo' => $ngo,
                // ];
            });

            return response()->json([
                'message' => 'NGO registration successful',
                'redirect' => route('login'),
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('NGO Registration Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred during registration. Please try again.',
            ], 500);
        }
    }
}
