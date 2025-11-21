<?php

namespace App\Domains\Auth\Http\Controllers\Backend\Role;

use App\Domains\Auth\Http\Requests\Backend\Role\DeleteRoleRequest;
use App\Domains\Auth\Http\Requests\Backend\Role\EditRoleRequest;
use App\Domains\Auth\Http\Requests\Backend\Role\StoreRoleRequest;
use App\Domains\Auth\Http\Requests\Backend\Role\UpdateRoleRequest;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Services\PermissionService;
use App\Domains\Auth\Services\RoleService;

/**
 * Class RoleController.
 */
class RoleController
{
    const ENABLE_USER_TYPE_NON_ADMIN_KEY = 'app.auth_enable_user_type_non_admin';

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * RoleController constructor.
     *
     * @param  RoleService  $roleService
     * @param  PermissionService  $permissionService
     */
    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roles = Role::with('permissions:id,name,description')
            ->withCount('users')
            ->get();

        return view('backend.auth.role.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $enable_user_type_non_admin = config(self::ENABLE_USER_TYPE_NON_ADMIN_KEY);
        return view('backend.auth.role.create')
            ->with([
                'categories' => $this->permissionService->getCategorizedPermissions(),
                'general' => $this->permissionService->getUncategorizedPermissions(),
                'enable_user_type_non_admin' => $enable_user_type_non_admin,
            ]);
    }

    /**
     * @param  StoreRoleRequest  $request
     * @return mixed
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleService->store($request->validated());
        return redirect()->route('admin.role.index')->withFlashSuccess(__('The role was successfully created.'));
    }

    /**
     * @param  EditRoleRequest  $request
     * @param  Role  $role
     * @return mixed
     */
    public function edit(EditRoleRequest $request, Role $role)
    {
        $enable_user_type_non_admin = config(self::ENABLE_USER_TYPE_NON_ADMIN_KEY);

        return view('backend.auth.role.edit')
            ->with([
                'categories' => $this->permissionService->getCategorizedPermissions(),
                'general' => $this->permissionService->getUncategorizedPermissions(),
                'role' => $role,
                'usedPermissions' => $role->permissions->modelKeys(),
                'enable_user_type_non_admin' => $enable_user_type_non_admin,
            ]);
    }

    public function show(Role $role)
    {
        $enable_user_type_non_admin = config(self::ENABLE_USER_TYPE_NON_ADMIN_KEY);
        $role->load('users');
        return view('backend.auth.role.show')
            ->with([
                'categories' => $this->permissionService->getCategorizedPermissions(),
                'general' => $this->permissionService->getUncategorizedPermissions(),
                'role' => $role,
                'permissions' => $role->getPermissionDescriptions(),
                'usedPermissions' => $role->permissions->modelKeys(),
                'enable_user_type_non_admin' => $enable_user_type_non_admin,
            ]);
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     * @return mixed
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->validated());

        return redirect()->route('admin.role.index')->withFlashSuccess(__('The role was successfully updated.'));
    }

    /**
     * @param  DeleteRoleRequest  $request
     * @param  Role  $role
     * @return mixed
     *
     * @throws \Exception
     */
    public function destroy(DeleteRoleRequest $request, Role $role)
    {
        $this->roleService->destroy($role);

        return redirect()->route('admin.role.index')->withFlashSuccess(__('The role was successfully deleted.'));
    }
}
