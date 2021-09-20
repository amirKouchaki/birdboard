<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project_records_activity(){
        $project = Project::factory()->create();
        $this->assertCount(1,$project->activity);
        self::assertEquals('created',$project->activity->first()->description);
    }

    /** @test */
    public function updating_a_project_records_activity(){
        $project = Project::factory()->create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2,$project->activity);
        self::assertEquals('updated',$project->activity->last()->description);
    }

    /** @test */
    public function creating_a_new_task_records_project_activity(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->assertCount(2,$project->activity);
        $this->assertEquals('task_created',$project->activity->last()->description);
    }

    /** @test */
    public function completing_a_new_task_records_project_activity(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),['body'=> 'changed','completed'=> true]);
        $this->assertCount(3,$project->activity);
        $this->assertEquals('task_completed',$project->activity->last()->description);
    }

    /** @test */
    public function changing_only_the_body_of_a_task_doesnt_record_project_activity(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),['body'=> 'changed']);
        $this->assertCount(2,$project->activity);
        $this->assertDatabaseMissing(Task::class,['description'=>'task_completed']);
    }
}
