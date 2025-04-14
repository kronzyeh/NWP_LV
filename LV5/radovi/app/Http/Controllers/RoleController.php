<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('roles.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('roles.index')->with('success', 'Uloga je ažurirana.');
    }
}
