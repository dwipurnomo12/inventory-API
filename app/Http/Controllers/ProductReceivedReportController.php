<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReceived;
use Barryvdh\DomPDF\Facade\pdf as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductReceivedReportController extends Controller
{
    public function filterDataProductReceived(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if (empty($startDate) || empty($endDate)) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'You must select both start date and end date.',
            ], 400);
        }

        $productReceived = ProductReceived::with('supplier');

        if ($startDate && $endDate) {
            $productReceived->whereBetween('date', [$startDate, $endDate]);
        }

        $data = $productReceived->paginate(10);
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $data
        ], 200);
    }
}