<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\True_;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function path(): string
    {
        return "/projects/$this->id";
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function recordActivity($type){
        Activity::factory()->create([
            'project_id' => $this->id,
            'description' => $type
        ]);
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity(){
        return $this->hasMany(Activity::class);
    }

}
