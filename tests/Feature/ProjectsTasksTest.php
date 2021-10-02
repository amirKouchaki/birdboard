<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectsTasksTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function guests_cannot_create_a_task()
    {
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks')->assertRedirect('/login');
    }

    /** @test */
    public function only_owner_of_the_project_can_create_a_task_for_their_project()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks')->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['body' => 'this is the projects test task']);
    }


    /** @test */
    public function a_project_can_manage_tasks()
    {

        $this->signIn();
        $project =app(ProjectFactory::class)->ownedBy(auth()->user())->create();
        $this->post($project->path() . '/tasks',$attributes = [
            'body' => 'this is the projects test task'
        ])->assertRedirect($project->path());
        $this->get($project->path())->assertSee($attributes);

    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();
        $project = app(ProjectFactory::class)->ownedBy(auth()->user())->create();
        $task = Task::factory()->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors(['body']);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),$attributes = [
            'body' => 'this is the updated Task'
        ])->assertRedirect($project->path());
        $this->assertDatabaseHas('tasks', $attributes);

    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),$attributes = [
            'body' => 'this is the updated Task',
            'completed' => true
        ])->assertRedirect($project->path());
        $this->assertDatabaseHas('tasks', $attributes);

    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(),$attributes = [
            'body' => 'this is the updated Task',
            'completed' => false
        ])->assertRedirect($project->path());
        $this->assertDatabaseHas('tasks', $attributes);

    }

    /** @test */
    public function only_the_owner_can_update_the_task()
    {
        $this->signIn();
        $project = app(ProjectFactory::class)
            ->withTasks()->create();
        $this->patch($project->tasks[0]->path())->assertForbidden();

    }

    /** @test */
    public function authenticated_user_cannot_update_tasks_that_they_dont_own_using_a_url_that_has_a_project_they_own(){
        $this->signIn();
        $project = app(ProjectFactory::class)->ownedBy(auth()->user())->create();
        $task = Task::factory()->create();
        $this->patch($project->path().'/tasks/'.$task->id)->assertForbidden();
    }



}
