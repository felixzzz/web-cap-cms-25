<?php

namespace App\Domains\Page\Services;

use App\Domains\Page\Http\Requests\StorePageRequest;
use App\Domains\Post\Models\Post;
use App\Services\Antikode\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageServices extends CrudService
{
    public function create_page_handler(Request $request)
    {
        $create = $request->user()->post()->create($request->all());

        if ($request->has('featured_image')) {
            $this->filepond_resolver($request->featured_image, 'featured_image', $create);
        }

        return $create;
    }

    public function update_page_handler(Request $request, Post $post)
    {
        $request['slug'] = isset($request['slug']) && $request['slug'] ? Str::slug($request['slug']) : Str::slug($request['title']);
        $post->update($request->all());
        $post->updateMeta('template', $request->template);
        if ($request->has('featured_image')) {
            $this->filepond_resolver($request->featured_image, 'featured_image', $post);
        }
        return $post;
    }

    public function change_status_handler(Post $post)
    {
        if ($post->status === 'publish') {
            $status = 'draft';
        } else {
            $status = 'publish';
        }
        $post->update([
            'status' => $status
        ]);
        return redirect()->route('admin.page')
            ->withFlashSuccess(__('Page was successfully created.'));
    }
}
