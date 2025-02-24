<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Highlight;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;

class HighlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $highlights = Highlight::orderBy('created_at', 'desc')->get();

        return view('highlight.index', compact('highlights'));
    }

    public function getImage($filename)
    {
        $storage = new StorageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE', storage_path('app/google/service-account.json')),
        ]);

        $bucket = $storage->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));
        $object = $bucket->object($filename);

        if (!$object->exists()) {
            abort(404, 'File not found');
        }

        $signedUrl = $object->signedUrl(
            now()->addDay(7),
            ['version' => 'v4']
        );

        return redirect($signedUrl);
    }
}
