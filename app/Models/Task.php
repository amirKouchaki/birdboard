<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }



    public function path(){
        return $this->project->path().'/tasks/'.$this->id;
    }

    public function complete(){
        $this->update(['completed' => true]);
        $this->recordActivity('task_completed');
    }

    public function incomplete(){
         $this->update(['completed' => false]);
         $this->recordActivity('task_incompleted');
    }


    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'project_id' => $this->project_id
        ]);
    }
    public function activity()
    {
        return $this->morphMany(Activity::class,'subject');
    }
}
