<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PostType\StorePostTypeRequest;
use App\Http\Requests\Backend\PostType\UpdatePostTypeRequest;
use Illuminate\Http\Request;

class PostTypeController extends Controller
{
    public function index()
    {
        return view('backend.posttype.index');
    }

    public function create()
    {
        return view('backend.posttype.create');
    }

    public function show(PostType $type)
    {
        return view('backend.posttype.show', compact('type'));
    }

    public function edit(PostType $type)
    {
        return view('backend.posttype.edit', compact('type'));
    }

    public function store(StorePostTypeRequest $request)
    {
        $request->user()->posttype()->create($request->validated());
        return to_route('admin.posttype.index')->withFlashSuccess('Post type has been created');
    }

    public function update(UpdatePostTypeRequest $request, PostType $type)
    {
        $slug = $type->slug;
        $updated = $type->update($request->validated());
        if ($updated) {
            Post::where('type', $slug)->update([
                'type' => $type->slug,
            ]);
        }
        return back()->withFlashSuccess('Post type has been updated');
    }

    public function delete(PostType $type)
    {
        $type->delete();
        return back()->withFlashSuccess('Post type has been deleted');
    }

    public function trashed()
    {
        return view('backend.posttype.deleted');
    }

    public function restore(Request $request)
    {
        if ($request->has('id')) {
            PostType::query()->where('id', $request->id)->withTrashed()->restore();
            return back()->withFlashSuccess('Post type has been restored');
        }
        return back()->withFlashDanger('Fail to restore post type');

    }

    public function destroy(Request $request)
    {
        $find = PostType::query()->where('id', $request->id)->withTrashed()->first();
        if ($find) {
            if ($find->post()->count() > 0) {
                $find->post()->forceDelete();
            }

            $find->forceDelete();
            return back()->withFlashSuccess('Post type has been delete permanently');
        }
        return back()->withFlashDanger('Fail to delete permanently post type');
    }
}
