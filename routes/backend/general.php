<?php

use App\Domains\Core\Http\Controllers\Backend\GeneralController;
use App\Domains\Core\Http\Controllers\Backend\SidebarMenuController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
    'prefix' => 'general',
    'as' => 'general.',
], function () {
    Route::get('/', [GeneralController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('General Settings'), route('admin.dashboard'));
        });

    Route::post('/', [GeneralController::class, 'store'])->name('store');
    Route::group([
        'prefix' => 'sidebar-menu',
        'as' => 'sidebar-menu.',
    ], function () {
        Route::get('/', [SidebarMenuController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.general.index')
                    ->push(__('Dashboard'), route('admin.general.index'));
            });
        Route::get('/create', [SidebarMenuController::class, 'create'])->name('create')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.general.sidebar-menu.index')
                    ->push(__('Sidebar Menu'), route('admin.general.sidebar-menu.index'));
            });
        Route::get('/edit/{id}', [SidebarMenuController::class, 'edit'])->name('edit')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.general.sidebar-menu.index')
                    ->push(__('Sidebar Menu'), route('admin.general.sidebar-menu.index'));
            });
        Route::post('', [SidebarMenuController::class, 'store'])->name('store');
        Route::put('/update-order', [SidebarMenuController::class, 'updateOrder'])->name('update-order');
        Route::put('/{id}', [SidebarMenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [SidebarMenuController::class, 'destroy'])->name('destroy');
    });
});
