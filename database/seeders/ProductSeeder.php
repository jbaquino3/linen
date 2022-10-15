<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockRoom;
use App\Models\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        $product_bulk_ids = \DB::table("nora.paul.linen_products")
            ->distinct("product_bulk_id")
            ->whereNull("deleted_at")
            ->pluck("product_bulk_id");

        foreach($product_bulk_ids as $bulk_id) {
            $product =  \DB::table("nora.paul.linen_products")
                ->where("product_bulk_id", $bulk_id)
                ->first();
            $stock_room = \DB::table("nora.paul.linen_stock_rooms")
                ->where("id", $product->stock_room_id)->first();
            $storage = \DB::table("nora.paul.linen_storage")
                ->where("id", $product->storage_room_id)->first();

            $new_stock_room = StockRoom::where("name", $stock_room->stock_room)->first();
            $new_storage = Storage::where([
                "stock_room_id" => $new_stock_room->id,
                "name" => $storage->storage_name
            ])->first();

            // get stock_number
            $stock_ids = \DB::table("nora.paul.linen_products")->whereNull("deleted_at")->where("product_bulk_id", $bulk_id)->orderBy("product_stock_id")->pluck("product_stock_id");
            $stock_numbers = [];
            foreach($stock_ids as $stock_id) {
                array_push($stock_numbers, intval(explode("-",$stock_id)[1]));
            }

            $created_product = Product::create([
                "bulk_id" => $bulk_id,
                "material_stock_number" => $product->raw_material_stock_number,
                "material_quantity" => $product->material_used_quantity,
                "storage_id" => $new_storage->id,
                "stock_numbers" => $stock_numbers,
                "name" => $product->product_name,
                "unit" => $product->product_unit,
                "unit_cost" => $product->product_unit_cost / (1 + ($product->issuance_additional_cost/100)),
                "quantity" => $product->product_quantity
            ]);
        }
    }
}
