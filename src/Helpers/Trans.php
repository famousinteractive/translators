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
    public static function get($key, $default = '', $params = [], $container = 'default', $lang=null, $preferCache=true) {

        $instance = new self();

        if(is_null($lang)) {
            $lang = $instance->getCurrentLang();
        }

        if($preferCache) {

            $paramsString = md5(json_encode($params));

            $value = Cache::remember('cache-fitrans-' . $key . '-' . $container .'-' . $lang.'-params'.$paramsString, 3600, function () use ($instance, $key, $default, $params, $lang) {
                return $instance->getTranslation($key, $default, $params, $lang, $container);
            });
        } else {
            $value = $instance->getTranslation($key, $default, $params, $lang, $container);
        }

        return $value;
    }

    protected function getTranslation($key, $default, $params, $lang, $container) {

        $content = Content::where('key', $key)->where('container', $container)->first();
        $translation = ContentTranslation::where('content_id', $content->id)->where('lang', $lang)->first();

        if(empty($content)) {
            $content = Content::create(['key' => $key, 'container' => $container]);
        }

        if(empty($translation)) {
            $translation = ContentTranslation::create([
                'content_id'    => $content->id,
                'lang'          => $lang,
                'value'         => $default
            ]);
        }

        //Generate missing translation for each language

        foreach(config('famousTranslator.lang') as $language) {
            $translationCount = ContentTranslation::where('content_id', $content->id)->where('lang', $language)->count();
            if($translationCount == 0) {
                ContentTranslation::create([
                    'content_id'    => $content->id,
                    'lang'          => $language,
                    'value'         => $default
                ]);
            }
        }

        return $this->replaceParameters($translation->value, $params);
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

function fitrans($key, $default = '', $params = [], $container = 'default', $lang=null, $preferCache=true) {
    echo \Famousinteractive\Translators\Helpers\Trans::get($key, $default, $params, $container, $lang,$preferCache);
}