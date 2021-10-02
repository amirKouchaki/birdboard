<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project(){
        $project = Project::factory()->create();
        $task = $project->addTask('this is a new Task');
        self::assertInstanceOf(Project::class,$task->project);
    }

    /** @test */
    public function it_has_a_path(){

        $task = Task::factory()->create();
        self::assertEquals($task->project->path().'/tasks/'.$task->id,$task->path());
    }

    /** @test */
    public function it_can_be_completed(){
        $task = Task::factory()->create();
        self::assertFalse($task->completed);
        $task->complete();
        self::assertTrue($task->fresh()->completed);
    }

    /** @test */
    public function it_can_be_marked_as_incomplete(){
        $this->withoutExceptionHandling();
        $task = Task::factory()->create();
        $task->complete();
        self::assertTrue($task->fresh()->completed);
        $task->incomplete();
        self::assertFalse($task->fresh()->completed);
    }

}
