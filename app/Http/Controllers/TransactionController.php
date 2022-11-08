<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\IssuedProduct;
use App\Models\Request as RequestModel;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $transactions = Transaction::all();

        return response()->json($transactions);
    }

    public function issued(Request $request) {
        $issued = [];
        if($request->user()->role == "USER") {
            $issued = IssuedProduct::where("location_id", $request->user()->location_id)->get();
        } else if ($request->user()->role == "ADMIN" || $request->user()->role == "SUPER_ADMIN") {
            $issued = IssuedProduct::all();
        }

        return response()->json($issued);
    }

    public function read(Request $request, $id) {
        $transaction = Transaction::find($id);

        return response()->json($transaction);
    }

    public function store(Request $request) {
        $transaction = Transaction::create([
            "location_id" => $request->location_id,
            "type" => $request->type,
            "is_final" => false,
            "transaction_date" => date("Y-m-d")
        ]);

        return response()->json($transaction->fresh(), 201);
    }

    public function addItem(Request $request, $id) {
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
        $transaction = Transaction::find($id);
        $updated = $transaction->update($request->all());

        return response()->json($updated);
    }

    public function finalize(Request $request, $id) {
        $transaction = Transaction::find($id);

        $updated = $transaction->update([
            "is_final" => true
        ]);

        $requests_update = RequestModel::where("location_id", $transaction->location_id)
            ->whereNotNull("prepared_at")
            ->whereNull("issued_at")
            ->whereNull("cancelled_at")
            ->update([
                "transaction_id" => $transaction->id,
                "issued_at" => Carbon::now(),
                "issued_by" => $request->user() ? $request->user()->employeeid : null
            ]);

        return response()->json($transaction->fresh());
    }

    public function delete(Request $request, $id) {
        TransactionItem::where("transaction_id", $id)->delete();
        $transaction = Transaction::find($id);
        $deleted = $transaction->delete();

        return response()->json($deleted);
    }
}
