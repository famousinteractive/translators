<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 18/04/2017
 * Time: 14:10
 */

namespace Famousinteractive\Translators\Helpers;


use Famousinteractive\Translators\Models\Content;
use Famousinteractive\Translators\Models\ContentTranslation;
use Illuminate\Support\Facades\Cache;

class Trans
{
    private static $_instance = null;

    public static function get($key, $default = '', $params = [], $lang=null, $preferCache=true) {

        self::$_instance = new self();

        if($preferCache) {

            $value = Cache::remember('cache-fitrans-' . $key . '-' . $lang, 3600, function () use ($key, $default, $params, $lang) {
                return self::$_instance->getTranslation($key, $default, $params, $lang);
            });
        } else {
            $value = self::$_instance->getTranslation($key, $default, $params, $lang);
        }

        return $value;
    }

    protected function getTranslation($key, $default, $params, $lang) {
        if(is_null($lang)) {
            $lang = $this->getCurrentLang();
        }

        $content = Content::where('key', $key)->whereHas('translations', function($q) use($lang) {
            $q->where('lang', $lang);
        })->first();

        if(empty($content)) {
            $content = Content::create(['key' => $key]);
            ContentTranslation::create([
                'content_id'    => $content->id,
                'lang'          => $lang,
                'value'         => $default
            ]);
        }

        return $this->replaceParameters($content->translations->first()->value, $params);
    }

    protected function getCurrentLang() {
        return \Config::get('app.locale');
    }

    protected function replaceParameters($value, $params = []) {

        foreach($params as $key=>$v) {
            $value = str_replace(':'.$key, $v, $value);
        }

        return $value;
    }

}

function fitrans($key, $default = '', $params = [], $lang=null, $preferCache=true) {
    echo \Famousinteractive\Translators\Helpers\Trans::get($key, $default, $params, $lang,$preferCache);
}