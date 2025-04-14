<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function apply($taskId)
    {
        $user = Auth::user();

        $alreadyApplied = Application::where('user_id', $user->id)
                                     ->where('task_id', $taskId)
                                     ->exists();

        if ($alreadyApplied) {
            return back()->with('warning', 'Već ste se prijavili na ovaj zadatak.');
        }

        Application::create([
            'user_id' => $user->id,
            'task_id' => $taskId,
            'accepted' => false, 
        ]);

        return redirect()->back()->with('success', 'Uspješno ste se prijavili na zadatak.');
    }

    public function myApplicants()
    {
        $user = Auth::user();

        $applications = Application::whereHas('task', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'task'])->get();

        return view('applications.my-applicants', compact('applications'));
    }

    public function accept(Application $application)
    {
        $application->accepted = true;
        $application->save();

        return redirect()->back()->with('success', 'Student je prihvaćen.');
    }
}
