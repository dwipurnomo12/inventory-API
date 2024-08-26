<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'DESC')->paginate(10);
        if ($customers->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $customers
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer'  => 'required',
            'address'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create([
            'customer'  => $request->customer,
            'address'   => $request->address
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Customer added successfully!',
            'data'      => $customer
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer'  => 'required',
            'address'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update([
            'customer'  => $request->customer,
            'address'   => $request->address
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Customer updated successfully!',
            'data'      => $customer
        ], 201);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found!'
            ], 404);
        }

        $customer->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Customer deleted successfully!',
            'data'      => $customer
        ], 200);
    }
}