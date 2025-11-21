<?php

namespace App\Domains\Core\Http\Controllers\Backend;

use App\Domains\Core\Http\Requests\SidebarMenu\StoreRequest;
use App\Domains\Core\Http\Requests\SidebarMenu\UpdateRequest;
use App\Domains\Core\Models\SidebarMenu;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;

use function view;

class SidebarMenuController extends Controller
{
    public function index()
    {
        $sidebarItems = SidebarMenu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')->get();
        return view('backend.core.setting.sidebar-menu.index',compact('sidebarItems'));
    }

    public function create(){
        $sidebarMenu = new SidebarMenu();
        $sidebarItems = SidebarMenu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')->get();
        $permissions = Permission::all();
        return view('backend.core.setting.sidebar-menu.from',compact('sidebarItems','sidebarMenu','permissions'));
    }

    public function edit($id){
        $sidebarMenu = SidebarMenu::with('children','permissions')->find($id);
        $sidebarItems = SidebarMenu::whereNull('parent_id')
            ->whereNot('id', $id)
            ->orderBy('order')->get();
        $permissions = Permission::all();
        return view('backend.core.setting.sidebar-menu.from',compact('sidebarItems','sidebarMenu','permissions'));
    }

    public function store(StoreRequest $request)
    {
        /* insert and redirect */
        $sidebarMenu = SidebarMenu::create($request->validated());
        $sidebarMenu->permissions()->sync($request->input('permissions',''));
        return redirect()->route('admin.general.sidebar-menu.index')->withFlashSuccess(__('The sidebar menu was successfully created.'));
    }

    public function update(UpdateRequest $request, $id)
    {
        /* update and redirect */
        $sidebarMenu = SidebarMenu::find($id);
        $sidebarMenu->update($request->validated());
        $sidebarMenu->permissions()->sync($request->input('permissions',''));
        return redirect()->route('admin.general.sidebar-menu.index')->withFlashSuccess(__('The sidebar menu was successfully updated.'));
    }

    public function updateOrder(Request $request)
    {
        $sidebarItems = $request->input('sidebarItems');
        foreach ($sidebarItems as $order => $id) {
            SidebarMenu::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        /* delete and redirect */
        $sidebarMenu = SidebarMenu::find($id);
        $sidebarMenu->delete();
        return response()->json(['success' => true]);
    }

}
