<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Carbon\Carbon;

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
        $material = Material::create([
            "stock_number" => Material::first()->stock_number + 1,
            "description" => $request->description,
            "unit" => $request->unit,
            "unit_cost" => $request->unit_cost,
            "quantity" => $request->quantity,
            "type" => $request->type,
            "archived_at" => $request->archived ? Carbon::now() : null,
            "archived_by" => $request->archived ? \Auth::id() : null,
            "storage_id" => $request->storage_id,
            "received_at" => $request->received_at
        ]);

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
