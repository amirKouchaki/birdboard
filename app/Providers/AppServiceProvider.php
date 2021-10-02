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
            'is_project_owner',
            fn(User $user,Project $project)
                =>$user->is($project->owner)
        );
        Gate::define(
            'is_task_owner',
            fn(User $user,Task $task)
                 =>$user->is($task->project->owner)
        );

        Model::unguard(true);
//        Project::observe(ProjectObserver::class);
//        Task::observe(ProjectTaskObserver::class);
    }
}
