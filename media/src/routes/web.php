<?php
namespace Concaveit\Media\Routes;
use Illuminate\Support\Facades\Route;

Route::get('/concave-gallery', 'Concaveit\Media\Controllers\MediaController@gallery')->name('concave.gallery');
Route::get('/concave-gallery-refresh', 'Concaveit\Media\Controllers\MediaController@gallery')->name('concave.gallery.refresh');
Route::post('/concave-media/upload', 'Concaveit\Media\Controllers\MediaController@uploadfiles')->name('concave.media.upload');

