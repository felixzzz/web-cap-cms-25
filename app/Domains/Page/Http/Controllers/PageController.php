<?php

namespace App\Domains\Page\Http\Controllers;

use App\Domains\Page\Http\Requests\StorePageRequest;
use App\Domains\Page\Services\PageServices;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\ComponentService;
use App\Domains\Post\Services\PostMetaService;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Models\BannerActive;
use App\Models\BannerGroup;


class PageController extends Controller
{

    protected $componentService;
    protected $postMetaService;
    private $rules;
    public function __construct(ComponentService $componentService, PostMetaService $postMetaService)
    {
        $this->componentService = $componentService;
        $this->postMetaService = $postMetaService;
        $this->rules = [
            'type' => ['required'],
            'title' => ['required', 'max:100'],
            'excerpt' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'featured_image' => ['nullable'],
            'featured_image_remove' => ['nullable'],
            'slug' => ['nullable'],
            'site_url' => ['nullable'],
            'meta_title' => ['nullable'],
            'meta_keyword' => ['nullable'],
            'meta_description' => ['nullable'],
            'featured' => ['nullable'],
            'status' => ['nullable'],
            'published_at' => ['nullable', 'date'],
            'parent' => ['nullable', 'exists:posts,id']
        ];
    }

    public function index()
    {
        $templates = $this->page_templates();
        return view('backend.page.index', compact('templates'));
    }

