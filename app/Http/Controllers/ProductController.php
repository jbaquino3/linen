<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TransactionItem;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::all();

        for($i=0; $i<sizeof($products); $i++) {
            $stock_numbers_available = $products[$i]->stock_numbers;
            $quantity_issued = 0;
            $quantity_condemned = 0;
            $quantity_lost = 0;
            $quantity_returned = 0;

            $issued = TransactionItem::leftJoin((config("app.debug") ? "dev" : "dbo") . ".transactions", "transaction_items.transaction_id", "=", "transactions.id")
                ->where("transaction_items.product_bulk_id", $products[$i]->bulk_id)
                ->get();

            foreach($issued as $issue) {
                if($issue->type == "ISSUANCE") {
                    $stock_numbers_available = array_values(array_diff($stock_numbers_available, $issue->stock_numbers));
                    $quantity_issued += $issue->quantity;
                } else if($issue->type == "CONDEMN") {
                    $quantity_condemned += $issue->quantity;
                } else if($issue->type == "LOST") {
                    $quantity_lost += $issue->quantity;
                } else if($issue->type == "RETURN") {
                    $stock_numbers_available = array_merge($stock_numbers_available, $issue->stock_numbers);
                    $quantity_returned += $issue->quantity;
                }
                
            }

            sort($stock_numbers_available);
            
            $products[$i]->stock_numbers_available = $stock_numbers_available;
            $products[$i]->quantity_issued = $quantity_issued;
            $products[$i]->quantity_condemned = $quantity_condemned;
            $products[$i]->quantity_lost = $quantity_lost;
            $products[$i]->quantity_returned = $quantity_returned;
        }

        return response()->json($products);
    }

    public function read(Request $request, $id) {
        $product = Product::find($id);

        return response()->json($product);
    }

    public function store(Request $request) {
        $product = Product::create([
            "material_stock_number" => $request->material_stock_number,
            "material_quantity" => $request->material_quantity,
            "storage_id" => $request->storage_id,
            "quantity" => $request->quantity,
            "unit" => $request->unit,
            "unit_cost" => $request->unit_cost,
            "create_date" => $request->create_date,
            "name" => $request->name,
            "stock_numbers" => $request->stock_numbers
        ]);

        return response()->json($product->fresh(), 201);
    }

    public function update(Request $request, $id) {
        $product = Product::find($id);
        $updated = $product->update($request->all());

        return response()->json($product->fresh());
    }

    public function delete(Request $request, $id) {
        $product = Product::find($id);
        $product->deleted_by = null;
        $product->saveQuietly();
        $deleted = $product->delete();

        return response()->json($deleted);
    }
}
