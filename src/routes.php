<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:06
 */

Route::get('famousTranslators', 'Famous\Translators\Controllers\ApiController@getTranslation');
Route::post('famousTranslators', 'Famous\Translators\Controllers\ApiController@postTranslation');