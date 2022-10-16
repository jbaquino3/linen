<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;

class RequestController extends Controller
{
    public function index(Request $request) {
        $requests = Request::all();

        return response()->json($requests);
    }
}
