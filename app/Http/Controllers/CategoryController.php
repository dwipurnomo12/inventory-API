<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'category'  => $request->category
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Category added successfully!',
            'data'      => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update([
            'category'  => $request->category
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Category updated successfully!',
            'data'      => $category
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found!'
            ], 404);
        }

        $category->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Category deleted successfully!',
            'data'      => $category
        ], 200);
    }
}