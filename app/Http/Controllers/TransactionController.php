<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Material;
use App\Models\IssuedProduct;
use App\Models\Request as RequestModel;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $transactions = [];

        if($request->user()->role == "USER") {
            $transactions = Transaction::where("location_id", $request->user()->location_id)->get();
        } else if ($request->user()->role == "ADMIN" || $request->user()->role == "SUPER_ADMIN") {
            $transactions = Transaction::all();
        }

        return response()->json($transactions);
    }

    public function issued(Request $request) {
        $issued = [];
        $returned = [];
        if($request->user()->role == "USER") {
            $issued = IssuedProduct::where("location_id", $request->user()->location_id)->where("type", "ISSUANCE")->get()->toArray();
            $returned = IssuedProduct::where("location_id", $request->user()->location_id)->where("type", "<>", "ISSUANCE")->get();
        } else if ($request->user()->role == "ADMIN" || $request->user()->role == "SUPER_ADMIN") {
            $issued = IssuedProduct::where("type", "ISSUANCE")->get()->toArray();
            $returned = IssuedProduct::where("type", "<>", "ISSUANCE")->get();
        }

        foreach($returned as $ret) {
            $index = -1;
            for($i=0; $i<sizeof($issued); $i++) {
                if($issued[$i]['bulk_id'] == $ret->bulk_id && $issued[$i]['location_id'] == $ret->location_id) {
                    $index = $i;
                    break;
                }
            }
            
            $issued[$i]['quantity'] = sizeof(array_values(array_diff($issued[$i]['stock_numbers'], $ret->stock_numbers)));
            $issued[$i]['stock_numbers'] = json_encode(array_values(array_diff($issued[$i]['stock_numbers'], $ret->stock_numbers)));

            if($issued[$i]['quantity'] == 0) {
                array_splice($issued, $i, 1);
            }
        }

        for($i=0; $i<sizeof($issued); $i++) {
            $material = Material::find($issued[$i]['material_stock_number']);

            $issued[$i]['material_name'] = $material->description;
        }

        return response()->json($issued);
    }

    public function read(Request $request, $id) {
        $transaction = Transaction::find($id);

        if($request->user()->role == "USER" && $transaction->location_id != $request->user()->location_id) {
            return response()->json("You do not have access to this resource.", 403);
        }

        return response()->json($transaction);
    }

    public function store(Request $request) {
        if($request->user()->role == "USER") {
            return response()->json("You do not have access to create a transaction.", 403);
        }

        $transaction = Transaction::create([
            "location_id" => $request->location_id,
            "type" => $request->type,
            "is_final" => $request->type != "ISSUANCE",
            "transaction_date" => date("Y-m-d")
        ]);

        if($request->type != "ISSUANCE") {
            $item = TransactionItem::create([
                "transaction_id" => $transaction->id,
                "product_bulk_id" => $request->product_bulk_id,
                "stock_numbers" => $request->stock_numbers,
                "issuance_additional_cost" => 0
            ]);
        }

        return response()->json($transaction->fresh(), 201);
    }

    public function addItem(Request $request, $id) {
        if($request->user()->role == "USER") {
            return response()->json("You do not have access to create a transaction.", 403);
        }

        $transaction_item = TransactionItem::create([
            "transaction_id" => $id,
            "product_bulk_id" => $request->product_bulk_id,
            "stock_numbers" => $request->stock_numbers,
            "issuance_additional_cost" => $request->issuance_additional_cost,
            "quantity" => $request->quantity,
        ]);

        return response()->json($transaction_item, 201);
    }

    public function update(Request $request, $id) {
        if($request->user()->role == "USER") {
            return response()->json("You do not have access to update a transaction.", 403);
        }

        $transaction = Transaction::find($id);
        $updated = $transaction->update($request->all());

        return response()->json($updated);
    }

    public function finalize(Request $request, $id) {
        if($request->user()->role == "USER") {
            return response()->json("You do not have access to update a transaction.", 403);
        }

        $transaction = Transaction::find($id);

        $updated = $transaction->update([
            "is_final" => true
        ]);

        $requests_update = RequestModel::where("location_id", $transaction->location_id)
            ->whereNotNull("processed_at")
            ->whereNull("prepared_at")
            ->whereNull("cancelled_at")
            ->update([
                "transaction_id" => $transaction->id,
                "prepared_at" => Carbon::now(),
                "prepared_by" => $request->user() ? $request->user()->employeeid : null
            ]);

        return response()->json($transaction->fresh());
    }

    public function delete(Request $request, $id) {
        if($request->user()->role == "USER") {
            return response()->json("You do not have access to delete a transaction.", 403);
        }

        TransactionItem::where("transaction_id", $id)->delete();
        $transaction = Transaction::find($id);
        $deleted = $transaction->delete();

        return response()->json($deleted);
    }
}
