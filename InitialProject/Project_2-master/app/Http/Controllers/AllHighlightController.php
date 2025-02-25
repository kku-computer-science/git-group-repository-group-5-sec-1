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

        $bucket->upload(fopen($bannerFile->getRealPath(), 'r'), ['name' => $bannerName]);

        // Create Highlight record
        $highlight = Highlight::create([
            'banner' => $bannerName,
            'selected' => 0,
            'topic' => $request->topic,
            'detail' => $request->detail,
        ]);

        // Process albums from allFiles()
        $allFiles = $request->allFiles();
        if (isset($allFiles['albums']) && !empty($allFiles['albums'])) {
            $albumFiles = $allFiles['albums'];

            foreach ($albumFiles as $groupIndex => $albumGroup) {
                foreach ($albumGroup as $albumFile) {
                    if ($albumFile instanceof \Illuminate\Http\UploadedFile) {
                        $albumName = date('Y-m-d') . '-' . microtime(true);

                        try {
                            $bucket->upload(fopen($albumFile->getRealPath(), 'r'), [
                                'name' => $albumName,
                            ]);

                            $album = Album::create([
                                'url' => $albumName,
                                'highlight_id' => $highlight->id,
                            ]);
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

    public function update(Request $request, $id)
    {
        // Log all incoming data for debugging
        Log::info('Update request data:', $request->all());
        Log::info('Update files in request:', $request->allFiles());

        // Validate request
        $request->validate([
            'banner' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048', // Optional for update
            'topic' => 'required|string|max:255',
            'detail' => 'required|string',
            'albums.*.*' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
        ]);

        // Find the highlight
        $highlight = Highlight::find($id);
        if (!$highlight) {
            return back()->withErrors("Highlight not found with ID: $id");
        }

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

        // Update banner if provided
        if ($request->hasFile('banner')) {
            // Delete old banner from GCS (optional, depending on your needs)
            $oldBanner = $highlight->banner;
            $bucket->object($oldBanner)->delete();

            $bannerFile = $request->file('banner');
            $bannerName = date('Y-m-d') . '-' . time();
            Log::info("Uploading new banner: $bannerName");
            $bucket->upload(fopen($bannerFile->getRealPath(), 'r'), ['name' => $bannerName]);
            $highlight->banner = $bannerName;
        }

        // Update highlight details
        $highlight->topic = $request->topic;
        $highlight->detail = $request->detail;
        $highlight->save();
        Log::info("Highlight updated with ID: {$highlight->id}");

        // Process albums
        $allFiles = $request->allFiles();
        if (isset($allFiles['albums']) && !empty($allFiles['albums'])) {
            $albumFiles = $allFiles['albums'];

            foreach ($albumFiles as $groupIndex => $albumGroup) {
                foreach ($albumGroup as $albumFile) {
                    if ($albumFile instanceof \Illuminate\Http\UploadedFile) {
                        $existingAlbum = $highlight->albums->get($groupIndex);
                        if ($existingAlbum) {
                            $bucket->object($existingAlbum->url)->delete();
                            $existingAlbum->delete();;
                        }

                        $albumName = date('Y-m-d') . '-' . microtime(true);

                        try {
                            $bucket->upload(fopen($albumFile->getRealPath(), 'r'), [
                                'name' => $albumName,
                            ]);

                            $album = Album::create([
                                'url' => $albumName,
                                'highlight_id' => $highlight->id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Failed to process album '$albumName': " . $e->getMessage());
                        }
                    }
                }
            }
        }

        return redirect()->route('all-highlight.index')
            ->with('success', 'Highlight updated successfully.');
    }

    public function destroy($id)
    {
        // Log the deletion attempt
        Log::info("Attempting to delete highlight with ID: $id");

        // Find the highlight
        $highlight = Highlight::find($id);
        if (!$highlight) {
            Log::warning("Highlight not found with ID: $id");
            return redirect()->route('all-highlight.index')->withErrors("Highlight not found with ID: $id");
        }

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

            // Delete banner from GCS
            $bucket->object($highlight->banner)->delete();
            Log::info("Deleted banner from GCS: {$highlight->banner}");

            // Delete all associated albums from GCS and database
            foreach ($highlight->albums as $album) {
                $bucket->object($album->url)->delete();
                Log::info("Deleted album from GCS: {$album->url}");
                $album->delete(); // Delete from database
            }

            // Delete the highlight record
            $highlight->delete();
            Log::info("Highlight deleted from database with ID: $id");

        } catch (\Exception $e) {
            Log::error("Failed to delete highlight ID: $id - " . $e->getMessage());
            return redirect()->route('all-highlight.index')->withErrors("Failed to delete highlight: " . $e->getMessage());
        }

        return redirect()->route('all-highlight.index')
            ->with('success', 'Highlight deleted successfully.');
    }
}
