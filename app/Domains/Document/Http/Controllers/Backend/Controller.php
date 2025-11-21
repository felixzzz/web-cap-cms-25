<?php

namespace App\Domains\Document\Http\Controllers\Backend;

use App\Domains\PostCategory\Services\CategoryService;

class Controller extends \App\Domains\Post\Http\Controllers\Controller
{
    protected CategoryService $categoryService;

    /**
     * PostCategoryController constructor.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
}
