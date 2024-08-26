<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'DESC')->paginate(10);
        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No data to display!'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'  => 'required|unique:users',
            'password'  => 'required|min:4',
            'role_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'User added successfully!',
            'data'      => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'user not found!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'  => 'required|unique:users,username,' . $id,
            'password'  => 'required|min:4',
            'role_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'name'      => $request->name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'User updated successfully!',
            'data'      => $user
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found!'
            ], 404);
        }

        $user->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'User deleted successfully!',
            'data'      => $user
        ], 200);
    }
}