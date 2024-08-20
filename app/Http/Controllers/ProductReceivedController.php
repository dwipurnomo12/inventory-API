<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductReceived;
use Illuminate\Support\Facades\Validator;

class ProductReceivedController extends Controller
{
    public function index()
    {
        $productsReceived = ProductReceived::with('supplier')->orderBy('id', 'DESC')->get();
        if ($productsReceived->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $productsReceived
        ], 201);
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_code'  => 'required|unique:products_received',
            'date'              => 'required|date',
            'product_name'      => 'required',
            'stock_in'          => 'required|numeric',
            'supplier_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $productReceived = ProductReceived::create([
            'transaction_code'  => $request->transaction_code,
            'date'              => $request->date,
            'product_name'      => $request->product_name,
            'stock_in'          => $request->stock_in,
            'supplier_id'       => $request->supplier_id
        ]);

        if ($productReceived) {
            $product = Product::where('product_name', $request->product_name)->first();
            if ($product) {
                $product->stock += $request->stock_in;
                $product->save();
            } else {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Product not found in the inventory.',
                ], 404);
            }
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Product received added successfully!',
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $productReceived = ProductReceived::find($id);
        if (!$productReceived) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Product received record not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'transaction_code'  => 'required|unique:products_received,transaction_code,' . $id,
            'date'              => 'required|date',
            'product_name'      => 'required',
            'stock_in'          => 'required|numeric',
            'supplier_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $oldProductName = $productReceived->product_name;
        $oldStockIn     = $productReceived->stock_in;

        $productReceived->update([
            'transaction_code'  => $request->transaction_code,
            'date'              => $request->date,
            'product_name'      => $request->product_name,
            'stock_in'          => $request->stock_in,
            'supplier_id'       => $request->supplier_id
        ]);

        $oldProduct = Product::where('product_name', $oldProductName)->first();
        if ($oldProduct) {
            $oldProduct->stock -= $oldStockIn;
            $oldProduct->save();
        }

        $newProduct = Product::where('product_name', $request->product_name)->first();
        if ($newProduct) {
            $newProduct->stock += $request->stock_in;
            $newProduct->save();
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'New product not found in the inventory.',
            ], 404);
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Product received updated successfully!',
        ], 200);
    }
}