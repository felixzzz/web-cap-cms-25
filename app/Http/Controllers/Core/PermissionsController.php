<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = $this->permission_format();
        return view('users.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);
        $check = Permission::where('name', 'like', '%'.$request->name.'%')->exists();
        if (!$check) {
            $permissions = ['read', 'update', 'create', 'delete', 'manage'];
            foreach ($permissions as $item) {
                $name = strtolower(trim($request->name.' '.$item));
                Permission::create([
                    'name' => $name
                ]);
            }
            flash('Success creating permission', 'success');
        } else {
            flash('Permission was exists', 'danger');
        }
        return back();
    }

}
