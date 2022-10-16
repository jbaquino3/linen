<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    public function index(Request $request) {
        $requests = RequestModel::all();

        return response()->json($requests);
    }

    public function read(Request $request, $id) {
        $request = RequestModel::find($id);

        return response()->json($request);
    }

    public function store(Request $request) {
        $request = RequestModel::create($request->all());

        return response()->json($request, 201);
    }

    public function update(Request $request, $id) {
        $request = RequestModel::find($id);
        $updated = $request->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $request = RequestModel::find($id);
        $deleted = $request->delete();

        return response()->json($deleted);
    }
}
