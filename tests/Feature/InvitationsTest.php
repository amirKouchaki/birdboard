<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function only_an_authenticated_user_can_invite_to_a_project(){

        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->withTasks()->create();

        $project->invite($newUser = User::factory()->create());

        $this->actingAs($newUser)->post(route('tasks.store',$project),$task = ['body' => 'hello from new task']);

        $this->assertDatabaseHas('tasks',$task);

        $this->actingAs($newUser)->patch(route('tasks.update',[$project,$project->tasks->first()]),$updatedTask = ['body' => 'this is america']);

        $this->assertDatabaseHas('tasks',$updatedTask);
    }
}
