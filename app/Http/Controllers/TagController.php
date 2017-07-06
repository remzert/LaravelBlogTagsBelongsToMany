<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function index(Request $request){
        $term = $request->get('term');
        return Tag::select('name')
                ->where('name', 'LIKE', $term . '%')
                ->get()
                ->map(function($tag){
                    return [
                        'id' => $tag->name,
                        'label' => $tag->name,
                        'value' => $tag->name
                    ];
                });
    }
}
