<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Observers\ProjectObserver;
use App\Observers\ProjectTaskObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define(
            'update',
            fn(User $user,Project $project)
                =>$user->is($project->owner) ||$project->members->contains($user)
        );
        Gate::define(
            'manage',
            fn(User $user,Project $project)
            =>$user->is($project->owner)
        );

        Model::unguard(true);
//        Project::observe(ProjectObserver::class);
//        Task::observe(ProjectTaskObserver::class);
    }
}
