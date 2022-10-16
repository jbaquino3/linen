<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $transactions = Transaction::all();

        return response()->json($transactions);
    }
}
