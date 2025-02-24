<?php

namespace App\Http\Controllers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;

class GoogleCloudController extends Controller
{
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
