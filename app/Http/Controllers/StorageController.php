<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;

class StorageController extends Controller
{
    public function index(Request $request) {
        $storages = Storage::all();

        return response()->json($storages);
    }

    public function read(Request $request, $id) {
        $storage = Storage::find($id);

        return response()->json($storage);
    }

    public function store(Request $request) {
        $storage = Storage::create($request->all());

        return response()->json($storage, 201);
    }

    public function update(Request $request, $id) {
        $storage = Storage::find($id);
        $updated = $storage->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $storage = Storage::find($id);
        $storage->deleted_by = null;
        $storage->saveQuietly();
        $deleted = $storage->delete();

        return response()->json($deleted);
    }
}
