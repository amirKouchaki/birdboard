<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProjectInvitationController extends Controller
{

    public function store(Project $project,ProjectInvitationRequest $request){
        $email = $request->validated();
        $user = User::whereEmail($email)->first();
        $project->invite($user);

        return redirect($project->path());
    }
}
