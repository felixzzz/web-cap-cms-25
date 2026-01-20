<?php

namespace App\Domains\Post\Http\Controllers\Backend;

use App\Domains\Post\Http\Requests\StorePostRequest;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostType;
use App\Domains\Post\Services\ComponentService;
use App\Domains\Post\Services\PostMetaService;
use App\Domains\Post\Services\PostService;
use App\Exceptions\GeneralException;
use App\Jobs\PostScheduler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;
use Spatie\Tags\Tag;

class PostController extends BackendController
{
    private $template;
    private $rules;

    /**
     * @var PostService
     * @var PostMetaService
     * @var ComponentService
     */
    protected $postService;
    protected $postMetaService;
    protected $componentService;

    public function __construct(PostService $postService, PostMetaService $postMetaService, ComponentService $componentService)
    {
        // setup service
        $this->postService = $postService;
        $this->postMetaService = $postMetaService;
        $this->componentService = $componentService;

        // setup rules validation
        $this->rules = [
            'type' => [],
            'title' => ['required_without:title_en', 'max:200'],
            'slug' => 'max:100',
            'slug_en' => 'max:100',
            'title_en' => ['required_without:title', 'max:200'],
            'excerpt' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'tags' => ['nullable'],
            'tags_id' => ['nullable'],
            'categories' => ['nullable'],
            'featured_image' => ['nullable'],
            'alt_image' => ['nullable'],
            'alt_image_en' => ['nullable'],
            'featured_image_remove' => ['nullable'],
            'meta_title' => ['nullable'],
            'meta_keyword' => ['nullable'],
            'meta_description' => ['nullable'],
            'featured' => ['nullable'],
            'status' => ['nullable'],
            'published_at' => ['nullable', 'date'],
            'post_type' => ['nullable']
        ];
    }

    public function index($type = 'news')
    {
        // Gate::authorize("admin.access.news.read");
        $type = $this->extract_post_type($type);
        $posts = Post::with('tags')->get();
        return view('backend.posts.index', compact('type', 'posts'));
    }

    public function create($type = 'news')
    {
        // Gate::authorize("admin.access.news.create");
        $type = $this->extract_post_type($type);
        list($template, $components) = $this->getTemplates($type['type']);
        $template = $this->getTemplates($type['type']);
        $template['multilanguage'] = $template[2]['multilanguage'];
        $template['lang_option'] = $template[3]['lang_option'];
        $categories = $this->getCategories($type['type']);
        $post = new Post();
        $pages = Post::select('id', 'title', 'slug')
            ->where('pages_dynamic', 'yes')->get();
        $tagDatas = Tag::all();
        $tags = $tagDatas->map(function ($tag) {
            return $tag->getTranslation('name', 'en');
        })->toArray();
        $tags_id = $tagDatas->map(function ($tag) {
            return $tag->getTranslation('name', 'id');
        })->toArray();

        return view(
            'backend.posts.create',
            compact(
                'components',
                'template',
                'categories',
                'type',
                'post',
                'tags',
                'tags_id',
                'pages'
            )
        );
    }

    public function store(Request $request, $type = 'news')
    {
        // Gate::authorize("admin.access.news.create");
        $type = $this->extract_post_type($type);
        list($template, $components) = $this->getTemplates($type['type']);
        $componentRules = $this->componentService->getComponentRules($components);

        $rules = array_merge($this->rules, $componentRules);
        $requestValidated = Validator::make($request->all(), $rules)->validate();

        $post = (new PostService())->create_post_handler($requestValidated, $type['type']);

        $this->postMetaService->updatePageMetaV2($post, $request->all());

        if ($post->status == Post::STATUS_SCHEDULE) {
            if ($request->get('published_at')) {
                $publishedAt = Carbon::parse($request->get('published_at'));
            } else {
                $publishedAt = now()->addHour();
            }

            // PostScheduler::dispatch($post)->delay($publishedAt);
        }

        return redirect()->route('admin.post.index', ['type' => $type['type']])
            ->withFlashSuccess(__('Post was successfully created.'));
    }

    public function edit(Post $post)
    {
        // Gate::authorize("admin.access.news.update");
        $type = $this->extract_post_type($post->type);
        list($template, $components) = $this->getTemplates($type['type']);
        $template = $this->getTemplates($type['type']);
        $template['multilanguage'] = $template[2]['multilanguage'];
        $template['lang_option'] = $template[3]['lang_option'];
        $meta = $post->meta->groupBy('section');
        $pages = Post::select('id', 'title', 'slug')->where('pages_dynamic', 'yes')->get();

        $valueMeta = [];
        foreach ($meta as $keyName => $fields) {
            $data = new \stdClass();
            foreach ($fields as $key => $val) {
                $data->{$val->key} = $val->value;
            }
            $valueMeta[$keyName] = $data;
        }

        $categories = [];
        if ($type['is_category']) {
            $post->load('category');
            $categories = $this->getCategories($post->type);
        }


        $tagDatas = Tag::all();
        $tags = $tagDatas->map(function ($tag) {
            return $tag->getTranslation('name', 'en');
        })->toArray();
        $tags_id = $tagDatas->map(function ($tag) {
            return $tag->getTranslation('name', 'id');
        })->toArray();
        return view(
            'backend.posts.edit',
            compact(
                'template',
                'categories',
                'post',
                'components',
                'tags',
                'tags_id',
                'type',
                'pages'
            )
        )->withMeta($valueMeta);
    }

