<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductStokReportController extends Controller
{
    public function filterDataProductStock(Request $request)
    {
        $selectedproduct = $request->input('select_product');

        if ($selectedproduct == 'all') {
            $products = Product::paginate(10);
        } elseif ($selectedproduct == 'minimum') {
            $products = Product::where('stock', '<=', 10)->get();
        } elseif ($selectedproduct == 'out_of_stock') {
            $products = Product::where('stock', 0)->get();
        } else {
            $products = Product::paginate(10);
        }

        return response()->json($products);
    }
}