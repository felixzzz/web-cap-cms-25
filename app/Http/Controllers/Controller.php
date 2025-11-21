<?php

namespace App\Http\Controllers;

use App\Domains\Post\Models\Category;
use App\Traits\Extra;
use App\Traits\Options;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Models\Permission;

/**
 * Class Controller.
 */
class Controller extends BaseController
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
     * @param mixed $roles
     * @return array
     */
    protected function permission_format(mixed $roles = null): array
    {
        $permission = Permission::all();
        $arr = [];
        foreach ($permission as $key => $item) {
            $name = str_replace(['create', 'update', 'read', 'delete', 'manage'], '', $item->name);
            $arr[$key] = trim($name);

        }
        $arr2 = [];
        $name = array_unique($arr);
        foreach ($name as $key => $nama) {
            $permit = [
                $nama . ' create',
                $nama . ' read',
                $nama . ' update',
                $nama . ' delete',
                $nama . ' manage',
            ];

            $find = Permission::whereNotNull('name');
            $find->whereIn('name', $permit);
            $arr2[$key]['name'] = ucwords($nama);
            if ($roles != null) {
                $result = $find->get();
                $ars = [];
                foreach ($result as $kis => $item) {
                    $check = $item->roles()->where('id', $roles->id)->exists();
                    if ($check) {
                        $ars[] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'checked' => true
                        ];
                    } else {
                        $ars[] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'checked' => false
                        ];
                    }
                }
                $arr2[$key]['permission'] = $ars;
            } else {
                $result = $find->get()->pluck('name');
                $arr2[$key]['permission'] = $result;
            }
        }
        return $arr2;
    }
}
