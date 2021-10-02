<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-500 leading-tight">
                {{ __('projects') }}
            </h2>
            <a href="{{route('projects.create')}}" class="ml-auto py-2 px-4 bg-blue-400 rounded-full text-white">create a new project</a>
        </div>
    </x-slot>
    <div class="lg:flex lg:justify-center -mx-3 flex-wrap">
        @forelse($projects as $project)
            <div class=" lg:w-1/3 p-3 py-4 ">
                <x-card style="height: 220px; width: 400px;">
                    <div class=" text-md ">
                        <h3 class="border-l-4 border-blue-400 text-xl mb-6 h-14 font-bold -ml-6 pl-5">
                            <a href="{{$project->path()}}">{{$project->title}}</a>
                        </h3>
                        <div class="text-gray-600">{{Illuminate\Support\Str::limit($project->description)}}</div>
                    </div>
                    <footer class="mt-2" >
                        <form action="{{$project->path()}}" method="POST" class="text-right">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="text-md text-red-500">delete</button>
                        </form>
                    </footer>
                </x-card>
            </div>
        @empty
            <div>no projects yet.</div>
        @endforelse
    </div>
</x-app-layout>
