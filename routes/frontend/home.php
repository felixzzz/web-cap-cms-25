<?php

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    })
    ->name('index');

