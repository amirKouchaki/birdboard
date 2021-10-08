<x-card class="mt-4">
    <h3 class="border-l-4 border-blue-400 text-xl mb-6 h-14 font-bold -ml-6 pl-5 text-blue-500">
        Invite A User
    </h3>
    <form action="{{route('invitation.store',$project)}}" method="POST">
        @csrf
        <input  class="block w-full rounded-md text-md px-4 py-2"  name="email" type="email" required>

        <x-button  class="bg-blue-500">invite</x-button>
    </form>
    <x-auth-validation-errors errorBag="invitations"/>
</x-card>
