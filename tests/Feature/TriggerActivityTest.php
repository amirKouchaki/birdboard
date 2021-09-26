<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project(){
        $project = Project::factory()->create();
        $this->assertCount(1,$project->activity);
        self::assertEquals('created',$project->activity->first()->description);
    }

    /** @test */
    public function updating_a_project(){
        $project = Project::factory()->create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2,$project->activity);
        $this->assertEquals('updated',$project->activity->last()->description);
    }

    /** @test */
    public function creating_a_task(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->assertCount(2,$project->activity);
        tap($project->activity->last(),function ( $activity){
            $this->assertEquals('task_created',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
        });
    }

    /** @test */
    public function completing_a_task(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),['body'=> 'changed','completed'=> true]);
        $this->assertCount(3,$project->activity);
        $this->assertEquals('task_completed',$project->activity->last()->description);
    }

    /** @test */
    public function incompleting_a_task(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),['body'=> 'changed','completed'=> true]);
        $this->assertCount(3,$project->activity);
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),['body'=> 'changed again','completed'=> false]);
        //TODO models have refreshes
        $project->refresh();
        $this->assertEquals('task_incompleted',$project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task(){
        $project = app(ProjectFactory::class)->withTasks()->create();
        $project->tasks[0]->delete();
        $this->assertCount(3,$project->activity);
    }

}
