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

    public function read(Request $request, $id) {
        $stock_room = StockRoom::find($id);

        return response()->json($stock_room);
    }

    public function store(Request $request) {
        $stock_room = StockRoom::create($request->all());

        return response()->json($stock_room, 201);
    }

    public function update(Request $request, $id) {
        $stock_room = StockRoom::find($id);
        $updated = $stock_room->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $stock_room = StockRoom::find($id);
        $stock_room->deleted_by = null;
        $stock_room->saveQuietly();
        $deleted = $stock_room->delete();

        return response()->json($deleted);
    }
}
