<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\PostRequest;
use App\Post;
use App\Tag;

class PostController extends Controller
{
    public function index() {
        /*$posts = Post::with('tags')->paginate(1);
        
        return view ('posts.index', [
            'posts' => $posts
         ]);*/
        return $this->renderIndex((new Post())->newQuery() );
    }
    
    public function tag($slug){
        $tag = Tag::where('slug', $slug)->first();
        
        
         return $this->renderIndex($tag->posts());
    }
    
    private function renderIndex($postQuery){
        $posts=$postQuery->with('tags')->paginate(10);
        $tags = Tag::all();
        $max = Tag::max('post_count');
        return view ('posts.index', [
            'posts' => $posts,
            'tags' => $tags,
            'max' => $max
         ]);
    }
    
    public function store(Request $request){
        $post = Post::create($request->all());
        $post->saveTags($request->get('tags'));
        
        return redirect()->route('posts.index');
    }
    
    public function edit(Post $post){
        return view('posts.edit', ['post' => $post]);
    }
    
    public function update(PostRequest $request, Post $post){
        $post->update($request->all());
        $post->saveTags($request->get('tags'));
        return redirect()->route('posts.index');
    }
    
}
