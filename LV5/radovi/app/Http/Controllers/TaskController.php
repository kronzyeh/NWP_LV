<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_hr' => 'required',
            'title_en' => 'required',
            'description' => 'required',
            'study_type' => 'required|in:strucni,preddiplomski,diplomski',
        ]);

        auth()->user()->tasks()->create($request->all());
        return redirect()->route('tasks.index');
    }

}
