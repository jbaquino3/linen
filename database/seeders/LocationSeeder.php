<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run() {
        Location::truncate();

        $wards = \DB::table("nora.paul.linen_ward")->get();

        foreach($wards as $ward) {
            Location::create([
                "name" => $ward->ward_name,
                "type" => "WARD"
            ]);
        }

        $offices = \DB::table("nora.paul.linen_office")->get();

        foreach($offices as $office) {
            Location::create([
                "name" => $office->office_name,
                "type" => "OFFICE"
            ]);
        }
    }
}
