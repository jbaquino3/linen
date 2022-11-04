<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\RequestRemark;
use App\Models\User;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function index(Request $request) {
        $requests = RequestModel::all();

        for($i=0; $i<sizeof($requests); $i++) {
            $requests[$i] = $this->addAttributes($requests[$i]);
        }

        return response()->json($requests);
    }

    private function addAttributes(RequestModel $request) {
        $user = User::find($request->requested_by);
        if($user) {
            $request->location_id = $user->location_id;
            $request->location_name = $user->location_name;
            $request->location_type = $user->location_type;
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
            "requested_at" => Carbon::now(),
            "requested_by" => "2010743",
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
            "remarks" => $request->remarks,
            "remarks_by" => $request->remarks_by
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
}