    public function create(Request $request)
    {
        $checkExist = Post::where('type', 'page')->where('template', $request->template)->first();
        if ($checkExist) {
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

    public function store(Request $request)
    {
        $template = $this->page_templates()->where('name', $request->template)->first();

        $componentRule = $this->componentService->getComponentRules($template['components']);
        if (isset($template['multilanguage']) && $template['multilanguage'] == 'true') {
            $componentRule = $this->componentService->getComponentRulesLanguage($template['components'], $template);
        } else {
            $componentRule = $this->componentService->getComponentRules($template['components']);
        }

        $rules = array_merge($this->rules, $componentRule);
        $requestValidated = Validator::make($request->all(), $rules)->validate();
        $request['template'] = $request->template;
        $create = (new PageServices())->create_page_handler($request);
        if ($create) {
            if ($template['name'] === 'blank') {
                if ($request->has('location')) {
                    $create->updateMeta('location', $request->location);
                }
            }
            $create->updateMeta('template', $request->template);
            if (count($template['components']) > 0) {
                $this->postMetaService->updatePageMetaV2($create, $request->all());
            }
        }
        $post = $create;
        $components = $template['components'];
        $meta = $create->meta->groupBy('section');
        $valueMeta = [];
        foreach ($meta as $keyName => $fields) {
            $data = new \stdClass();
            foreach ($fields as $key => $val) {
                $data->{$val->key} = $val->value;
            }
            $valueMeta[$keyName] = $data;
        }
        $location = $create->getMeta('location');
        $parents = Post::where('type', 'page')->where('id', '!=', $create->id)->pluck('id', 'title');
        return view('backend.page.edit', compact('template', 'components', 'post', 'parents', 'location'))->withMeta($valueMeta)->withFlashSuccess(__('Page was successfully created.'));
    }

    public function edit(Post $post)
    {
        $template = $this->page_templates()->where('name', $post->template)->first();
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

            $bannerGroups = BannerGroup::where('position', 'pages')->get();
            $homeBannerGroups = BannerGroup::where('position', 'home')->get();
            $homeBanners = BannerActive::where('post_id', $post->id)
                ->whereIn('location', ['journey-growth', 'financial-report', 'financial-reports', 'navbar', 'footer'])
                ->get()
                ->map(function ($banner) {
                    if ($banner->location == 'financial-report')
                        $banner->location = 'financial-reports';
                    return $banner;
                })
                ->groupBy('location');

            return view('backend.page.edit', compact('template', 'components', 'post', 'parents', 'location', 'bannerGroups', 'homeBannerGroups', 'homeBanners'))->withMeta($valueMeta);
        }
        return back()->withFlashSuccess('The page have invalid format pages.');
    }
    public function editbyTemplate(Request $request, $templateName)
    {
        $template = $this->page_templates()->where('name', $templateName)->first();
        $post = Post::where('type', 'page')->where('template', $templateName)->orderBy('id', 'desc')->first();
        if (!$post) {
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

            $bannerGroups = BannerGroup::where('position', 'pages')->get();
            $homeBannerGroups = BannerGroup::where('position', 'home')->get();
            $homeBanners = BannerActive::where('post_id', $post->id)
                ->whereIn('location', ['journey-growth', 'financial-report', 'financial-reports', 'navbar', 'footer'])
                ->get()
                ->map(function ($banner) {
                    if ($banner->location == 'financial-report')
                        $banner->location = 'financial-reports';
                    return $banner;
                })
                ->groupBy('location');

            return view('backend.page.edit', compact('template', 'components', 'post', 'parents', 'location', 'bannerGroups', 'homeBannerGroups', 'homeBanners'))->withMeta($valueMeta);
        }
        return back()->withFlashSuccess('The page have invalid format pages.');
    }

    public function update(Request $request, Post $post)
    {
        $template = $this->page_templates()->where('name', $request->template)->first();
        $componentRule = $this->componentService->getComponentRules($template['components']);
        $rules = array_merge($this->rules, $componentRule);
        if ($template['name'] === 'blank') {
            $rules['content'] = ['required', 'string'];
        }
        $requestValidated = Validator::make($request->all(), $rules)->validate();
        $update = (new PageServices())->update_page_handler($request, $post);
        if ($update) {
            if ($template['name'] === 'blank') {
                if ($request->has('location')) {
                    $post->updateMeta('location', $request->location);
                }
            }

            $this->postMetaService->updatePageMetaV2($post, $request->all());

            if ($request->has('home_banners')) {
                foreach ($request->home_banners as $lang => $positions) {
                    if (is_array($positions) && !isset($positions['banner_group_id'])) {
                        foreach ($positions as $location => $data) {
                            if (!empty($data['banner_group_id'])) {
                                BannerActive::updateOrCreate(
                                    ['post_id' => $post->id, 'location' => $location, 'language' => $lang],
                                    ['banner_group_id' => $data['banner_group_id'], 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]
                                );
                            } else {
                                BannerActive::where('post_id', $post->id)->where('location', $location)->where('language', $lang)->delete();
                            }
                        }
                    } else {
                        $location = $lang;
                        $data = $positions;
                        if (!empty($data['banner_group_id'])) {
                            BannerActive::updateOrCreate(
                                ['post_id' => $post->id, 'location' => $location, 'language' => 'id'],
                                ['banner_group_id' => $data['banner_group_id'], 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]
                            );
                        } else {
                            BannerActive::where('post_id', $post->id)->where('location', $location)->delete();
                        }
                    }
                }
            }
        }

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

        $bannerGroups = BannerGroup::where('position', 'pages')->get();
        $homeBannerGroups = BannerGroup::where('position', 'home')->get();
        $homeBanners = BannerActive::where('post_id', $post->id)
            ->whereIn('location', ['journey-growth', 'financial-report', 'financial-reports', 'navbar', 'footer'])
            ->get()
            ->map(function ($banner) {
                if ($banner->location == 'financial-report')
                    $banner->location = 'financial-reports';
                return $banner;
            })
            ->groupBy('location');

        session()->flash('success', __('Page was successfully updated.'));
        return view('backend.page.edit', compact('template', 'components', 'post', 'parents', 'location', 'bannerGroups', 'homeBannerGroups', 'homeBanners'))->withMeta($valueMeta)->withFlashSuccess(__('Page was successfully updated.'));
    }

    public function delete(Post $post)
    {
        Gate::authorize("admin.access.{$post->type}.delete");

        $type = $post->type;

        if (!$post->delete()) {
            throw new GeneralException('There was a problem deleting this post. Please try again.');
        }
        ;
        session()->flash('success', __('Page was successfully deleted.'));

        return redirect()->route('admin.page')->withFlashSuccess(__('Page was deleted successfully.'));
    }

    public function trashed()
    {
        Gate::authorize("admin.access.page.read");

        return view('backend.page.deleted');
    }

    public function restore(int $deletedPostId)
    {
        $deletedPost = Post::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        Gate::authorize("admin.access.page.delete");

        if (!$deletedPost->restore()) {
            throw new GeneralException(__('There was a problem restoring this post. Please try again.'));
        }

        return redirect()->route('admin.page')->withFlashSuccess(
            __('Page ' . $deletedPost->title . ' was successfully restored.')
        );
    }

    public function destroy(int $deletedPostId)
    {
        $deletedPost = Post::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        Gate::authorize("admin.access.{$type}.delete");

        DB::beginTransaction();

        try {
            $deletedPost->forceDelete();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem permanently deleting this page. Please try again.'));
        }
        DB::commit();

        return redirect()->route('admin.page.deleted')->withFlashSuccess(
            __('The page was permanently deleted.')
        );
    }

    public function show(Post $post)
    {
        Gate::authorize("admin.access.{$post->type}.read");

        $type = $post->type;

        $theTemplate = $this->page_templates()->where('name', $post->template)->first();
        $components = $theTemplate['components'] ?? [];
        $meta = $post->meta->groupBy('section');
        $valueMeta = [];
        foreach ($meta as $keyName => $fields) {
            $data = new \stdClass();
            foreach ($fields as $key => $val) {
                $data->{$val->key} = $val->value;
            }
            $valueMeta[$keyName] = $data;
        }
        $post->load('parent');
        $parent = $post->parent()->first();
        return view('backend.page.show')
            ->withPost($post)
            ->withParent($parent)
            ->withType($type)
            ->withComponents($components)
            ->withMeta($valueMeta);
    }

    public function changeStatus(Post $post)
    {
        return (new PageServices())->change_status_handler($post);
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
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
