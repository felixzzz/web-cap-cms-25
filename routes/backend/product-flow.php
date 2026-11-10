<?php

use App\Http\Controllers\Backend\ProductFlowBuilderController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'product-flow',
    'as' => 'product-flow.',
], function () {
    Route::get('/', [ProductFlowBuilderController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Product Flow Builder'), route('admin.product-flow.index'));
        });
});

