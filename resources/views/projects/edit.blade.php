<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-500 leading-tight">
                {{ __('Create A New Project') }}
            </h2>
            <a href="{{route('projects.index')}}" class="ml-auto py-2 px-4 bg-blue-400 rounded-full text-white">my projects</a>
        </div>
    </x-slot>
    <form method="POST" action="{{$project->path()}}" class="container" >
        @csrf
        @method("PATCH")
        <h1 class="heading">Create a Project</h1>
        @include('partials.projects._form',[
            'buttonText' => 'edit the project'
        ])
    </form>
</x-app-layout>
