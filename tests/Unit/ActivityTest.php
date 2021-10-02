<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use LazilyRefreshDatabase;
    /** @test */
    public function it_has_a_user(){
        $project = Project::factory()->create();
        self::assertInstanceOf(User::class,$project->activity->first()->user);
    }
}
