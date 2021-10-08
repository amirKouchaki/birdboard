@props(['errors','errorBag' => 'default'])

@if ($errors->$errorBag->any())
    <div {{ $attributes }}>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600 ">
            @foreach ($errors->$errorBag->all() as $error)
                <li class="list-none">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
