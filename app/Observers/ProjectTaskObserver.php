<?php

namespace App\Observers;

use App\Models\Task;

class ProjectTaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @return void
     */
    public function created(Task $task)
    {
       $task->recordActivity('task_created');
    }


    /**
     * Handle the Task "deleted" event.
     *
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->recordActivity('task_deleted');
    }

}
