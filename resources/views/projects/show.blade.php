<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex items-center">
                <a href="{{route('projects.index')}}" class="mr-2 align-text-bottom">my projects</a>/
                <div class="font-semibold text-md text-gray-500 leading-tight ml-2">{{ $project->title }}</div>
            </div>
            <a href="{{$project->path().'/edit'}}" class="ml-auto py-2 px-4 bg-blue-400 rounded-full text-white">edit your project</a>
        </div>
    </x-slot>

    <div class="lg:flex">
        <div class="lg:w-3/4 mr-3">
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
                    <button type="submit" class="button">save notes</button>
                </form>
                @forelse($errors->all() as $error)
                    <div class="my-3 text-xs text-red-500">{{$error}}</div>
                @empty
                @endforelse
            </div>
        </div>
        <div class="lg:w-1/4 ml-3">

            <x-card>
                <div class=" text-md ">
                    <h3 class="border-l-4 border-blue-400 text-xl mb-6 h-14 font-bold -ml-6 pl-5 text-blue-500">
                        {{$project->title}}
                    </h3>
                    <div class="text-gray-600">{{$project->description}}</div>
                </div>
            </x-card>
        </div>
    </div>

</x-app-layout>
