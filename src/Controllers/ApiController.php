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
use Famousinteractive\Translators\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Storage;

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

    public function getFiles(Request $request) {

        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $files = File::all();

        return Response::json([
            'success'   => true,
            'data'      => [
                'files' => $files,
                'disks' => config('famousTranslator.disks')
            ],
        ]);
    }

    public function getFile(Request $request, $fileId) {
        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $file = File::find($fileId);

        return Response::json([
            'success'   => true,
            'data'      => $file,
            'file'      => Storage::disk($file->disk)->get($file->name),
        ]);
    }

    public function postFile(Request $request) {
        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $file = $request->transfile->store('fitrans', $request->get('disk'));

        File::create([
            'name'  => $file,
            'disk'  => $request->get('disk'),
            'url'   => $file
        ]);

        return Response::json([
            'success'   => 'maybe',
            'file' => $file
        ]);
    }

    public function deleteFile(Request $request, $fileId) {
        if(!$this->check($request)) {
            return Response::json(['success' => false, 'message' => 'invalid credential']);
        }

        $file = File::find($fileId);

        $name = $file->name;
        $disk = $file->disk;
        $file->delete();
        Storage::disk($disk)->delete('fitrans/'.$name);

    }
}