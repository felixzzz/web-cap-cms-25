<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\PostCategory\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function category_filter_array()
    {

    }

    public function index(Request $request)
    {
        return $request->user()->can('admin.access.user');
//        return $request->user()->roles()->get();
    }
}
