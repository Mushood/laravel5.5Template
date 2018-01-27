<?php

Route::group(array('prefix' => 'admin','middleware' => ['auth','admin']), function()
{
    Route::resource('entity', 'AdminEntityController');
    Route::post('/entity/image/create', 'AdminEntityController@uploadImage')->name('entity.image.upload');
    Route::get('/entity/publish/{entity}', 'AdminEntityController@publish')->name('entity.publish');
    Route::get('/entity/unpublish/{entity}', 'AdminEntityController@unpublish')->name('entity.unpublish');
    
});