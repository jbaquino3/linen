<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::all();

        return response()->json($users);
    }

    public function read(Request $request, $id) {
        $user = User::find($id);

        return response()->json($user);
    }

    public function store(Request $request) {
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $updated = $user->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $user = User::find($id);
        $deleted = $user->delete();

        return response()->json($deleted);
    }
}
