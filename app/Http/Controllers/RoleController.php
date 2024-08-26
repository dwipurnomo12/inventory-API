<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        if ($roles->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $roles
        ], 200);
    }
}