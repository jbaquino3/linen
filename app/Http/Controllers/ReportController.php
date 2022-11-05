<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

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

    public function store(Request $request) {
        $report = Report::create($request->all());

        return response()->json($report->fresh(), 201);
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
