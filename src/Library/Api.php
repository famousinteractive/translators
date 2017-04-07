<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:01
 */

namespace Famousinteractive\Translators\Library;


class Api
{
    protected static $_instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * @return Api
     */
    public static function getApi() {

        if(is_null(self::$_instance)) {
            return new self();
        }
        return self::$_instance;
    }

    /**
     * @param $clientId
     * @param $apiKey
     * @return bool
     */
    public function checkCredential($clientId, $apiKey) {

        require __DIR__ .'/../ApiCredential.php';

        if($credential['clientId'] == $clientId && \Hash::check($apiKey, $credential['key'])) {
            return true;
        }

        return false;
    }
}