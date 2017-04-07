<?php
/**
 * Created by PhpStorm.
 * User: jeremydillenbourg
 * Date: 05/04/2017
 * Time: 14:01
 */

namespace Famousinteractive\Translators\Controllers;


use App\Http\Controllers\Controller;
use Famousinteractive\Translators\Library\Api;
use Famousinteractive\Translators\Library\FileParser;
use Famousinteractive\Translators\Library\FileWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTranslation(Request $request) {

        $clientId   = $request->get('clientId');
        $apiKey     = $request->header('apiKey');

        $api = Api::getApi();

        if($api->checkCredential($clientId, $apiKey)) {
            $parser = new FileParser;

            return Response::json(['success' => true, 'data' => $parser->readFiles()->getJsonData()]);
        } else {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postTranslation(Request $request) {

        $clientId   = $request->get('clientId');
        $apiKey     = $request->header('apiKey');

        $api = Api::getApi();

        if($api->checkCredential($clientId, $apiKey)) {
            $writer = new FileWriter();

            $writer->updateFiles(
                $request->get('file'),
                $request->get('lang'),
                $request->get('key'),
                $request->get('value')
            );

            return Response::json(['success' => true]);
        } else {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

    }

}