<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:02
 */

namespace Famousinteractive\Translators\Library;


class FileParser
{

    protected $data = [];
    protected $langPath = null;

    /**
     * FileParser constructor.
     */
    public function __construct()
    {
        $this->langPath = resource_path('lang');
    }

    /**
     * @return $this
     */
    public function readFiles() {

        foreach($this->getFiles() as $k=>$lang) {

            if(is_dir($this->langPath.'/'.$lang) && $lang != '.' && $lang != '..') {
                foreach($this->getFiles($this->langPath.'/'.$lang) as $file) {
                    $filePath = $this->langPath.'/'.$lang.'/'.$file;

                    if(file_exists($filePath) && $file != '.' && $file != '..') {
                        $content = include $filePath;
                        $this->parser($lang, preg_replace("/\\.[^.]+$/", "", $file), $content);
                    }
                }
            }

        }
        return $this;
    }

    /**
     * @return array
     */
    public function getJsonData() {
        return $this->data;
    }

    /*
     *
     */
    protected function getFiles($path = null) {

        if(is_null($path)) {
            $path = $this->langPath;
        }
        return scandir($path);
    }

    /**
     * @param $lang
     * @param $file
     * @param string $content
     */
    protected function parser($lang, $file, $content = '') {

        foreach($content as $key=>$value) {
            $this->recursiveParser($lang, $file,$key, $value);
        }
    }

    /**
     * @param $lang
     * @param $file
     * @param $key
     * @param $value
     */
    protected function recursiveParser($lang, $file, $key, $value) {

        if(is_array($value)) {
            foreach($value as $k=>$v) {
                $this->recursiveParser($lang, $file,$key .'.'.$k, $v);
            }
        } else {
            $this->setData($lang, $file, $key, $value);
            return;
        }
    }

    /**
     * @param $lang
     * @param $fileName
     * @param $key
     * @param $value
     */
    protected function setData($lang, $fileName, $key, $value) {

        if(!isset($this->data[$lang])) {
            $this->data[$lang] = [];
        }
        if(!isset($this->data[$lang][$fileName])) {
            $this->data[$lang][$fileName] = [];
        }

        $this->data[$lang][$fileName][$key] = $value;
    }
}