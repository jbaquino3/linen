<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $transactions = Transaction::all();

        return response()->json($transactions);
    }

    public function read(Request $request, $id) {
        $transaction = Transaction::find($id);

        return response()->json($transaction);
    }

    public function store(Request $request) {
        $transaction = Transaction::create($request->all());

        return response()->json($transaction, 201);
    }

    public function update(Request $request, $id) {
        $transaction = Transaction::find($id);
        $updated = $transaction->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        TransactionItem::where("transaction_id", $id)->delete();
        $transaction = Transaction::find($id);
        $deleted = $transaction->delete();

        return response()->json($deleted);
    }
}
