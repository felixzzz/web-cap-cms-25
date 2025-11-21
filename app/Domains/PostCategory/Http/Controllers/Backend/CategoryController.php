<?php

namespace App\Domains\PostCategory\Http\Controllers\Backend;

use App\Domains\Post\Models\PostCategories;
use App\Domains\PostCategory\Http\Requests\Backend\StoreCategoryRequest;
use App\Domains\PostCategory\Models\Category;
use App\Domains\PostCategory\Services\CategoryService;
use App\Exceptions\GeneralException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Log;


class CategoryController extends Controller
{
    /**
     * PostCategoryController constructor.
     */
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct($categoryService);
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($type)
    {
        // Gate::authorize("admin.access.{$type}.read");
        return view("backend.postCategory.index", compact('type'));
    }

    /**
     * @param $type
     * @return mixed
     */
    public function create($type)
    {
        // Gate::authorize("admin.access.{$type}.create");

        return view(
            'backend.postCategory.create',
            compact('type')
        );
    }

    /**
     * @param Category $category
     * @return mixed
     */
    public function show(Category $category, $type)
    {
        return view('backend.postCategory.show', compact('category', 'type'));
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreCategoryRequest $request, $type)
    {
        // Gate::authorize("admin.access.{$type}.create");

        $requestValidated = $request->all();

        DB::beginTransaction();

        try {
            $postCategory = $this->categoryService->createCategory($requestValidated, $type);

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            throw $exception;
        }

        return redirect()
            ->route('admin.category.index', ['type' => $type])
            ->withFlashSuccess(__('The Category was successfully created.'));
    }

    /**
     * @param string $type
     * @param Category $postCategory
     * @return mixed
     */
    public function edit(Category $category)
    {
        // Gate::authorize("admin.access.{$category->type}.update");

        $type = $category->type;
        return view('backend.postCategory.edit', compact('category', 'type'));
    }

    /**
     * @param Request $request
     * @param string $type
     * @param Category $category
     * @return mixed
     * @throws \Throwable
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        // Gate::authorize("admin.access.{$category->type}.update");

        $requestValidated = $request->validated();

        DB::beginTransaction();

        try {
            $category = $this->categoryService->updateCategory($category, $requestValidated);

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            throw $exception;
        }

        return redirect()->route('admin.category.index', ['type' => $category->type])
            ->withFlashSuccess(__('Category was successfully updated.'));
    }

    /**
     * @param Request $request
     * @param Category $category
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function delete(Request $request, Category $category)
    {
        // Gate::authorize("admin.access.{$category->type}.delete");

        $type = $category->type;
        $checkPost = PostCategories::where('category_id', $category->id)->get();
        if (count($checkPost) > 0) {
            throw new GeneralException('You can’t delete category that is used in active post');
        }

        if (! $category->delete() ) {
            throw new GeneralException('There was a problem deleting this promo. Please try again.');
        }

        return redirect()->route('admin.category.index', $type)->withFlashSuccess(__( 'Category was successfully deleted.'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trashed($type)
    {
        // Gate::authorize("admin.access.{$type}.delete");

        return view("backend.postCategory.deleted", compact('type'));
    }

    /**
     * @param Int $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function restore(int $deletedPostId)
    {
        $deletedPost = Category::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        Gate::authorize("admin.access.{$type}.delete");

        if (! $deletedPost->restore()) {
            throw new GeneralException(__('There was a problem restoring this Promo. Please try again.'));
        }

        return redirect()->route('admin.category.index', $type)->withFlashSuccess(__($type . ' ' . $deletedPost->title . ' was successfully restored.'));
    }

    /**
     * @param Int $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(int $deletedPostId)
    {
        $deletedPost = Category::onlyTrashed()->findOrFail($deletedPostId);
        $type = $deletedPost->type;

        Gate::authorize("admin.access.{$type}.delete");

        if (! $deletedPost->forceDelete()) {
            throw new GeneralException(__('There was a problem permanently deleting this promo. Please try again.'));
        }

        return redirect()->route('admin.category.deleted', $type)->withFlashSuccess(__('The ' . $type . ' was permanently deleted.'));
    }

}
