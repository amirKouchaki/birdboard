<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

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

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_has_tasks()
    {
        $project = Project::factory()->create();
        $project->tasks()->create(['body' => $this->faker->sentence]);
        self::assertInstanceOf(Task::class, $project->tasks->first());
    }

    /** @test */
    public function it_has_activity()
    {
        $project = Project::factory()->create();
        self::assertInstanceOf(Activity::class, $project->activity->first());
    }

    /** @test */
    public function it_can_invite_users_to_the_project()
    {

        $project = app(ProjectFactory::class)->withTasks()->create();
        $project->invite($newUser = User::factory()->create());
        $this->assertTrue($project->members->contains($newUser));
    }

    /** @test */
    public function it_has_accessible_projects()
    {
        $john = $this->signIn();

        $sally = User::factory()->create();
        $nick = User::factory()->create();

        app(ProjectFactory::class)->ownedBy($john)->create();
        $project = tap(app(ProjectFactory::class)->ownedBy($sally)->create())->invite($nick);

        self::assertCount(1,$john->accessibleProjects());

        $project->invite($john);

        self::assertCount(2,$john->accessibleProjects());

    }

}
