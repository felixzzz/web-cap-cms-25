<?php

namespace App\Traits;

trait GateRequest
{
    public function authorizePermission($permission)
    {
        if ($this->authorizeAuthenticate()) {
            $user = auth()->user();
            if ($user->can($permission)) {
                return true;
            }
        }
        return false;
    }

    public function authorizeAuthenticate(): bool
    {
        return auth()->check() ? true : false;
    }
}
