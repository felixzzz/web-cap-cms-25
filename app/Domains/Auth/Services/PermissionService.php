<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Models\Permission;
use App\Services\BaseService;

/**
 * Class PermissionService.
 */
class PermissionService extends BaseService
{
    /**
     * PermissionService constructor.
     *
     * @param  Permission  $permission
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

    /**
     * @return mixed
     */
    public function getCategorizedPermissions()
    {
        return Permission::isMaster()
            ->with('children')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getUncategorizedPermissions()
    {
        return Permission::singular()
            ->orderBy('sort', 'asc')
            ->get();
    }
}
