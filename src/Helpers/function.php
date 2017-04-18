<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 18/04/2017
 * Time: 15:44
 */

if (! function_exists('fitrans')) {
    function fitrans($key, $default = '', $params = [], $lang = null, $preferCache = true)
    {
        return \Famousinteractive\Translators\Helpers\Trans::get($key, $default, $params, $lang, $preferCache);
    }
}