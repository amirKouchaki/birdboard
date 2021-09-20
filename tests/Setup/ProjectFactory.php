<?php


namespace Tests\Setup;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    public User $owner;
    public int $tasksCount = 0;


    public function ownedBy(User $owner){
        $this->owner = $owner;
        return $this;
    }


    public function withTasks(int $tasksCount = 1){
        $this->tasksCount = $tasksCount;
        return $this;
    }


    public function create(){

        $project = Project::factory()->create([
            'owner_id' => $this->owner?? User::factory()
        ]);

        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id
        ]);


        return $project;
    }

}
