<?php

namespace App\Domains\Post\Http\Controllers;

use App\Domains\PostCategory\Models\Category;
use App\Traits\Extra;
use App\Traits\Options;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Str;

class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Options, Extra;

    /**
     * @param mixed $type
     * @return mixed
     */
    public function getCategories(mixed $type)
    {
        $categories = Category::whereNotNull('slug');
        if ($type != null) {
            $categories = $categories->where('type', $type);
        } else {
            $categories = $categories->whereNull('type');
        }
        $categories = $categories->get();
        return $categories;
    }

    /**
     * get Post template by name
     *
     * @param string $type
     * @param string $name
     *
     * @return array
     */
    public function getTemplate(string $type, $folder = 'pages')
    {
        $singleTemplate = [];
        $templates = $this->getTemplates($type, $folder);
        foreach ($templates as $page) {
            if (array_intersect_assoc($page, ['name' => $type])) {
                $singleTemplate = $page;

                break;
            }
        }

        return $singleTemplate;
    }

    public function getTemplate2(string $type, $name)
    {
        $singleTemplate = [];
        $templates = $this->getTemplates($type);
        foreach ($templates as $page) {
            if (array_intersect_assoc($page, ['name' => $name])) {
                $singleTemplate = $page;

                break;
            }
        }

        return $singleTemplate;
    }

    /**
     * get Post templates
     *
     * @param string $type
     *
     * @return array
     */
    private function getTemplates(string $type, $folder)
    {
        $filename = Str::slug($type, '-');
        $path = 'templates/'.$folder.'/' . $filename . '.json';
   
        $templates = [];
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $templates = json_decode($fileJson, true);
        }

        return $templates;
    }
}
