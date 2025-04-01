<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users (for project owner dropdown).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all users, excluding the current user if needed
        $users = User::all();

        return response()->json($users);
    }
}
