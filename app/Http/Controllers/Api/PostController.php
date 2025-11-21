<?php

namespace App\Http\Controllers\Api;

use App\Domains\Learn\Models\Learn;
use App\Domains\PostCategory\Models\Category;
use App\Http\Controllers\Controller;
use App\Domains\Post\Services\PostService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Domains\Post\Models\Post;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Domains\Member\Models\Member;
use App\Domains\Member\Models\MemberLearn;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function index(Request $request)
    {
        $request->validate([
            'limit' => ['nullable', 'numeric'],
            'type' => ['nullable', 'string'],
            'post_type' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
            'categories' => ['nullable'],
            'category_slug' => ['nullable'],
            'featured' => ['nullable'],
            'meta' => ['nullable'],
            'sort' => ['nullable'],
            'order' => ['nullable'],
            'lang' => ['nullable'],
            'management_type' => ['nullable'],
            'news_dynamic' => ['nullable']
        ]);
        $lang = $request->lang;

        $query = Post::with('meta')->where('type', $request->type ?? 'news')
                    ->where('status', 'publish');
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search);
            if($request->type != 'managements'){
                if ($lang === 'id') {
                    $query->whereRaw('LOWER(posts.title) LIKE ?', ["%{$searchTerm}%"]);
                } elseif ($lang === 'en') {
                    $query->whereRaw('LOWER(posts.title_en) LIKE ?', ["%{$searchTerm}%"]);
                }
            }else{
                $query->whereRaw('LOWER(posts.title) LIKE ?', ["%{$searchTerm}%"]);
            }
        }

        if ($request->has('featured') && $request->featured != '') {
            $query->where('featured', $request->featured);
        }
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
            if($request->type == 'news' && $request->type == 'articles-sustainability'){
                $query->where('published_at', '<=', date('Y-m-d H:i:s'));
            }
        }
        if ($request->has('post_type') && $request->post_type != '') {
            $query->where('post_type', $request->post_type);
        }
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->whereIn('category_id', $request->categories);
            });
        }
        if ($request->has('category_slug') && !empty($request->category_slug)) {
            $query->whereHas('category', function ($q) use ($request, $lang) {
                $q->where('slug', $request->category_slug);
            });
        }
        if ($request->has('management_type') && !empty($request->management_type)) {
            $query->whereHas('meta', function ($q) use ($request) {
                $q->where('key', 'category')->where('type', 'text')->where('section', 'list')->where('value', $request->management_type);
            });
        }
        if ($request->has('sort') && $request->sort != '' && $request->has('order') && $request->order != '') {
            $query->orderBy($request->sort, $request->order);
        }

        $posts = $query->paginate($request->limit ?? 10);

        $posts->getCollection()->transform(function ($value) use($lang){
            $valueMeta = [];

            if ($value->meta) {
                $meta = $value->meta->groupBy('section');

                foreach ($meta as $keyName => $fields) {
                    $data = new \stdClass();
                    foreach ($fields as $key => $val) {
                        $data->{$val->key} = $this->is_json($val->value) ? json_decode($val->value, true) : $val->value;
                    }
                    $valueMeta[$keyName] = $data;
                }
            }

            return  [
                'id' => $value->id,
                'post_type' => $value->post_type,
                'template' => $value->template,
                'title' => $value->title,
                'slug' => $value->slug,
                'slug_en' => $value->slug_en,
                'title_en' => $value->title_en,
                'type' => $value->type,
                'excerpt' => $value->excerpt,
                'image' => $value->featured_image(),
                'alt_image' => $value->alt_image,
                'alt_image_en' => $value->alt_image_en,
                'featured' => $value->featured,
                'created_at' => $value->created_at,
                'updated_at' => $value->updated_at,
                'published_at' => $value->published_at,
                'status' => $value->status,
                'view_count' => $value->view_count,
                'author' => $value->author,
                'author_admin' => [
                    'name' => $value->user->name,
                    'avatar' => $value->user->avatar
                ],
                'meta_data' => [
                    'meta_title' => $value->meta_title,
                    'meta_description' => $value->meta_description,
                    'meta_keyword' => $value->meta_keyword
                ],
                'meta' => $valueMeta,
                'category' => $value->category()->get(['id', 'name','name_en', 'slug']),
                'tag' => $value->tags->map(function ($tag) use ($lang) {
                    return $tag->getTranslation('name', $lang); // Fetch name based on locale
                })->implode(','),
                'storage_url' => config('filesystems.disks.s3.url')
            ];
        });
        return response()->json(['message' => 'Data Successfully Fetched', 'data' => $posts], 200);
    }
    public function products(){
        $products = Post::select('id','title','slug','title_en')->where('type','products')->where('status', 'publish')->get();
        return response()->json(['data' => $products], 200);
    }
    public function productJson(){
        $products = Post::select('id','title','slug','title_en')->where('type','products')->where('status', 'publish')->get();
        return response()->json($products, 200);
    }
    public function getPostDetailbySlug(Request $request)
    {
        $post = Post::where('status','publish')->with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'meta' => function ($query) {
                $query->select('id', 'post_id', 'key', 'value', 'type', 'section');
            }
        ])->where(function ($query) use ($request) {
            $query->where('slug_en', $request->slug)
                  ->orWhere('slug', $request->slug);
        })->first();
        if (!$post) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $valueMeta = [];
        if ($post->meta) {
            $meta = $post->meta->groupBy('section');

            foreach ($meta as $keyName => $fields) {
                $data = new \stdClass();
                foreach ($fields as $key => $val) {
                    $data->{$val->key} = $this->is_json($val->value) ? json_decode($val->value, true) : $val->value;
                }
                $valueMeta[$keyName] = $data;
            }
        }
        $data = [
            'id' => $post->id,
            'author' => $post->author,
            'template' => $post->template,
            'title' => $post->title,
            'title_en' => $post->title_en,
            'slug' => $post->slug,
            'slug_en' => $post->slug_en,
            'post_type' => $post->post_type,
            'type' => $post->type,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'featured' => $post->featured,
            'image' => $post->featured_image(),
            'alt_image' => $post->alt_image,
            'alt_image_en' => $post->alt_image_en,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'status' => $post->status,
            'view_count' => $post->view_count,
            'meta_data' => [
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'meta_keyword' => $post->meta_keyword
            ],
            'category' => $post->category()->get(['id', 'name','name_en', 'slug']),
            'published_at' => $post->published_at,
            'meta' => $valueMeta,
            'author' => $post->author,
            'author_admin' => [
                'name' => $post->user->name,
                'avatar' => $post->user->avatar
            ],
        ];

        return response()->json(['data' => $data], 200);
    }
    public function categories(Request $request){
        $request->validate([
            'type' => ['required', 'string'],
            'parent' => ['nullable', 'integer'],
            'lang' => ['nullable', 'string'],
            'highlight' => ['nullable', 'integer'],
        ]);
        $query = Category::select('id','type','name','name_en','slug','description','description_en')->withCount('active_posts');
        
        $query->when(request('type'), function ($q) use ($request) {
            if($request->type !== 'contact_us' && $request->type !== 'whistleblowing' && $request->type !== 'managements' ){
                return $q->where('type', $request->type)->having('active_posts_count', '>', 0);
            }else{
                return $q->where('type', $request->type);
            }
        });
        if ($request->has('sort') && $request->sort != '' && $request->has('order') && $request->order != '') {
            $query->orderBy($request->sort, $request->order);
        }else{
            $query->orderBy('sort', 'ASC');
        }
        $posts = $query->get();

        if(!$posts){
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json(['data' => $posts], 200);
    }
    function is_json($string) {
        return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
    }

}
