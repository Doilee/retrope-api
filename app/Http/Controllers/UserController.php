<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me()
    {
        return auth()->user();
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $data = $request->only(['name', 'email']);

        $user = auth()->user();

        $user->fill($data);

        $user->save();

        return response()->json([
            'message' => 'Edit successful!',
            'user' => $user
        ]);
    }
}
