<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Notifications\NgoRegistrationApproved;
use App\Notifications\NgoRegistrationRejected;

class NgoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:admin']);
    }
    public function showNgos(Request $request)
    {
        $query = User::where('role_id', 1)->with('ngo');

        // ---------- SEARCH ----------
        if ($request->filled('name')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->name) . '%']);
        }
        if ($request->filled('category')) {
            $query->whereHas('ngo', fn($q) => $q->whereRaw('LOWER(category) LIKE ?', ['%' . strtolower($request->category) . '%']));
        }
        if ($request->filled('address')) {
            $query->whereHas('ngo', fn($q) => $q->whereRaw('LOWER(address) LIKE ?', ['%' . strtolower($request->address) . '%']));
        }
        if ($request->filled('registration')) {
            $query->whereHas('ngo', fn($q) => $q->whereRaw('LOWER(registration_number) LIKE ?', ['%' . strtolower($request->registration) . '%']));
        }

        $ngos = $query->paginate(10)->appends($request->all());

        // ---------- ALWAYS JSON ----------
        if ($request->has('ngo')) {
            return response()->json([
                'ngos'        => $ngos->items(),                 // array of NGO objects
                'current_page' => $ngos->currentPage(),
                'last_page'   => $ngos->lastPage(),
                'links'       => $ngos->linkCollection()->toArray(), // pagination URLs
                'total'       => $ngos->total(),
            ]);
        }

        return view('admin.ngos.list', compact('ngos'));
    }

    // public function ngos()
    // {
    //     $ngos = User::where('role_id', 1)->with('ngo')->paginate(10);
    //     return view('admin.ngos.index', compact('ngos'));
    // }

    public function show($id)
    {
        $ngo = User::where('role_id', 1)->with('ngo')->findOrFail($id);
        return view('admin.ngos.show', compact('ngo'));
    }

    public function verifyNgo(Request $request, $id)
    {
        $ngo = User::where('role_id', 1)->findOrFail($id);

        $ngoTable = Ngo::where('user_id', $ngo->id)->first();

        $ngo->update(['verified' => true]);
        $ngoTable->update(['verified' => true]);

        $owner = User::find($ngo->owner_id);

        // Notify the NGO
        // $ngo->notify(new NgoRegistrationApproved($ngo->name, true));

        // Notify the owner if exists
        if ($ngo->owner_id) {
            if ($owner) {
                // $owner->notify(new NgoRegistrationApproved($ngo->name, false));
            }
        }

        return redirect()->route('admin.ngos')->with('success', 'NGO verified successfully.');
    }

    public function rejectNgo(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $ngo = User::where('role_id', 1)->findOrFail($id);
        // $ngo->notify(new NgoRegistrationRejected($request->rejection_reason));
        $ngo->update(['verified' => 2]);
        return redirect()->route('admin.ngos')->with('success', 'NGO registration rejected and deleted.');
    }
    public function getAll()
    {
        $ngos = \App\Models\Ngo::select('id', 'name', 'category', 'address', 'registration_number')->get();
        return response()->json(['data' => $ngos]);
    }

    public function getDetails($id)
    {
        $ngo = \App\Models\Ngo::findOrFail($id);
        return response()->json(['data' => $ngo]);
    }
    public function search(Request $request)
    {
        $query = \App\Models\Ngo::query();

        // Apply filters if provided (fuzzy LIKE search)
        if ($request->filled('ngo_name')) {
            $query->where('name', 'like', '%' . $request->ngo_name . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('registration_number')) {
            $query->where('registration_number', 'like', '%' . $request->registration_number . '%');
        }

        // Paginate results (20 per page, same as initial load)
        $ngos = $query->paginate(20);

        // Preserve search params in pagination links
        $ngos->appends($request->only(['ngo_name', 'category', 'address', 'registration_number']));

        // Return JSON: data (array of NGOs), current_page, last_page, links (HTML for pagination)
        return response()->json([
            'data' => $ngos->items(), // Array of NGO objects for table rendering
            'current_page' => $ngos->currentPage(),
            'last_page' => $ngos->lastPage(),
            'links' => $ngos->links()->toHtml(), // Laravel's pagination HTML
        ]);
    }
    public function suspend(Request $request, $id)
    {
        $ngo = User::where('role_id', 1)->where('id', $id)->first();
        $ngo = Ngo::where('user_id', $ngo->id)->first();
        if ($ngo->suspended) {
            $ngo->update([
                'suspended' => false,
                'suspension_reason' => null,
                'suspended_at' => null,
            ]);

            return redirect()->route('admin.ngos')->with('success', 'NGO unsuspended successfully.');
        } else {
            $ngo->update([
                'suspended' => true,
                'suspension_reason' => $request->suspension_reason,
                'suspended_at' => now(),
            ]);

            return redirect()->route('admin.ngos')->with('success', 'NGO suspended successfully.');
        }
    }
}
