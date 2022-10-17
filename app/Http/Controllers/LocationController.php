<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index(Request $request) {
        $locations = Location::all();

        return response()->json($locations);
    }

    public function read(Request $request, $id) {
        $location = Location::find($id);

        return response()->json($location);
    }

    public function store(Request $request) {
        $location = Location::create($request->all());

        return response()->json($location->fresh(), 201);
    }

    public function update(Request $request, $id) {
        $location = Location::find($id);
        $updated = $location->update($request->all());

        return response()->json($location->fresh());
    }

    public function delete(Request $request, $id) {
        $location = Location::find($id);
        $location->deleted_by = null;
        $location->saveQuietly();
        $deleted = $location->delete();

        return response()->json($deleted);
    }
}
