<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;


class ManageProjectTest extends TestCase
{
    use withFaker, LazilyRefreshDatabase;

    /** @test */
    public function only_an_authenticated_user_can_create_a_project()
    {
        $this->signIn();
        $this->get('/projects/create')->assertOk();
        $projectArray = Project::factory()->raw();
        unset($projectArray['owner_id']);
        $response = $this->post('/projects', $projectArray);
        $project = Project::where($projectArray)->first();
        $response->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', $projectArray);
        $this->get($project->path())->assertSee($projectArray);
        $this->get('/projects')->assertSee($projectArray['title']);
    }

    /** @test */
    public function only_an_authenticated_user_can_delete_a_project(){
        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->withTasks()->create();
        $this->actingAs($project->owner)->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects',$project->getAttributes());
    }

    /** @test */
    public function only_an_authenticated_user_can_update_a_project(){
        $project = app(ProjectFactory::class)->create();
        $this->actingAs($project->owner)->patch($project->path(),$attributes =[
            'title' => 'changed title',
            'description' => 'description changed',
            'notes' => 'changed'
        ])->assertRedirect($project->path());
        $this->assertDatabaseHas('projects',$attributes);
        $this->get($project->path().'/edit')->assertOk();
    }

    /** @test */
        public function only_an_authenticated_user_can_update_a_projects_general_notes_only(){
            $project = app(ProjectFactory::class)->create();
            $this->actingAs($project->owner)
                ->patch($project->path(),$attributes =['notes' => 'changed'])
                ->assertRedirect($project->path());
            $this->assertDatabaseHas('projects',$attributes);
            $this->get($project->path().'/edit')->assertOk();
        }

    /** @test */
    public function guests_cannot_manage_projects()
    {

        $project = Project::factory()->create();
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path().'/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->delete($project->path())->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_view_a_single_one_of_their_projects()
    {
        $project =app(ProjectFactory::class)->create();
        $this->actingAs($project->owner)->get($project->path())->assertSee([
            $project->title,
            $project->description
        ]);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_others_projects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->get($project->path())->assertForbidden();
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_others_projects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->delete($project->path())->assertForbidden();
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $this->post('/projects', Project::factory()->raw(['title' => '']))->assertSessionHasErrors(['title']);
    }


    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $this->post('/projects', Project::factory()->raw(['description' => '']))->assertSessionHasErrors(['description']);
    }


    /** @test */
    public function unauthenticated_users_cannot_update_a_project()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->patch($project->path())->assertForbidden();

    }
}
