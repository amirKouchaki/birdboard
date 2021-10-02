<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
     public function it_can_add_a_task(){

         $this->withoutExceptionHandling();

         $project = Project::factory()->create();
         $task = $project->addTask('Test task');

         self::assertCount(1,$project->tasks);
         self::assertTrue($project->tasks->contains($task));
     }

}
