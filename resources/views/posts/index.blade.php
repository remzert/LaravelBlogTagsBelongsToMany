@extends('application')

@section('content')
    
    <div class="row">
            <div class="col-sm-8">
                 <h1>Articles</h1>
    
                    @foreach ($posts as $post)
                        <h2>{{$post->name}}</h2>

                        <p>
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('posts.tag', ['slug' => $tag->slug]) }}" class="badge badge-default"> {{ $tag->name }}</a>
                            @endforeach
                        </p>
                        <p>
                            {{$post->content}}
                        </p>
                        <p>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-default">Editer</a>
                        </p>
                        
                    @endforeach 
                    {{ $posts->links() }}
            </div>
            <div class="col-sm-4">
                <h2>Nuage de tags</h2>
                
                @foreach ($tags as $tag)
                    <a style="font-size:{{ 1 + round($tag->post_count / $max, 2) }}rem;" href="{{ route('posts.tag', ['slug' => $tag->slug]) }}"> {{ $tag->name }}</a>
                @endforeach
            </div>
    </div>

   
    
    
    <p>&nbsp;</p>
    
    @include('posts.form', ['post' => new App\Post() ])
@endsection
