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

        $key = explode('.', $key);

        $path = $this->langPath . '/'.$lang . '/' . $file . '.php';

        $content = include $path;

        $content = $this->recursiveArrayUpdate($key, $content, 0, $value);

        $fp = fopen($path , 'w');
        fwrite($fp, '<?php return ' . var_export( $content, true) . ';');
        fclose($fp);

        return $this;
    }

    /**
     * @param $key
     * @param $content
     * @param $keyIncrement
     * @param $value
     * @return mixed
     */
    protected function recursiveArrayUpdate($key, $content, $keyIncrement, $value) {

        foreach($content as $k=>$v) {

            if($k == $key[$keyIncrement]) {
                if(is_array($v)) {
                    $this->recursiveArrayUpdate($key, $content, ++$keyIncrement, $value);
                } else {
                    $content[$k] = $value;
                    return $content;
                }
            }
        }
    }





}