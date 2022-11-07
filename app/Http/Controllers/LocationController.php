<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

class LocationController extends Controller
{
    public function index(Request $request) {
        $locations = Location::all();

        for($i=0; $i<sizeof($locations); $i++) {
            $locations[$i] = $this->addAttributes($locations[$i]);
        }

        return response()->json($locations);
    }

    private function addAttributes(Location $location) {        
        $transactions = Transaction::where('location_id', $location->id)->get();

        $location->transaction_count = sizeof($transactions);

        return $location;
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
