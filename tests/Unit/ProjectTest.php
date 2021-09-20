<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    /** @test */
    public function it_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals("/projects/$project->id", $project->path());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class,$project->owner);
    }

    /** @test */
    public function it_has_tasks(){
        $project = Project::factory()->create();
        $project->tasks()->create(['body' =>$this->faker->sentence ]);
        self::assertInstanceOf(Task::class,$project->tasks->first());
    }

    /** @test */
    public function it_has_activity(){
        $project = Project::factory()->create();
        self::assertInstanceOf(Activity::class,$project->activity->first());
    }
}
