<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\RequestRemark;
use App\Models\Location;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function index(Request $request) {
        $requests = [];
        if($request->user()->role == "USER") {
            $requests = RequestModel::where("location_id", $request->user()->location_id)->get();
        } else if ($request->user()->role == "ADMIN" || $request->user()->role == "SUPER_ADMIN") {
            $requests = RequestModel::all();
        }

        for($i=0; $i<sizeof($requests); $i++) {
            $requests[$i] = $this->addAttributes($requests[$i]);
        }

        return response()->json($requests);
    }

    public function stats(Request $request) {
        $pending = [];
        $processing = [];
        $ready = [];
        if($request->user()->role == "USER") {
            $pending = RequestModel::where("location_id", $request->user()->location_id)->whereNull("processed_at")->whereNull("cancelled_at")->count();
            $processing = RequestModel::where("location_id", $request->user()->location_id)->whereNotNull("processed_at")->whereNull("prepared_at")->count();
            $ready = RequestModel::where("location_id", $request->user()->location_id)->whereNotNull("prepared_at")->whereNull("issued_at")->count();
        } else if ($request->user()->role == "ADMIN" || $request->user()->role == "SUPER_ADMIN") {
            $pending = RequestModel::whereNull("processed_at")->whereNull("cancelled_at")->count();
            $processing = RequestModel::whereNotNull("processed_at")->whereNull("prepared_at")->count();
            $ready = RequestModel::whereNotNull("prepared_at")->whereNull("issued_at")->count();
        }

        return response()->json([
            "pending" => $pending,
            "processing" => $processing,
            "ready" => $ready
        ]);
    }

    private function addAttributes(RequestModel $request) {
        $location = Location::find($request->location_id);
        if($location) {
            $request->location_name = $location->name;
            $request->location_type = $location->type;
        }

        return $request;
    }

    public function read(Request $request, $id) {
        $request = RequestModel::find($id);

        return response()->json($request);
    }

    public function store(Request $request) {
        $request_model = RequestModel::create([
            "name" => $request->name,
            "quantity" => $request->quantity,
            "unit" => $request->unit,
            "processed_by" => null,
            "prepared_by" => null,
            "issued_by" => null,
            "cancelled_by" => null
        ]);

        return response()->json($this->addAttributes($request_model->fresh()), 201);
    }

    public function createRemark(Request $request) {
        $remark = RequestRemark::create([
            "request_id" => $request->request_id,
            "remarks" => $request->remarks
        ]);

        return response()->json($remark, 201);
    }

    public function update(Request $request, $id) {
        $request_model = RequestModel::find($id);
        $updated = $request_model->update($request->all());

        return response()->json($updated);
    }

    public function delete(Request $request, $id) {
        $request_model = RequestModel::find($id);
        if($request_model->requested_by == \Auth::id()) {
            $deleted = $request_model->delete();
        } else {
            $request_model->cancelled_at = Carbon::now();
            $request_model->cancelled_by = \Auth::id();
            $request_model->saveQuietly();
        }

        return response()->json(true);
    }

    public function processRequest($id) {
        $request = RequestModel::find($id);
        $updated = $request->update([
            "processed_at" => Carbon::now(),
            "processed_by" => \Auth::id()
        ]);

        return response()->json($updated);
    }

    public function readyRequest($id) {
        $request = RequestModel::find($id);
        $updated = $request->update([
            "prepared_at" => Carbon::now(),
            "prepared_by" => \Auth::id()
        ]);

        return response()->json($updated);
    }

    public function issueRequest($id) {
        $request = RequestModel::find($id);
        $updated = $request->update([
            "issued_at" => Carbon::now(),
            "issued_by" => \Auth::id()
        ]);

        return response()->json($updated);
    }
}
