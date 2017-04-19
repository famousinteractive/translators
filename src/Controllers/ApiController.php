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

    public function check(Request $request) {
        $clientId   = $request->get('clientId');
        $apiKey     = $request->header('apiKey');

        $api = Api::getApi();

        if(is_null($apiKey) || !$api->checkCredential($clientId, $apiKey)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTranslation(Request $request) {

        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $parser = new FileParser;
        return Response::json(['success' => true, 'data' => $parser->readFiles()->getJsonData()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postTranslation(Request $request) {

        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

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

        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $translations = Content::with('translations')->get()->toArray();
        return Response::json(['success' => true, 'data' => $translations]);
    }

    public function postContentDatabase(Request $request) {

        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $key = $request->get('key');
        $lang = $request->get('lang');
        $value = $request->get('value');

        $content = Content::where('key', $key)->first();

        if(empty($contentId)) {
            return Response::json(['success' => false, 'message' => 'The key' . $key . 'doesn\'t exists']);
        }

        //Save content
        $html = $request->get('html', $content->html);
        $description = $request->get('description', $content->description);

        $content->html = $html;
        $content->description = $description;
        $content->save();

        //Save the translation
        $translation = ContentTranslation::where('content_id', $content->id)
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