<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index(Request $request) {
        $materials = Material::all();

        return response()->json($materials);
    }
}
