<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'DESC')->paginate(10);
        if ($suppliers->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $suppliers
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier'  => 'required',
            'address'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create([
            'supplier'  => $request->supplier,
            'address'   => $request->address
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'supplier added successfully!',
            'data'      => $supplier
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Supplier not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'supplier'  => 'required',
            'address'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->update([
            'supplier'  => $request->supplier,
            'address'   => $request->address
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Supplier updated successfully!',
            'data'      => $supplier
        ], 200);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Supplier not found!'
            ], 404);
        }

        $supplier->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Supplier deleted successfully!',
            'data'      => $supplier
        ], 201);
    }
}