<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'unit')->orderBy('id', 'DESC')->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code'  => 'unique:products',
            'product_name'  => 'required',
            'description'   => 'required',
            'image'         => 'nullable|mimes:jpg,jpeg,png',
            'minimum_stock' => 'required|integer',
            'category_id'   => 'required|integer',
            'unit_id'       => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        $product_code = 'PRD-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $product = Product::create([
            'product_code'  => $product_code,
            'product_name'  => $request->product_name,
            'description'   => $request->description,
            'image'         => $imagePath,
            'minimum_stock' => $request->minimum_stock,
            'category_id'   => $request->category_id,
            'unit_id'       => $request->unit_id
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Product added successfully!',
            'data'      => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Product not found!'
            ], 404);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_name'  => 'required',
            'description'   => 'required',
            'image'         => 'mimes:jpg,jpeg,png',
            'minimum_stock' => 'required|integer',
            'category_id'   => 'required|integer',
            'unit_id'       => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');

            if (!$imagePath) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to upload image!'
                ], 500);
            }
        }

        $product->update([
            'product_name'  => $request->product_name,
            'description'   => $request->description,
            'image'         => $imagePath,
            'minimum_stock' => $request->minimum_stock,
            'category_id'   => $request->category_id,
            'unit_id'       => $request->unit_id
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Product updated successfully!',
            'data'      => $product
        ], 201);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found!'
            ], 404);
        }

        $product->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Product deleted successfully!',
            'data'      => $product
        ], 200);
    }
}