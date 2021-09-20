<div class="field">
    <label class="label" for="title">Title</label>

    <div class="control">
        <input type="text" class="input" name="title" placeholder="Title" value="{{$project->title}}" required>
    </div>
</div>

<div class="field">
    <label class="label" for="description">Description</label>

    <div class="control">
        <textarea name="description" class="textarea" rows="10" required>{{$project->description}}</textarea>
    </div>
</div>
@forelse($errors->all() as $error)
    <div class="my-3 text-xs text-red-500">{{$error}}</div>
@empty
@endforelse
<div class="field">
    <div class="control">
        <button type="submit" class="button is-link">{{$buttonText}}</button>
    </div>
</div>
