<?php
use \Illuminate\Support\Facades\Route;

Route::prefix('form')->group(function () {
    Route::get('/', [\App\Http\Controllers\Backend\Form\FormController::class, 'index'])->name('form')
        ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
            $trail->parent('admin.form')
                ->push(__('Form'));
        });

    Route::get('/create', [\App\Http\Controllers\Backend\Form\FormController::class, 'create'])->name('form.create')
        ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
            $trail->parent('admin.form')
                ->push(__('Form'), route('admin.form'))
                ->push(__('Create Form'));
        });

    Route::post('/store', [\App\Http\Controllers\Backend\Form\FormController::class, 'store'])->name('form.store');

    Route::prefix('{form}')->group(function () {
        Route::get('/show', [\App\Http\Controllers\Backend\Form\FormController::class, 'show'])->name('form.show')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail, \App\Models\Form\Form $form) {
                $trail->parent('admin.form')
                    ->push(__('Form'), route('admin.form'))
                    ->push(__('Form '.$form->name));
            });

        Route::get('/edit', [\App\Http\Controllers\Backend\Form\FormController::class, 'edit'])->name('form.edit')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
                $trail->parent('admin.form')
                    ->push(__('Form'), route('admin.form'))
                    ->push(__('Edit Form'));
            });

        Route::patch('/update', [\App\Http\Controllers\Backend\Form\FormController::class, 'update'])->name('form.update');

        Route::delete('/destroy', [\App\Http\Controllers\Backend\Form\FormController::class, 'destroy'])->name('form.destroy');
    });

    Route::prefix('submission/{form}')->group(function () {
        Route::get('/', [\App\Http\Controllers\Backend\Form\SubmissionController::class, 'index'])->name('form.submission');
        Route::get('/show/{submission}', [\App\Http\Controllers\Backend\Form\SubmissionController::class, 'show'])->name('form.submission.show');
        Route::delete('/delete/{submission}', [\App\Http\Controllers\Backend\Form\SubmissionController::class, 'delete'])->name('form.submission.destroy');
    });

    Route::prefix('fields/{form}')->group(function () {
        Route::get('/', [\App\Http\Controllers\Backend\Form\FieldController::class, 'indexForm'])->name('form.field')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail, \App\Models\Form\Form $form) {
                $trail->parent('admin.form')
                    ->push(__('Fields '), route('admin.form'))
                    ->push(__('Form '.$form->name));
            });

        Route::get('/create', [\App\Http\Controllers\Backend\Form\FieldController::class, 'createForm'])->name('form.field.create')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail, $form) {
                $trail->parent('admin.form')
                    ->push(__('Fields '), route('admin.form'))
                    ->push(__(''));
            });

        Route::post('/create', [\App\Http\Controllers\Backend\Form\FieldController::class, 'storeForm'])->name('form.field.store');
    });

    Route::prefix('field-form/{field}')->group(function () {
        Route::get('/edit', [\App\Http\Controllers\Backend\Form\FieldController::class, 'editForm'])->name('form.field.edit')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
                $trail->parent('admin.form')
                    ->push(__('Fields '), route('admin.form'))
                    ->push(__('Edit'));
            });
        Route::get('/show', [\App\Http\Controllers\Backend\Form\FieldController::class, 'showForm'])->name('form.field.show')
            ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
                $trail->parent('admin.form')
                    ->push(__('Fields '), route('admin.form'))
                    ->push(__('Edit'));
            });

        Route::patch('/update', [\App\Http\Controllers\Backend\Form\FieldController::class, 'updateForm'])->name('form.field.update');
        Route::delete('/delete', [\App\Http\Controllers\Backend\Form\FieldController::class, 'deleteForm'])->name('form.field.destroy');
    });
});