    public function update(Request $request, Post $post)
    {
        // Gate::authorize("admin.access.news.update");
        $type = $this->extract_post_type($post->type);
        list($template, $components) = $this->getTemplates($type['type']);
        $componentRules = $this->componentService->getComponentRules($components);
        $rules = array_merge($this->rules, $componentRules);
        $requestValidated = Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();

        try {
            $post = (new PostService())->update_post_handler($post, $requestValidated);
            $this->postMetaService->updatePageMetaV2($post, $request->all());

            if ($post->status == Post::STATUS_SCHEDULE) {
                if ($request->get('published_at')) {
                    $publishedAt = now()->diffInMinutes($request->get('published_at'));
                } else {
                    $publishedAt = now()->addHour();
                }
                // PostScheduler::dispatch($post)->delay($publishedAt);
            }

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            throw $exception;
        }
        return redirect()->route('admin.post.index', $type['type'])->withFlashSuccess(
            __('Page ' . $post->title . ' was successfully updated.')
        );
    }

    /**
     * @param Post $post
     *
     * @return mixed
     */
    public function show(Post $post)
    {
        // Gate::authorize("admin.access.news.read");

        $type = $post->type;

        list($components) = $this->getTemplates($type);
        $meta = $post->meta->groupBy('section');
        $valueMeta = [];
        foreach ($meta as $keyName => $fields) {
            $data = new \stdClass();
            foreach ($fields as $key => $val) {
                $data->{$val->key} = $val->value;
            }
            $valueMeta[$keyName] = $data;
        }
        $post->load('category');

        return view('backend.posts.show')
            ->withPost($post)
            ->withType($type)
            ->withComponents($components)
            ->withMeta($valueMeta);
    }

    public function changeStatus(Post $post)
    {
        (new PostService())->change_post_status($post);
        flash('Post status has been changed', 'success');
        return redirect()->route('admin.post.index');
    }

    /**
     * @param Request $request
     * @param Post $post
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function delete(Request $request, Post $post)
    {
        // Gate::authorize("admin.access.news.delete");

        $type = $post->type;

        if (!$post->delete()) {
            throw new GeneralException('There was a problem deleting this post. Please try again.');
        }
        ;

        return redirect()->route('admin.post.index', $type)->withFlashSuccess(
            __('Page ' . $post->title . ' was successfully deleted.')
        );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trashed($type)
    {
        // Gate::authorize("admin.access.news.read");

        return view('backend.posts.deleted')
            ->withType($type);
    }

    /**
     * @param Int $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function restore(int $deletedPostId)
    {
        $deletedPost = Post::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        // Gate::authorize("admin.access.news.delete");

        if (!$deletedPost->restore()) {
            throw new GeneralException(__('There was a problem restoring this post. Please try again.'));
        }

        return redirect()->route('admin.post.index', $type)->withFlashSuccess(
            __('Page ' . $deletedPost->title . ' was successfully restored.')
        );
    }

    /**
     * @param Int $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(int $deletedPostId)
    {
        $deletedPost = Post::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        // Gate::authorize("admin.access.news}.delete");

        DB::beginTransaction();

        try {
            $deletedPost->category()->detach();
            $deletedPost->forceDelete();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem permanently deleting this page. Please try again.'));
        }
        DB::commit();

        return redirect()->route('admin.post.deleted', $type)->withFlashSuccess(
            __('The page was permanently deleted.')
        );
    }


    public function getColorPalette()
    {
        $data = Post::where('type', 'color')->where('status', 'published')->get();
        $data = $data->map(function ($value) {
            return [
                'title' => $value->title_id,
                'color' => $value->title_en,
            ];
        });

        return response()->json($data);
    }

    /**
     * @param mixed $type
     * @return array
     */
    public function extract_post_type(mixed $type): array
    {
        if ($type !== 'news') {
            $postType = PostType::where('slug', $type)->first();
            if ($postType) {
                if ($postType->is_category) {
                    $this->rules['categories'] = ['array', 'required', 'exists:categories,id'];
                } else {
                    $this->rules['categories'] = ['array', 'nullable', 'exists:categories,id'];
                }

                if (!$postType->is_content) {
                    $this->rules['content'] = ['nullable'];
                }
                return [
                    'name' => $postType->name,
                    'type' => $postType->slug,
                    'is_category' => $postType->is_category,
                    'is_content' => $postType->is_content,
                    'is_tags' => $postType->is_tags,
                    'featured' => $postType->featured,
                    'featured_image' => $postType->featured_image,
                ];
            }
        }
        return [
            'name' => ucwords(trim(str_replace('-', ' ', $type))),
            'type' => $type,
            'is_category' => true,
            'is_content' => true,
            'is_tags' => true,
            'featured' => true,
            'featured_image' => true,
        ];
    }

    /**
     * @param mixed $type
     * @return array
     */
    public function getTemplates(mixed $type): array
    {
        $theTemplate = $this->getTemplate($type, 'posts');
        if ($theTemplate) {
            $template = $theTemplate['name'];
            $multilanguage['multilanguage'] = $theTemplate['multilanguage'];
            $lang_option['lang_option'] = $theTemplate['lang_option'];
            $components = $theTemplate['components'] ?? [];
        } else {
            $template = null;
            $components = [];
            $multilanguage = false;
            $lang_option = [];
        }
        return array($template, $components, $multilanguage, $lang_option);
    }
    public function checkPostStatus()
    {
        $now = Carbon::now();
        $formattedNow = $now->format('Y-m-d H:i:s');

        $posts = Post::where('status', Post::STATUS_SCHEDULE)->get();

        foreach ($posts as $post) {
            if ($post->published_at && $post->published_at <= $now) {
                $post->status = Post::STATUS_PUBLISH;
                if (!$post->published_at) {
                    $post->published_at = $now;
                }
                $post->save();
            }
        }
        return true;
    }
}
