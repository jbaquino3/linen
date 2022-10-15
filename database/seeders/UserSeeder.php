<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Location;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::truncate();
        $users = \DB::table("nora.paul.linen_users")->get();

        foreach($users as $user) {
            // find location
            $location = null;
            if($user->ward_id) {
                $ward = \DB::table("nora.paul.linen_ward")->where("id", $user->ward_id)->first();
                $location = Location::where([
                    "type" => "WARD",
                    "name" => $ward->ward_name
                ])->first();
            } else if($user->office_id) {
                $office = \DB::table("nora.paul.linen_office")->where("id", $user->office_id)->first();
                $location = Location::where([
                    "type" => "OFFICE",
                    "name" => $office->office_name
                ])->first();
            }

            User::create([
                "employeeid" => $user->employee_id,
                "name" => $user->name,
                "email" => $user->username,
                "password" => $user->password ?? \Hash::make(date("Y-m-d")),
                "location_id" => $location ? $location->id : null,
                "role" => strtoupper($user->role_name)
            ]);
        }
    }
}
