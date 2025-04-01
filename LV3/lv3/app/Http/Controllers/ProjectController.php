<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $projectsAsLeader = $user->projectsAsLeader ?: collect();
    $projectsAsMember = $user->projectsAsMember ?: collect();

    $projects = $projectsAsLeader->merge($projectsAsMember);

    return response()->json($projects);
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'owner_id' => 'required|exists:users,id'
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'owner_id' => $request->owner_id
        ]);

        return response()->json($project);
    }

    public function addMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($project->owner_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $project->members()->attach($request->user_id);
        return response()->json(['message' => 'Member added successfully']);
    }

    

    public function show($id)
    {
        $project = Project::with('members')->findOrFail($id);
        $users = User::whereNotIn('id', $project->members->pluck('id'))
                     ->where('id', '!=', $project->owner_id) 
                     ->get();
    
        return response()->json([
            'project' => $project,
            'users' => $users,
        ]);
    }

}

