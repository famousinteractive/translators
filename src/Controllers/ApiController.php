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
use Famousinteractive\Translators\Models\Content;
use Famousinteractive\Translators\Models\ContentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    public function __construct(Request $request)
    {
        $clientId   = $request->get('clientId');
        $apiKey     = $request->header('apiKey');

        $api = Api::getApi();

        if(!$api->checkCredential($clientId, $apiKey)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
            die;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTranslation(Request $request) {

        $parser = new FileParser;
        return Response::json(['success' => true, 'data' => $parser->readFiles()->getJsonData()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postTranslation(Request $request) {

        $writer = new FileWriter();

        $writer->updateFiles(
            $request->get('file'),
            $request->get('lang'),
            $request->get('key'),
            $request->get('value')
        );

        return Response::json(['success' => true]);
    }

    public function getContentDatabase(Request $request) {

        $translations = Content::with('translations')->get()->toArray();
        return Response::json(['success' => true, 'data' => $translations]);
    }

    public function postContentDatabase(Request $request) {

        $key = $request->get('key');
        $lang = $request->get('lang');
        $value = $request->get('value');


        $contentId = Content::where('key', $key)->first()->id;

        if(empty($contentId)) {
            return Response::json(['success' => false, 'message' => 'The key' . $key . 'doesn\'t exists']);
        }

        $translation = ContentTranslation::where('content_id', $contentId)
                          ->where('lang', $lang)
                          ->first();

        if(empty($translation)) {
            ContentTranslation::create([
                'content_id' => $contentId,
                'lang'       => $lang,
                'value'      => $value
            ]);
        } else {
            $translation->value = $value;
            $translation->save();
        }

        return Response::json(['success' => true]);
    }

}