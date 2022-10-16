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

    public function read(Request $request, $id) {
        $material = Material::find($id);

        return response()->json($material);
    }

    public function store(Request $request) {
        $material = Material::create($request->all());

        return response()->json($material, 201);
    }

    public function update(Request $request, $id) {
        $material = Material::find($id);
        $updated = $material->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $material = Material::find($id);
        $material->deleted_by = null;
        $material->saveQuietly();
        $deleted = $material->delete();

        return response()->json($deleted);
    }
}
