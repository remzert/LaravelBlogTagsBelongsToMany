<h2>Créer un nouvel article</h2>

@if($post->id)
    <form action="{{route('posts.update', $post)}}" method="post">
        <input type="hidden" name="_method" value="put">
@else
    <form action="{{route('posts.store')}}" method="post">
@endif
    
    {{ csrf_field() }}
    <div class="form-group">
        <input type="text" name="name" class="form-control" value="{{ old('name', $post->name) }}" placeholder="Titre">
    </div>
    
    <div class="form-group">
        <textarea type="text" name="content" class="form-control" placeholder="Contenu">
            {{ old('content', $post->content) }}
        </textarea>
    </div>
    
    <div class="form-group">
        <input data-url="{{ route('tags.index') }}" id="tokenfield" type="text" name="tags" class="form-control" value="{{ old('tags', $post->tagsList) }}" placeholder="Tags">
    </div>
    
    <button class="btn btn-primary">Envoyer</button>
</form>

