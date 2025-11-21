<?php

namespace App\Domains\Post\Http\Controllers\Backend;

use App\Domains\Post\Http\Controllers\Controller;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\ComponentService;
use App\Domains\Post\Services\PostMetaService;
use App\Domains\Post\Services\PostService;

class BackendController extends Controller
{
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
    }

    public function rules()
    {
        return [
            'title' => ['required', 'max:100'],
            'slug' => ['max:100'],
            'excerpt' => ['max:255'],
            'content' => ['string'],
            'status' => ['required', 'in:'.Post::STATUS_PUBLISH.','.Post::STATUS_DRAFT],
            'published_at' => ['nullable','date'],
            'template' => ['nullable','string'],
        ];
    }

    public function promo_rules()
    {
        return [
            'title' => ['required', 'max:100'],
            'slug' => ['max:100'],
            'excerpt' => ['max:255'],
            'content' => [''],
            'status' => ['required', 'in:'.Post::STATUS_PUBLISH.','.Post::STATUS_DRAFT],
            'category' => [],
            'published_at' => ['required','date'],
            'expired_at' => ['required','date'],
        ];
    }
}
