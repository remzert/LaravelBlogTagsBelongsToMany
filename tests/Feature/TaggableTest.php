<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Post;
use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Event;

class TaggableTest extends TestCase
{
    public function setUp(){
        parent::setUp();
        Artisan::call('migrate');   
    }
    
    public function listenQueries(){
        Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query){
            echo "\033[0;34" . $query->sql . "\033[0m <= ";
            echo "\033[0;32[" . implode(', ', $query->bindings) . "]\033[0m";
            echo "\n";
        });
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateTags()
    {
        
        //$post = Post::create(['name' => 'demo', 'content' => 'lorem']);
        $post = factory(Post::class)->create();
        $post->saveTags('salut,chien,chat');
        $this->assertEquals(3, Tag::count());
        $this->assertEquals(1, Tag::first()->post_count);
        $this->assertEquals(3, BD::table('post_tag')->count());
    }
    
     public function testDeleteFromPivot()
    {
        
        //$post = Post::create(['name' => 'demo', 'content' => 'lorem']);
        $post = factory(Post::class)->create();
        $post->saveTags('salut,chien,chat');
        $post->delete();
        $this->assertEquals(0, BD::table('post_tag')->count());
    }
    
    public function testEmptyTags()
    {
        
        //$post = Post::create(['name' => 'demo', 'content' => 'lorem']);
        $post = factory(Post::class)->create();
        $post->saveTags('');
        $this->assertEquals(0, Tag::count());
        
    }
    
    public function testReuseTags(){
        $posts = factory(Post::class, 2)->create();
        $post1 = $post->first();
        $post2 = $post->last();
        $post1->saveTags(' salut,chien ,chat, , ,');
        $post2->saveTags('salut,chameau');
        $this->assertEquals(4, Tag::count());
        $this->assertEquals(3, BD::table('post_tag')->where('post_id', $post1->id)->count());
        $this->assertEquals(2, BD::table('post_tag')->where('post_id', $post2->id)->count());
        $this->assertEquals(2, Tag::where('name', 'salut')->first()->post_count);
        
    }
    
    public function testPostCountOntags(){
        $posts = factory(Post::class, 2)->create();
        $post1 = $post->first();
        $post2 = $post->last();
        //$this->listenQueries();
        $post1->saveTags('salut,chien,chat');
        $this->assertEquals(1, Tag::where('name', 'salut')->first()->post_count);
        $post2->saveTags('salut');
               
        $this->assertEquals(2, Tag::where('name', 'salut')->first()->post_count);
        $post2->saveTags('chien');
        $this->assertEquals(2, Tag::where('name', 'chien')->first()->post_count);
        $this->assertEquals(1, Tag::where('name', 'salut')->first()->post_count);
    }   
    
    public function testCleanUnusedTags(){
        $post = factory(Post::class)->create();
        $post->saveTags('salut,chien,chat');
        $this->assertEquals(3, Tag::count());
        $post->saveTags('');
        $this->assertEquals(0, Tag::count());
       
    }
    
    public function testDeletePost(){
        $post = factory(Post::class)->create();
        $post->saveTags('salut,chien,chat');
        $this->assertEquals(3, Tag::count());
        $post->delete();
        $this->assertEquals(0, Tag::count());
    }
    
     public function testDuplicateTags(){
        $post = factory(Post::class)->create();
        $post->saveTags('salut,Salut');
        $this->assertEquals(1, Tag::count());
       
    }
    
           
}
