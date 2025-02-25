<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Models\Album;
use Illuminate\Http\Request;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class AllHighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::orderBy('created_at', 'desc')->get();
        return view('highlight.all', compact('highlights'));
    }

    public function create()
    {
        return view('highlight.create');
    }

    public function store(Request $request)
    {
        // Log all incoming data for debugging
        Log::info('Request data:', $request->all());
        Log::info('Files in request:', $request->allFiles());

        // Validate request
        $request->validate([
            'banner' => 'required|image|mimes:png,jpeg,jpg,webp|max:2048',
            'topic' => 'required|string|max:255',
            'detail' => 'required|string',
            'albums.*.*' => 'image|mimes:png,jpeg,jpg,webp|max:2048',
        ]);

        // Initialize Google Cloud Storage
        try {
            $storage = new StorageClient([
                'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE', storage_path('app/google/service-account.json')),
            ]);
            $bucket = $storage->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));

            if (!$bucket->exists()) {
                throw new \Exception("Bucket does not exist or is inaccessible.");
            }
        } catch (\Exception $e) {
            Log::error('Google Cloud Storage initialization failed: ' . $e->getMessage());
            return back()->withErrors('Google Cloud Storage error: ' . $e->getMessage());
        }

        // Upload banner
        $bannerFile = $request->file('banner');
        $bannerName = date('Y-m-d') . '-' . time();
        Log::info("Uploading banner: $bannerName");
        $bucket->upload(fopen($bannerFile->getRealPath(), 'r'), ['name' => $bannerName]);

        // Create Highlight record
        $highlight = Highlight::create([
            'banner' => $bannerName,
            'selected' => 0,
            'topic' => $request->topic,
            'detail' => $request->detail,
        ]);
        Log::info("Highlight created with ID: {$highlight->id}");

        // Process albums from allFiles()
        $allFiles = $request->allFiles();
        if (isset($allFiles['albums']) && !empty($allFiles['albums'])) {
            $albumFiles = $allFiles['albums'];
            Log::info('Album files detected:', array_keys($albumFiles));

            foreach ($albumFiles as $groupIndex => $albumGroup) {
                Log::info("Processing album group: $groupIndex", ['file_count' => count($albumGroup)]);
                foreach ($albumGroup as $albumFile) { // Direct iteration over the file array
                    if ($albumFile instanceof \Illuminate\Http\UploadedFile) {
                        $albumName = date('Y-m-d') . '-' . microtime(true);
                        Log::info("Uploading album: $albumName");

                        try {
                            $bucket->upload(fopen($albumFile->getRealPath(), 'r'), [
                                'name' => $albumName,
                            ]);
                            Log::info("Album uploaded to GCS: $albumName");

                            $album = Album::create([
                                'url' => $albumName,
                                'highlight_id' => $highlight->id,
                            ]);
                            Log::info("Album saved to database with ID: {$album->id}");
                        } catch (\Exception $e) {
                            Log::error("Failed to process album '$albumName': " . $e->getMessage());
                        }
                    } else {
                        Log::warning("Invalid album file object in group $groupIndex", ['file' => (array)$albumFile]);
                    }
                }
            }
        } else {
            Log::warning("No albums detected in allFiles.");
        }

        return redirect()->route('all-highlight.index')
            ->with('success', 'Highlight created successfully.');
    }
    public function edit($id)
    {
        $highlight = Highlight::find($id);
        if (!$highlight) {
            return back()->withErrors("Highlight not found with ID: $id");
        }

        return view('highlight.edit', compact('highlight'));
    }
}
