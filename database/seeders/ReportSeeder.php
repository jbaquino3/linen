<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\User;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportItem::truncate();
        Report::truncate();
        
        for($i=5; $i<=10; $i++) {
            $locations = Location::all();

            foreach($locations as $location) {
                $items = \DB::select("exec linen." . (config("app.debug") ? "dev." : "dbo.") . "generateReport ?, ?, ?", [
                    "2022-" . $i . "-01",
                    "2022-" . $i+1 . "-01",
                    $location->id
                ]);

                if(sizeof($items) > 0) {
                    $user = User::where("location_id", $location->id)->first();
                    $report = Report::create([
                        "location_id" => $location->id,
                        "location_name" => $location->name,
                        "generated_by" => $user ? $user->employeeid : null,
                        "month" => date("F", strtotime(date("2022-" . $i . "-01"))),
                        "year" => "2022"
                    ]);

                    foreach($items as $item) {
                        $item->report_id = $report->id;
                        ReportItem::create(json_decode(json_encode($item), true));
                    }
                }
            }
        }
    }
}
