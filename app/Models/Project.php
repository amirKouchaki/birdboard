<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity,HasFactory;


    protected $guarded = [];

    //should not be protect or private
    // public $old = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function invite(User $user){
        $this->members()->attach($user);
    }

    public function members(){
        return $this->belongsToMany(User::class,'project_member')->withTimestamps();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }
}
