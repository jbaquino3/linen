<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::all();

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
        ]);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id) {
        $product = Product::find($id);
        $updated = $product->update([
            "material_stock_number" => $request->material_stock_number,
            "material_quantity" => $request->material_quantity,
            "storage_id" => $request->storage_id,
            "quantity" => $request->quantity,
            "unit" => $request->unit,
            "unit_cost" => $request->unit_cost,
            "create_date" => $request->create_date,
            "name" => $request->name,
        ]);

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $product = Product::find($id);
        $product->deleted_by = null;
        $product->saveQuietly();
        $deleted = $product->delete();

        return response()->json($deleted);
    }
}
