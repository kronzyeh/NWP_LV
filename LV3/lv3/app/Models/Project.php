<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'completed_tasks', 'start_date', 'end_date', 'owner_id',
    ];

    /**
     * The members of the project.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_users')
                    ->withTimestamps();  // Just timestamps, no 'role' needed
    }

    /**
     * The owner of the project.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}



