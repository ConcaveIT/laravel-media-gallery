<?php
namespace Concaveit\Media\Routes;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function(){
	Route::get('/concave-gallery', 'Concaveit\Media\Controllers\MediaController@gallery')->name('concave.gallery');
	Route::get('/concave-gallery-refresh', 'Concaveit\Media\Controllers\MediaController@refreshGallery')->name('concave.gallery.refresh');
	Route::post('/concave-media/upload', 'Concaveit\Media\Controllers\MediaController@uploadfiles')->name('concave.media.upload');
	Route::post('/concave-media/delete/{id}', 'Concaveit\Media\Controllers\MediaController@delete_file')->name('concave.media.delete');
	Route::post('/concave-media/delete/multiple/{id}', 'Concaveit\Media\Controllers\MediaController@delete_multiple_files')->name('concave.media.delete.multiple');
	Route::post('/concave-media/update', 'Concaveit\Media\Controllers\MediaController@update_file')->name('concave.media.update');
});



