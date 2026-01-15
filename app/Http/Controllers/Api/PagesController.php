<?php

namespace App\Http\Controllers\Api;

use App\Domains\Document\Models\Document;
use App\Domains\Post\Models\Package;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostMeta;
use App\Domains\PostCategory\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Post\Services\PostService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
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
            'search' => ['nullable', 'string'],
            'dynamic' => ['nullable'],
            'meta' => ['nullable'],
            'sort' => ['nullable'],
            'order' => ['nullable'],
            'parent_slug' => ['nullable'],
            'select' => ['nullable', 'string']
        ]);

        $query = Post::where('status', 'publish')
            ->where('type', Post::TYPE_PAGE);
        if ($request->has('select') && $request->select != '') {
            $columns = explode(',', $request->select);
            $query->select($columns);
        } else {
            $query->select('*')->with('meta');
        }
        if ($request->has('search') && $request->search != '') {
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$request->search}%"]);
        }
        if ($request->has('dynamic') && $request->dynamic != '') {
            $query->where('pages_dynamic', $request->dynamic);
        }
        if ($request->has('is_parent') && $request->is_parent != '') {
            if ($request->is_parent == 'true') {
                $query->where('template', 'business_solusions_dynamic')->where(function ($query) {
                    $query->where('parent', '')
                        ->orWhereNull('parent');
                });
            }
        }
        if ($request->has('parent_slug') && $request->parent_slug != '') {
            $query->whereHas('parent', function ($query) use ($request) {
                $query->where('slug', $request->parent_slug);
            });
        }

        if ($request->has('sort') && $request->sort != '' && $request->has('order') && $request->order != '') {
            $query->orderBy($request->sort, $request->order);
        }

        $posts = $query->paginate($request->limit ?? 10);

        if ($request->has('select') && $request->select != '') {
            return response()->json(['message' => 'Data Successfully Fetched', 'data' => $posts], 200);
        }

        $posts->getCollection()->transform(function ($value) {
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

            return [
                'id' => $value->id,
                'title' => $value->title,
                'template' => $value->template,
                'parent' => $value->parent,
                'dynamic' => $value->pages_dynamic,
                'slug' => $value->slug,
                'slug_en' => $value->slug_en,
                'site_url' => $value->site_url,
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
                'parent_data' => $value->parent ? [
                    'parent_id' => $value->parent,
                    'parent_slug' => $value->parent_data->slug ?? ''
                ] : null,
                'meta_data' => [
                    'meta_title' => $value->meta_title,
                    'meta_description' => $value->meta_description,
                    'meta_keyword' => $value->meta_keyword
                ],
                'meta' => $valueMeta,
                'storage_url' => config('filesystems.default') == 's3' ? config('filesystems.disks.s3.url') : config('app.url') . '/storage'
            ];
        });
        return response()->json(['message' => 'Data Successfully Fetched', 'data' => $posts], 200);
    }
    public function pageDynamic(Request $request)
    {
        // Fetch posts with children
        $posts = Post::with('children')
            ->where('status', 'publish')
            ->where('type', Post::TYPE_PAGE)
            ->where('pages_dynamic', 'yes')
            ->whereDoesntHave('parent_data', function ($q) {
                $q->where('slug', 'sustainability');
            })
            ->where('template', 'business_solusions_dynamic')->where(function ($query) {
                $query->where('parent', '')
                    ->orWhereNull('parent');
            })
            ->orderBy('sort')
            ->get();

        // Transform the posts collection
        $transformedPosts = $posts->transform(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'title_en' => $post->title_en,
                'template' => $post->template,
                'parent' => $post->parent,
                'dynamic' => $post->pages_dynamic,
                'published_at' => $post->published_at,
                'slug' => $post->slug,
                'slug_en' => $post->slug_en,
                'site_url' => $post->site_url,
                'children' => $post->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'title' => $child->title,
                        'template' => $child->template,
                        'parent' => $child->parent,
                        'dynamic' => $child->pages_dynamic,
                        'slug' => $child->slug,
                        'site_url' => $child->site_url,
                    ];
                }),
            ];
        });

        // Return response
        return response()->json(['message' => 'Data Successfully Fetched', 'data' => $transformedPosts], 200);
    }
    public function pageSlugs(Request $request)
    {
        $posts = Post::select('id', 'slug', 'template', 'pages_dynamic', 'title', 'status')
            ->where('type', Post::TYPE_PAGE)
            ->where('status', 'publish')
            ->get();

        if (!count($posts)) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json(['data' => $posts], 200);

    }
    public function getDetailbySlug(Request $request, $slug)
    {
        Log::info("DEBUG: getDetailbySlug called with $slug");
        $post = Post::where('type', Post::TYPE_PAGE)
            ->where('status', 'publish')
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->first();

        if (!$post) {
            return response()->json(['message' => 'not found'], 404);
        }
        if ($post) {
            $meta = $post->meta->groupBy('section');

            foreach ($meta as $keyName => $fields) {
                // Return status for each section to see where it stops
                // return response()->json(['message' => 'DEBUG ALIVE INSIDE LOOP', 'key' => $keyName], 200); 
                // We know it entered 'banner'.

                $data = new \stdClass();
                file_put_contents('/tmp/debug.log', "Processing Section: $keyName\n", FILE_APPEND);
                foreach ($fields as $key => $val) {
                    if ($keyName === 'technology_items') {
                        file_put_contents('/tmp/debug.log', "  Processing Field: {$val->key}\n", FILE_APPEND);
                    }
                    $data->{$val->key} = $this->is_json($val->value) ? json_decode($val->value, true) : $val->value;
                    if ($keyName === 'banner' && $val->key === 'logo_en') {
                        $data->{$val->key} = $fields->firstWhere('key', 'logo_id')->value ?? null;
                    }
                }
                $valueMeta[$keyName] = $data;
            }
        }

        $data = [
            'id' => $post->id,
            'template' => $post->template,
            'title' => $post->title,
            'title_en' => $post->title_en,
            'slug' => $post->slug,
            'slug_en' => $post->slug_en,
            'site_url' => $post->site_url,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'published_at' => $post->published_at,
            'meta_seo_title' => $post->meta_seo_title,
            'meta_seo_description' => $post->meta_seo_description,
            'meta_keyword' => $post->meta_keyword,
            'meta' => $valueMeta,
            'storage_url' => config('filesystems.default') == 's3' ? config('filesystems.disks.s3.url') : config('app.url') . '/storage'
        ];
        return response()->json(['data' => $data], 200);

    }
    public function getDatabySection($template, $section)
    {
        $post = Post::select('id', 'type', 'slug')
            ->where('status', 'publish')
            ->where('type', Post::TYPE_PAGE)
            ->where('template', $template)
            ->first();

        if (!$post) {
            return response()->json(['message' => 'not found'], 404);
        }

        $valueMeta = [];
        if ($post) {
            $post->meta->transform(function ($meta) use ($post) {
                if ($meta->type == 'repeater') {
                    $meta->value = collect(json_decode($meta->value, true))->map(function ($item) use ($meta) {
                        return collect($item)->map(function ($value, $key) use ($meta) {
                            if (str_contains($key, 'custom_post_')) {
                                $type = explode("_", $key);
                                $custom_post = Post::select('id', 'title', 'slug')
                                    ->with([
                                            'meta' => function ($query) {
                                                $query->where('section', 'hero_banner');
                                            }
                                        ])
                                    ->where('id', $value)
                                    ->first();

                                return [
                                    'id' => $custom_post->id,
                                    'title' => $custom_post->title,
                                    'slug' => $custom_post->slug,
                                    'meta' => $custom_post->meta->groupBy('section'),
                                ];
                            }
                            return $value;
                        });
                    })->toArray();
                }
                return $meta;
            });
            $meta = $post->meta->where('section', $section)->groupBy('section');

            foreach ($meta as $keyName => $fields) {
                $data = new \stdClass();
                foreach ($fields as $key => $val) {
                    $data->{$val->key} = $this->is_json($val->value) ? json_decode($val->value, true) : $val->value;
                }
                $valueMeta[$keyName] = $data;
            }
        }

        return response()->json(['data' => $valueMeta], 200);

    }
    public function search(Request $request)
    {
        // Validate the request
        $request->validate([
            'limit' => ['required', 'numeric'],
            'search' => ['required', 'string'],
            'lang' => ['required', 'string'],
            'type' => ['nullable'],
        ]);
        $lang = strtolower($request->lang);

        // Initialize the query for posts
        $query = Post::select('posts.id', 'posts.slug', 'posts.title_en', 'posts.title', 'posts.template', 'posts.type', 'posts.post_type', 'posts.site_url')
            ->where('posts.type', 'page')
            ->where('status', 'publish')
            ->orderBy('posts.id', 'desc');


        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search); // Convert search term to lowercase

            // Apply search based on the language
            if ($lang === 'id') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(posts.title) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereHas('meta', function ($q) use ($searchTerm) {
                            $q->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                                ->whereIn('key', ['title_id', 'content_id', 'name_id', 'description_id', 'content_id']);
                        });
                });
            } elseif ($lang === 'en') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(posts.title_en) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereHas('meta', function ($q) use ($searchTerm) {
                            $q->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                                ->whereIn('key', ['title_en', 'content_en', 'name_en', 'description_en', 'content_en']);
                        });
                });
            }
        }


        if ($lang === 'id') {
            $posts = $query->with([
                'meta' => function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                        ->whereIn('key', ['title_id', 'content_id', 'document_id', 'name_id', 'file_title_id', 'document_title_id', 'description_id', 'content_id']);
                }
            ])->limit($request->limit)->get();
        }
        if ($lang === 'en') {
            $posts = $query->with([
                'meta' => function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                        ->whereIn('key', ['title_en', 'content_en', 'document_en', 'name_en', 'file_title_en', 'document_title_en', 'description_en', 'content_en']);
                }
            ])->limit($request->limit)->get();
        }

        // Prepare data for response
        $pages = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'slug' => $post->slug,
                'site_url' => $post->site_url,
                'title_en' => $post->title_en,
                'title' => $post->title,
                'template' => $post->template,
                'type' => $post->type,
                'post_type' => $post->post_type,
                'meta' => $post->meta->map(function ($meta) {
                    return [
                        'section' => $meta->section,
                        'key' => $meta->key,
                        'value' => $meta->value,
                    ];
                }),
            ];
        });

        $reports = $this->searchReport($request->all(), $lang);
        $publication = $this->searchPublication($request->all(), $lang);
        $article = $this->searchArticle($request->all(), $lang);
        $press = $this->searchPress($request->all(), $lang);

        $type = $request->type ?? [];
        $allResults = [];
        if (in_array("pages", $type)) {
            $allResults = array_merge($allResults, $this->normalizeResults($pages->toArray(), 'pages'));
        }
        if (in_array("reports", $type)) {
            $allResults = array_merge($allResults, $this->normalizeResults($reports, 'reports'));
        }
        if (in_array("publication", $type)) {
            $allResults = array_merge($allResults, $this->normalizeResults($publication, 'publication'));
        }

        if (in_array("article", $type)) {
            $allResults = array_merge($allResults, $this->normalizeResults($article->toArray(), 'article'));
        }

        if (in_array("press", $type)) {
            $allResults = array_merge($allResults, $this->normalizeResults($press, 'press'));
        }
        if (count($type) === 0) {
            $allResults = array_merge(
                $this->normalizeResults($pages->toArray(), 'pages'),
                $this->normalizeResults($reports, 'reports'),
                $this->normalizeResults($publication, 'publication'),
                $this->normalizeResults($article->toArray(), 'article'),
                $this->normalizeResults($press, 'press')
            );
        }

        // Create a paginator for the combined results
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = $request->limit ?? 5;
        $collection = collect($allResults);
        $paginatedResults = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage)->values(),
            $collection->count(),
            $request->limit ?? 5,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        // Prepare data for response
        return response()->json([
            'results' => [
                'data' => $paginatedResults->items(),
                'current_page' => $paginatedResults->currentPage(),
                'from' => $paginatedResults->firstItem(),
                'last_page' => $paginatedResults->lastPage(),
                'per_page' => $paginatedResults->perPage(),
                'to' => $paginatedResults->lastItem(),
                'total' => $paginatedResults->total(),
            ]
        ], 200);
    }
    function searchPress($data, $lang)
    {
        $searchTerm = strtolower($data['search']);
        $query = Document::select('*')
            ->where('page', 'news');

        if (!empty($searchTerm)) {
            if ($lang === 'id') {
                $query->whereRaw('LOWER(document_name_id) LIKE ?', ["%{$searchTerm}%"]);
            } elseif ($lang === 'en') {
                $query->whereRaw('LOWER(document_name_en) LIKE ?', ["%{$searchTerm}%"]);
            }
        }
        return $query->limit($data['limit'])->get()->toArray();
    }
    function searchReport($data, $lang)
    {
        $searchTerm = strtolower($data['search']);
        $query = Document::select('*')
            ->where(function ($query) {
                $query->where('page', 'sustainability_reports')
                    ->orWhere('page', 'investor_reports');
            });

        if (!empty($searchTerm)) {
            if ($lang === 'id') {
                $query->whereRaw('LOWER(document_name_id) LIKE ?', ["%{$searchTerm}%"]);
            } elseif ($lang === 'en') {
                $query->whereRaw('LOWER(document_name_en) LIKE ?', ["%{$searchTerm}%"]);
            }
        }
        return $query->limit($data['limit'])->get()->toArray();
    }
    function searchPublication($data, $lang)
    {
        $searchTerm = strtolower($data['search']);
        $query = Document::select('*')
            ->where('page', 'investor_publicatios')
            ->where('section', 'document');

        if (!empty($searchTerm)) {
            if ($lang === 'id') {
                $query->whereRaw('LOWER(document_name_id) LIKE ?', ["%{$searchTerm}%"]);
            } elseif ($lang === 'en') {
                $query->whereRaw('LOWER(document_name_en) LIKE ?', ["%{$searchTerm}%"]);
            }
        }
        return $query->limit($data['limit'])->get()->toArray();
    }
    function searchArticle($data, $lang)
    {
        $searchTerm = strtolower($data['search']);
        $query = Post::select('posts.id', 'posts.slug', 'posts.title_en', 'posts.title', 'posts.template', 'posts.type', 'posts.post_type', 'posts.published_at')
            ->where('posts.type', 'news')
            ->where('status', 'publish')->where('published_at', '<=', date('Y-m-d H:i:s'))
            ->with('meta')
            ->orderBy('posts.id', 'desc');

        if (!empty($searchTerm)) {
            if ($lang === 'id') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(posts.title) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereHas('meta', function ($q) use ($searchTerm) {
                            $q->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                                ->whereIn('key', ['title_id', 'content_id', 'name_id', 'description_id', 'content_id']);
                        });
                });
            } elseif ($lang === 'en') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(posts.title_en) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereHas('meta', function ($q) use ($searchTerm) {
                            $q->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])
                                ->whereIn('key', ['title_en', 'content_en', 'name_en', 'description_en', 'content_en']);
                        });
                });
            }
        }
        $query->with([
            'meta_result' => function ($query) use ($searchTerm) {
                $query->where('key', 'content_id')->orWhere('key', 'content_en');
            }
        ]);

        if ($lang === 'id') {
            $posts = $query->with([
                'meta' => function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])->whereIn('key', ['title_id', 'content_id']);
                }
            ])->limit($data['limit'])->get();
        }
        if ($lang === 'en') {
            $posts = $query->with([
                'meta' => function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(value) LIKE ?', ["%{$searchTerm}%"])->whereIn('key', ['title_en', 'content_en']);
                }
            ])->limit($data['limit'])->get();
        }

        // Prepare data for response
        return $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'slug' => $post->slug,
                'title_en' => $post->title_en,
                'title' => $post->title,
                'template' => $post->template,
                'published_at' => $post->published_at,
                'type' => $post->type,
                'post_type' => $post->post_type,
                'meta_result' => $post->meta_result->map(function ($meta) {
                    return [
                        'section' => $meta->section,
                        'key' => $meta->key,
                        'value' => $meta->value,
                    ];
                }),
                'meta' => $post->meta->map(function ($meta) {
                    return [
                        'section' => $meta->section,
                        'key' => $meta->key,
                        'value' => $meta->value,
                    ];
                }),
            ];
        });
    }
    private function normalizeResults(array $results, string $type): array
    {
        $normalizedResults = [];
        foreach ($results as $item) {
            $normalizedResults[] = [
                'id' => $item['id'] ?? null,
                'type' => $type,
                'page_type' => $item['template'] ?? null,
                'title_id' => $item['title'] ?? $item['document_name_id'] ?? null,
                'title_en' => $item['title'] ?? $item['document_name_en'] ?? null,
                'slug' => $item['slug'] ?? null,
                'site_url' => $item['site_url'] ?? null,
                'category' => $item['category'] ?? null,
                'section' => $item['section'] ?? null,
                'published_at' => $item['published_at'] ?? null,
                'author' => $item['author'] ?? null,
                'publisher' => $item['publisher'] ?? null,
                'release_year' => $item['release_year'] ?? null,
                'meta' => $item['meta'] ?? [],
                'meta_result' => $item['meta_result'] ?? [],
                'document_file_id' => $item['document_file_id'] ?? null,
                'document_file_en' => $item['document_file_en'] ?? null,
                'image' => $item['image'] ?? null,
                'alt_image' => $item['alt_image'] ?? null,
                'alt_image_en' => $item['alt_image_en'] ?? null,
            ];
        }
        return $normalizedResults;
    }

    function is_json($string)
    {
        return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
    }


}
