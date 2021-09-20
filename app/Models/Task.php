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
        return $this->update(['completed' => true]);
    }
}
