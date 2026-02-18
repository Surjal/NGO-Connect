<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use Illuminate\Http\Request;

class NgoSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Ngo::query();

        if ($request->filled('name')) {
                $query->where('ngo_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory', 'like', '%' . $request->subcategory . '%');
        }

        $ngos = $query->paginate(10);

        $categories = Ngo::distinct()->pluck('category')->filter();
        $subcategories = Ngo::distinct()->pluck('subcategory')->filter();

        return view('people.ngo.search', compact('ngos', 'categories', 'subcategories', 'request'));
    }
}
