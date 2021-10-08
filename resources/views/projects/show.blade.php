<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex items-center">
                <a href="{{route('projects.index')}}" class="mr-2 align-text-bottom">my projects</a>/
                <div class="font-semibold text-md text-gray-500 leading-tight ml-2">{{ $project->title }}</div>

            </div>
            <span class="flex ml-auto">
                @foreach($project->members as $member)
                    <img class="rounded-full w-10 mr-4" src="{{pravatar_url($member->id)}}"
                         alt="{{$member->name}}'s profile picture">
                @endforeach
                <img class="rounded-full w-10 mr-4" src="{{pravatar_url($project->owner_id)}}"
                     alt="owner's profile picture">
                <a href="{{$project->path().'/edit'}}" class=" py-2 px-4 bg-blue-400 rounded-full text-white ml-4">edit your project</a>
            </span>

        </div>
    </x-slot>
    <div class="lg:flex">
        <div class="lg:w-4/6 mr-3">
            <div class="mb-8">
                <h2 class="text-xl text-gray-600 mb-2">Tasks</h2>
                @forelse($project->tasks as $task)

                    <x-card class="mb-3">

                        <form action="{{$task->path()}}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="flex justify-between items-center">
                                <input class="border-0 rounded w-full {{$task->completed ?'text-green-600':''}}"
                                       type="text" name="body" value="{{$task->body}}">
                                <input class="ml-2" type="checkbox" name="completed"
                                       onchange="this.form.submit()" {{$task->completed ?'checked':''}}>
                            </div>
                        </form>
                    </x-card>
                @empty
                @endforelse
                <x-card class="mb-3">
                    <form method="POST" action="{{$project->path().'/tasks'}}">
                        @csrf
                        <input class="border-0 rounded w-full text-sm" type="text" placeholder="Add A New Task"
                               name="body" value="{{old('body')}}">
                    </form>

                </x-card>
            </div>
            <div>
                <h2 class="text-xl text-gray-600 mb-2">General Notes</h2>
                <form action="{{$project->path()}}" method="POST">
                    @method("PATCH")
                    @csrf
                    <textarea name="notes" id="" rows="10" class="w-full px-4 py-2 rounded"
                              placeholder="Anything special you want to make a note of?"
                    >{{$project->notes}}</textarea>
                    <button type="submit" class="button mt-3">save notes</button>
                </form>
                <x-auth-validation-errors/>
            </div>
        </div>
        <aside class="lg:w-2/6 ml-3">
            <x-card class="mb-5 ">
                <div class=" text-md ">
                    <h3 class="border-l-4 border-blue-400 text-xl mb-6 h-14 font-bold -ml-6 pl-5 text-blue-500">
                        {{$project->title}}
                    </h3>
                    <div class="text-gray-600">{{$project->description}}</div>
                </div>
            </x-card>
            <x-card>
                <ul>
                    @forelse($project->activity as $activity)
                        <li class="list-none text-sm font-bold {{!$loop->last ?'mb-1':''}}">
                            @include('projects.activity.'.$activity->description)
                            <span class="text-gray-400">{{$activity->created_at->diffForHumans(null,true)}}</span>
                        </li>
                    @empty
                        <li class="list-none">there are no activities yet!</li>
                    @endforelse
                </ul>
            </x-card>
            @can('manage',$project)
                @include('partials.projects.invitations._form')
            @endcan
        </aside>
    </div>
</x-app-layout>
