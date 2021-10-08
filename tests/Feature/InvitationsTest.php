<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    /** @test */
    public function the_invited_email_must_be_associated_with_a_registered_user()
    {
        $project = ProjectFactory::create();
        $this->actingAs($project->owner)->post($project->path() . '/invitations', ['email' => $this->faker->email])
            ->assertSessionHasErrors(
                ['email' => 'there is no user registered with this email in this website.'],
                null,
                'invitations'
            );

    }

    /** @test */
    public function non_owners_may_not_invite_users_to_the_project()
    {
        $project = ProjectFactory::create();
        $john = User::factory()->create();

        $assertInvitationForbidden = function () use ($john, $project) {

            $this->actingAs($john)->post($project->path() . '/invitations', ['email' => $john->email])->assertForbidden();

        };

        $assertInvitationForbidden();

        $project->invite($john);

        $assertInvitationForbidden();

    }


    /** @test */
    public function a_project_can_invite_a_user()
    {

        $project = ProjectFactory::create();
        $john = User::factory()->create();

        $this->actingAs($project->owner)->post($project->path() . '/invitations', ['email' => $john->email])->assertRedirect();

        $this->assertTrue($project->members->contains($john));
    }


    /** @test */
    public function invited_users_may_update_project_details()
    {

        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks()->create();

        $project->invite($newUser = User::factory()->create());

        $this->actingAs($newUser)->post(route('tasks.store', $project), $task = ['body' => 'hello from new task']);

        $this->assertDatabaseHas('tasks', $task);

        $this->actingAs($newUser)->patch(route('tasks.update', [$project, $project->tasks->first()]), $updatedTask = ['body' => 'this is america']);

        $this->assertDatabaseHas('tasks', $updatedTask);
    }
}
