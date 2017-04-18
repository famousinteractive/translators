<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:02
 */

namespace Famousinteractive\Translators\Library;

class FileWriter
{
    protected $langPath = null;

    public function __construct()
    {
        $this->langPath = resource_path('lang');
    }

    /**
     * @param $file
     * @param $lang
     * @param $key
     * @param $value
     * @return $this
     */
    public function updateFiles($file, $lang, $key, $value) {

        $path = $this->langPath . '/'.$lang . '/' . $file . '.php';

        $content = include $path;

        array_set($content, $key, $value);

        if(!empty($content) && !is_null($content)) {

            $fp = fopen($path, 'w');
            fwrite($fp, '<?php return ' . var_export($content, true) . ';');
            fclose($fp);
        }

        return $this;
    }
}