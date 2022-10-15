<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockRoom;
use App\Models\Storage;

class StockRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Storage::truncate();
        StockRoom::truncate();

        $stockrooms = \DB::table("nora.paul.linen_stock_rooms")->get();

        for($i=0; $i<sizeof($stockrooms); $i++) {
            $model = StockRoom::create([
                "name" => $stockrooms[$i]->stock_room
            ]);

            $storages = \DB::table("nora.paul.linen_storage")
                ->where("stock_room_id", $stockrooms[$i]->id)
                ->get();

            foreach($storages as $storage) {
                Storage::create([
                    "stock_room_id" => $model->id,
                    "name" => $storage->storage_name
                ]);
            }
        }
    }
}
