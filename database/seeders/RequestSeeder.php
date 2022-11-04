<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\RequestRemark;
use App\Models\Location;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Request::truncate();
        RequestRemark::truncate();
        
        $requests = \DB::table("nora.paul.linen_requests")->whereNull("deleted_at")->get();

        foreach($requests as $request) {
            $location = null;
            if($request->ward_id) {
                $ward = \DB::table("nora.paul.linen_ward")->where("id", $request->ward_id)->first();
                $location = Location::where([
                    "type" => "WARD",
                    "name" => $ward->ward_name
                ])->first();
            } else if($request->office_id) {
                $office = \DB::table("nora.paul.linen_office")->where("id", $request->office_id)->first();
                $location = Location::where([
                    "type" => "OFFICE",
                    "name" => $office->office_name
                ])->first();
            }

            $request_new = Request::create([
                "name" => $request->product_name_request,
                "quantity" => $request->product_quantity_request,
                "unit" => "PIECE",
                "location_id" => $location->id,
                "requested_by" => $request->employee_id,
                "requested_at" => $request->created_at,
                "processed_by" => $request->status != 5 && $request->status > 1 ? $request->processed_by_emp_id : null,
                "processed_at" => $request->status != 5 && $request->status > 1 ? $request->processed_at : null,
                "prepared_by" => $request->status != 5 && $request->status > 2 ? $request->processed_by_emp_id : null,
                "prepared_at" => $request->status != 5 && $request->status > 2 ? $request->processed_at : null,
                "issued_by" => $request->status != 5 && $request->status > 3 ? $request->processed_by_emp_id : null,
                "issued_at" => $request->status != 5 && $request->status > 3 ? $request->processed_at : null,
                "cancelled_by" => $request->status == 5 && $request->comments ? $request->processed_by_emp_id : null,
                "cancelled_at" => $request->status == 5 && $request->comments ? $request->processed_at : null,
                "deleted_at" => $request->status == 5 && !$request->comments ? $request->updated_at : null
            ]);

            if($request->comments) {
                RequestRemark::create([
                    "remarks" => $request->comments,
                    "remarks_by" => $request->processed_by_emp_id,
                    "request_id" => $request_new->id
                ]);
            }
        }
    }
}
