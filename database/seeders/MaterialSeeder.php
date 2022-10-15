<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\User;
use App\Models\StockRoom;
use App\Models\Storage;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::truncate();
        $materials = \DB::table("nora.paul.linen_raw_materials")->whereNull("deleted_at")->get();

        foreach($materials as $material) {
            $stock_room = \DB::table("nora.paul.linen_stock_rooms")
                ->where("id", $material->stock_room)->first();
            $storage = \DB::table("nora.paul.linen_storage")
                ->where("id", $material->storage_room)->first();

            $new_stock_room = StockRoom::where("name", $stock_room->stock_room)->first();
            $new_storage = Storage::where([
                "stock_room_id" => $new_stock_room->id,
                "name" => $storage->storage_name
            ])->first();

            Material::create([
                "stock_number" => $material->stock_number,
                "description" => $material->description,
                "unit" => $material->unit,
                "unit_cost" => $material->unit_cost,
                "quantity" => $material->quantity,
                "type" => $material->type,
                "archived_at" => $material->is_archived == 1 ? $material->created_at : null,
                "archived_at" => $material->is_archived == 1 ? $material->created_at : null,
                "received_at" => $material->received_at,
                "storage_id" => $new_storage->id
            ]);
        }
    }
}
