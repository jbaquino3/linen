<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockRoom;

class StockRoomController extends Controller
{
    public function index(Request $request) {
        $stock_rooms = StockRoom::all();

        return response()->json($stock_rooms);
    }
}
