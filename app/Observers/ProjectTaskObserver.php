<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;

class ProjectTaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param \App\Models\Task $task
     * @return void
     */
    public function created(Task $task)
    {
       $task->project->recordActivity('task_created');
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param \App\Models\Task $task
     * @return void
     */
    public function updated(Task $task)
    {
        if (!$task->completed) return;
        $task->project->recordActivity('task_completed');
    }

}
