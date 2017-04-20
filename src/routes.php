<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:06
 */

Route::get('famousTranslators', 'Famousinteractive\Translators\Controllers\ApiController@getTranslation');
Route::post('famousTranslators', 'Famousinteractive\Translators\Controllers\ApiController@postTranslation');

Route::get('famousTranslatorsDatabase', 'Famousinteractive\Translators\Controllers\ApiController@getContentDatabase');
Route::post('famousTranslatorsDatabase', 'Famousinteractive\Translators\Controllers\ApiController@postContentDatabase');

Route::get('famousTranslatorsFiles', 'Famousinteractive\Translators\Controllers\ApiController@getFiles');
Route::post('famousTranslatorsFiles', 'Famousinteractive\Translators\Controllers\ApiController@postFile');
Route::get('famousTranslatorsFiles/{fileId}', 'Famousinteractive\Translators\Controllers\ApiController@getFile');
Route::delete('famousTranslatorsFiles/{fileId}', 'Famousinteractive\Translators\Controllers\ApiController@deleteFile');