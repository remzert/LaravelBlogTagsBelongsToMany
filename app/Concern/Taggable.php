<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Concern;

use App\Tag;
use Illuminate\Support\Str;

/**
 *
 * @author Beaud
 */
trait Taggable {
    
    public static function bootTaggable(){
        static::observe(App\Observer\TaggableObserver::class);
    } 
    
    public function tags(){
       return $this->belongsToMany(Tag::class);
    }
    
    public function saveTags(string $tags){
            //je recupere la liste des tags à associer à l'article
            $tags = array_filter(array_unique(array_map(function($item){
                return trim($item); 
            }, explode(',', $tags))), function($item){
                    return !empty($item);
            });
            
                       
            // je recupére les tags qui sont déjà en BD
            $persisted_tags = Tag::whereIn('name', $tags)->get();
            
                        
            // je trouve les nouveaux tags et je les insère en base
            $tags_to_create = array_diff($tags, $persisted_tags->pluck('name')->all());
            $tags_to_create = array_map(function ($tag){
                return [
                    'name' => $tag, 
                    'slug' => Str::slug($tag), 
                    'post_count' => 1
                ];
            }, $tags_to_create);
            /*foreach ($tags as $tag)
            {
                $tags_to_create[] = ['name' => $tag, 'slug' => Str::slug($tag)];
            }*/
            $created_tags = $this->tags()->createMany($tags_to_create);
            $persisted_tags = $persisted_tags->merge($created_tags);
            $edits = $this->tags()->sync($persisted_tags);
            
            Tag::whereIn('id', $edits['attached'])->increment('post_count', 1);
            Tag::whereIn('id', $edits['detached'])->decrement('post_count', 1);
            Tag::removeUnused();
            
     }
     
     public function getTagsListAttribute(){
         return $this->tags->pluck('name')->implode(',');
     }
}
