<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @var array|mixed
     */
    public mixed $old = [];


    public function path(): string
    {
        return "/projects/$this->id";
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function recordActivity($description)
    {
        //TODO : getChanges() gives you the changes after the update
        $changes  = $this->activityChanges($description);

        $this->activity()->create([
            'description' => $description,
            'changes' => $changes
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

    public function activity()
    {

        return $this->hasMany(Activity::class)->latest();
    }

    protected function activityChanges($description)
    {
        //TODO : if any event other than updated is going to record changes this should change
        if($description === 'updated'){
            return  [
                'before' => Arr::except(array_diff($this->old,$this->getAttributes()),'updated_at'),
                'after'  => Arr::except($this->getChanges(),'updated_at')
            ];
        }

    }

}
