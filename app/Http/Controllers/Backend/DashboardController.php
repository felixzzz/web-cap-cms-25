<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Domains\Post\Models\Post;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('backend.dashboard');
    }

    function editbyTemplate(Request $request, $template)
    {
        $template = $this->page_templates()->where('name', $template)->first();
        $post = Post::where('type', 'page')->where('template',$template)->orderBy('id', 'desc')->first();
        if(!$post){
            $request->merge(['template' => $template['name']]); // Use merge to add or update the request data
            return $this->create($request);
        }
        if ($template) {
            $components = $template['components'];
            $meta = $post->meta->groupBy('section');
            $valueMeta = [];
            foreach ($meta as $keyName => $fields) {
                $data = new \stdClass();
                foreach ($fields as $key => $val) {
                    $data->{$val->key} = $val->value;
                }
                $valueMeta[$keyName] = $data;
            }
            $location = $post->getMeta('location');
            $parents = Post::where('type', 'page')->where('id', '!=', $post->id)->pluck('id', 'title');
            return view('backend.page.edit', compact('template', 'components', 'post', 'parents', 'location'))->withMeta($valueMeta);
        }
        return back()->withFlashSuccess('The page have invalid format pages.');
    }
    public function create(Request $request)
    {
        $checkExist = Post::where('type', 'page')->where('template', $request->template)->first();
        if($checkExist)
        {
            throw new GeneralException('The page have has already created.');
        }
        $template = $this->page_templates()->where('name', $request->template)->first();
        // dd($template, $request->template);
        if ($template) {
            $components = $template['components'];
            $post = new Post();
            $parents = Post::where('type', 'page')->pluck('id', 'title');
            return view('backend.page.create', compact('template', 'components', 'post', 'parents', 'template'));
        }
    }
    public function page_templates(): ?\Illuminate\Support\Collection
    {
        $template = null;
        if (file_exists(resource_path('templates/pages.json'))) {
            $template = file_get_contents(resource_path('templates/pages.json'));
            $template = json_decode($template, true);
            $template = collect($template);
        }
        return $template;
    }
}
