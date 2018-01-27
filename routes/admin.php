<?php

Route::group(array('prefix' => 'admin'), function()
{

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

});
