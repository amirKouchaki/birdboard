<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tests\Setup\ProjectFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create(['email' => 'test@gmail.com']);
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make(12345678)
        ]);
        app(ProjectFactory::class)->ownedBy($user)->withTasks(2)->create();
    }
}
