<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('id', 'DESC')->paginate(10);
        if ($units->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $units
        ], 201);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Unit::create([
            'unit'  => $request->unit
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Unit added successfully!',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json([
                'status'  => 'error',
                'message' => 'unit not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'unit'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $unit->update([
            'unit'  => $request->unit
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Unit updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unit not found!'
            ], 404);
        }

        $unit->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Unit deleted successfully!',
        ], 200);
    }
}
