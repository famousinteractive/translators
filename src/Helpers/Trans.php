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
    public static function get($key, $default = '', $params = [], $lang=null, $preferCache=true) {

        $instance = new self();

        if($preferCache) {

            $paramsString = md5(json_encode($params));

            $value = Cache::remember('cache-fitrans-' . $key . '-' . $lang.'-params'.$paramsString, 3600, function () use ($instance, $key, $default, $params, $lang) {
                return $instance->getTranslation($key, $default, $params, $lang);
            });
        } else {
            $value = $instance->getTranslation($key, $default, $params, $lang);
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