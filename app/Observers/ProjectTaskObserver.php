<?php

namespace App\Observers;

use App\models\Task;

class ProjectTaskObserver
{

    public function created(Task $task)
    {
        $task->recordActivity('created_task');
    }

    public function updating(Task $task)
    {
        $task->old = $task->getOriginal();
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->recordActivity('deleted_task');
    }
}
