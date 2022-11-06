<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Report;
use App\Models\ReportItem;

class ReportController extends Controller
{
    public function index(Request $request) {
        $reports = Report::all();

        return response()->json($reports);
    }

    public function read(Request $request, $id) {
        $report = Report::find($id);

        return response()->json($report);
    }

    public function generate(Request $request) {
        // location_id, month, year
        $start = date("Y-m-01", strtotime($request->month . " " . $request->year));
        $end = date("Y-m-d", strtotime("+1 month", strtotime($start)));

        $items = \DB::select("exec linen." . (config("app.debug") ? "dev." : "dbo.") . "generateReport ?, ?, ?", [
            $start,
            $end,
            $request->location_id
        ]);

        if(sizeof($items) > 0) {
            $location = Location::find($request->location_id);

            $report = Report::updateOrCreate(
                [
                    "location_id" => $location->id,
                    "month" => $request->month,
                    "year" => $request->year
                ],
                [
                    "location_name" => $location->name,
                    "generated_by" => $request->user() ? $request->user()->employeeid : null
                ]
            );
            
            ReportItem::where("report_id", $report->id)->delete();

            foreach($items as $item) {
                $item->report_id = $report->id;
                ReportItem::create(json_decode(json_encode($item), true));
            }

            return response()->json($report->fresh(), 201);
        }

        return response()->json(null);
    }

    public function update(Request $request, $id) {
        $report = Report::find($id);
        $updated = $report->update($request->all());

        return response()->json($report->fresh());
    }

    public function delete(Request $request, $id) {
        $report = Report::find($id);
        $report->deleted_by = null;
        $report->saveQuietly();
        $deleted = $report->delete();

        return response()->json($deleted);
    }
}
