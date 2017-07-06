<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Observer;

use App\Tag;
use App\Post;


/**
 * Description of TaggableObserver
 *
 * @author Beaud
 */
class TaggableObserver {
    public function deleting(Post $post){
       $tags_id = $post->$tags->pluck('id');
       Tag::whereIn('id', $tags_id)->decrement('post_count', 1);
       Tag::removeUnused();
        
    }
}
