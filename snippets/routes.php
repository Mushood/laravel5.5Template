<?php

Route::get('/entity', 'EntityController@index')->name('entity.front.index');
Route::get('/entity/{slug}', 'EntityController@show')->name('entity.front.show');

Route::group(array('prefix' => 'admin','middleware' => ['auth','admin']), function()
{
    Route::resource('entity', 'AdminEntityController');
    Route::get('/entity/export/list', 'AdminEntityController@export')->name('entity.export');
    Route::post('/entity/search/list', 'AdminEntityController@search')->name('entity.search');
    Route::post('/entity/bulk/action', 'AdminEntityController@bulkAction')->name('entity.bulk.action');
    Route::post('/entity/image/create', 'AdminEntityController@uploadImage')->name('entity.image.upload');
    Route::get('/entity/publish/{entity}', 'AdminEntityController@publish')->name('entity.publish');
    Route::get('/entity/unpublish/{entity}', 'AdminEntityController@unpublish')->name('entity.unpublish');
    
});