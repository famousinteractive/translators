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

class Trans
{
    private static $_instance = null;

    public static function get($key, $default = '', $params = [], $lang=null) {

        self::$_instance = new self();

        if(is_null($lang)) {
            $lang = self::$_instance->getCurrentLang();
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

        $value = self::$_instance->replaceParameters($content->translations->value, $params);

        return $value;
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

function fitrans($key, $default = '', $params = [], $lang=null) {
    echo \Famousinteractive\Translators\Helpers\Trans::get($key, $default, $params, $lang);
}