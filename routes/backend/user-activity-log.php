<?php

use Tabuna\Breadcrumbs\Trail;
use App\Domains\UserActivityLog\Http\Controllers\Backend\UserActivityLogController;

// All route names are prefixed with 'admin.'.
Route::group([
    'prefix' => 'user-activity-log',
    'as' => 'user-activity-log.',
], function () {
    Route::get('/', [UserActivityLogController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('General Settings'), route('admin.dashboard'));
        });
    Route::get('/{activity}', [UserActivityLogController::class, 'show'])
        ->name('show')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.user-activity-log.index')
                ->push(__('User Activity Log'), route('admin.user-activity-log.index'));
        });
});
