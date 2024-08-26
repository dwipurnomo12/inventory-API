<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOut;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductOutController extends Controller
{
    public function index()
    {
        $productsOut = ProductOut::with('customer')->orderBy('id', 'DESC')->get();
        if ($productsOut->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $productsOut
        ], 200);
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_code'  => 'unique:products_out',
            'date'              => 'required|date',
            'product_name'      => 'required',
            'stock_out'         => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::where('product_name', $request->product_name)->first();

                    if (!$product) {
                        $fail('Product not found in the inventory.');
                    } elseif ($value > $product->stock) {
                        $fail('Stock is not enough!');
                    }
                },
            ],
            'customer_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction_code = 'TRX-OUT-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $productOut = ProductOut::create([
            'transaction_code'  => $transaction_code,
            'date'              => $request->date,
            'product_name'      => $request->product_name,
            'stock_out'         => $request->stock_out,
            'customer_id'       => $request->customer_id
        ]);

        if ($productOut) {
            $product = Product::where('product_name', $request->product_name)->first();
            if ($product) {
                $product->stock -= $request->stock_out;
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
            'message'   => 'Product out added successfully!',
            'data'      => $productOut
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $productOut = ProductOut::find($id);
        if (!$productOut) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Product out record not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'date'              => 'required|date',
            'product_name'      => 'required',
            'stock_out'         => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request, $productOut) {
                    $product = Product::where('product_name', $request->product_name)->first();

                    if (!$product) {
                        $fail('Product not found in the inventory.');
                    } elseif ($request->product_name === $productOut->product_name && ($value > ($product->stock + $productOut->stock_out))) {
                        $fail('Stock is not enough!');
                    } elseif ($request->product_name !== $productOut->product_name && $value > $product->stock) {
                        $fail('Stock is not enough!');
                    }
                },
            ],
            'customer_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $oldProduct = Product::where('product_name', $productOut->product_name)->first();
            if ($oldProduct) {
                $oldProduct->stock += $productOut->stock_out;
                $oldProduct->save();
            }

            $productOut->update([
                'transaction_code'  => $request->transaction_code,
                'date'              => $request->date,
                'product_name'      => $request->product_name,
                'stock_out'         => $request->stock_out,
                'customer_id'       => $request->customer_id
            ]);

            $newProduct = Product::where('product_name', $request->product_name)->first();
            if ($newProduct) {
                $newProduct->stock -= $request->stock_out;
                $newProduct->save();
            } else {
                DB::rollBack();
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'New product not found in the inventory.',
                ], 404);
            }

            DB::commit();

            return response()->json([
                'status'    => 'success',
                'message'   => 'Product out updated successfully!',
                'data'      => $productOut
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'    => 'error',
                'message'   => 'Error updating product out: ' . $e->getMessage(),
            ], 500);
        }
    }
}