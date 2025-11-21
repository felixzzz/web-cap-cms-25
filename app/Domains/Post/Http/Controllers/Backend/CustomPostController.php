<?php

namespace App\Domains\Post\Http\Controllers\Backend;

use App\Domains\Post\Http\Requests\StoreCustomPostRequest;
use App\Domains\Post\Models\PostType;
use App\Http\Controllers\Controller;

class CustomPostController extends Controller
{
    public function index()
    {
        $icons = $this->fontawesome();
        $posts = PostType::with(['post', 'user'])->get();
        return view('posts.custom.index', compact('icons', 'posts'));
    }

    public function store(StoreCustomPostRequest $request)
    {
        auth()->user()->posttype()->create($request->validated());
        flash('Custom post type successfully create!', 'success');
        return redirect()->route('post.type.custom');
    }
}
