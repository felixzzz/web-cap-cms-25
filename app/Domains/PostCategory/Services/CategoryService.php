<?php

namespace App\Domains\PostCategory\Services;

use App\Domains\Post\Models\Post;
use App\Domains\PostCategory\Models\Category;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Str;
use Log;

/**
 * Class PostCategoryService.
 */
class CategoryService extends BaseService
{
    /**
     * PostCategoryService constructor.
     *
     * @param  Category  $post
     */
    public function __construct(Category $postCategory)
    {
        $this->model = $postCategory;
    }

    /**
     * @param  array  $data
     *
     * @return Category
     */

    public function createCategory(array $data = [], $type = null): Category
    {
        $data['slug'] = $this->cekSlug(isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']));

        try {
            $user = auth()->user();
            $createData = ($data) ?
                array_merge(
                    [ 'type' => $type, 'user_id' => $user->id ],
                    $data
                ) :
                [];
            $postCategory = $this->model::create($createData);
        } catch (Exception $e) {
            Log::error($e);

            throw new GeneralException(__('There was a problem creating this page. Please try again.'));
        }

        return $postCategory;
    }

    private function cekSlug($slug, $id = null)
    {
        if (! Category::where('slug', $slug)
            ->when($id, function ($q, $id) {
                $q->where('id', '!=', $id);
            })
            ->first()) {
            return $slug;
        }

        return $this->cekSlug($slug."-".date('YmdHis'));
    }


    /**
     * @param  Category  $postCategory
     * @param  array  $data
     *
     * @return Category
     * @throws \Throwable
     */

    public function updateCategory(Category $postCategory, array $data = []): Category
    {
        $data['slug'] = isset($data['slug']) ? $data['slug'] : Str::slug($data['name'] ?? "");

        try {
            $postCategory->update($data);
        } catch (Exception $e) {
            Log::error('PostCategoryService@updateCategory: ', ['error' => $e]);

            throw new GeneralException(__('There was a problem updating this category. Please try again.'));
        }

        return $postCategory;
    }

    /**
     * @param $featured_image
     * @return string
     */
    public function featured_image_path($featured_image): string
    {
        $resource = $featured_image;
        $name = Str::uuid(2)->toString() . '.jpg';
        $image = $featured_image->storePubliclyAs('images/post', $name, config('filesystems.default'));
        $path = 'images/post/' . $name;

        return $path;
    }
}
