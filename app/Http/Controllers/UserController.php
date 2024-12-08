<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->query('name');

        $users = User::where('name', 'like', "%$name%")->get();

        return response()->json($users);
    }
}
