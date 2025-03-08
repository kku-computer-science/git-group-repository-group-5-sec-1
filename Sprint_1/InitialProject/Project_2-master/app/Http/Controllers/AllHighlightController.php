<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Models\Album;
use App\Models\Tag;
use App\Models\ResearchGroup;
use Illuminate\Http\Request;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class AllHighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::with('tags')->orderBy('created_at', 'desc')->get();
        return view('highlight.all', compact('highlights'));
    }

    public function create()
    {
        $researchGroups = ResearchGroup::pluck('group_name_th')->all(); // Fetch group_name_th for dropdown
        return view('highlight.create', compact('researchGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:png,jpeg,jpg,webp|max:2048',
            'topic' => 'required|string|max:255',
            'detail' => 'required|string',
            'albums.*.*' => 'image|mimes:png,jpeg,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:255', // Validate tag names
        ]);

        try {
            $storage = new StorageClient([
                'projectId' => 'handy-amplifier-451806-b1',
                'keyFilePath' => storage_path('app/google/service-account.json'),
            ]);
            $bucket = $storage->bucket('highlight-image');

            if (!$bucket->exists()) {
                throw new \Exception("Bucket does not exist or is inaccessible.");
            }

            // Upload banner
            $bannerFile = $request->file('banner');
            $bannerName = date('Y-m-d') . '-' . time() . '-' . $bannerFile->getClientOriginalName();
            $bucket->upload(fopen($bannerFile->getRealPath(), 'r'), ['name' => $bannerName]);

            // Create highlight
            $highlight = Highlight::create([
                'banner' => $bannerName,
                'selected' => 0,
                'topic' => $request->topic,
                'detail' => $request->detail,
            ]);

            // Handle tags from request
            if ($request->tags) {
                $tagIds = [];
                foreach (array_filter($request->tags) as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $tagIds[] = $tag->id;
                }
                $highlight->tags()->sync($tagIds);
            }

            // Process albums (unchanged)
            $allFiles = $request->allFiles();
            if (isset($allFiles['albums']) && !empty($allFiles['albums'])) {
                $albumFiles = $allFiles['albums'];
                foreach ($albumFiles as $groupIndex => $albumGroup) {
                    foreach ($albumGroup as $albumFile) {
                        if ($albumFile instanceof \Illuminate\Http\UploadedFile) {
                            $albumName = date('Y-m-d') . '-' . microtime(true) . '-' . $albumFile->getClientOriginalName();
                            $bucket->upload(fopen($albumFile->getRealPath(), 'r'), ['name' => $albumName]);
                            Album::create([
                                'url' => $albumName,
                                'highlight_id' => $highlight->id,
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('all-highlight.index')
                ->with('success', 'Highlight created successfully.');
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return back()->withErrors('Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $highlight = Highlight::with('tags')->findOrFail($id);
        $researchGroups = ResearchGroup::pluck('group_name_th')->all();
        return view('highlight.edit', compact('highlight', 'researchGroups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banner' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
            'topic' => 'required|string|max:255',
            'detail' => 'required|string',
            'albums.*.*' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:255',
        ]);

        try {
            $highlight = Highlight::findOrFail($id);
            $storage = new StorageClient([
                'projectId' => 'handy-amplifier-451806-b1',
                'keyFilePath' => storage_path('app/google/service-account.json'),
            ]);
            $bucket = $storage->bucket('highlight-image');

            if ($request->hasFile('banner')) {
                $bucket->object($highlight->banner)->delete();
                $bannerFile = $request->file('banner');
                $bannerName = date('Y-m-d') . '-' . time(). '-' . $bannerFile->getClientOriginalName();
                $bucket->upload(fopen($bannerFile->getRealPath(), 'r'), ['name' => $bannerName]);
                $highlight->banner = $bannerName;
            }

            $highlight->update([
                'topic' => $request->topic,
                'detail' => $request->detail,
            ]);

            // Handle tags
            if ($request->tags) {
                $tagIds = [];
                foreach (array_filter($request->tags) as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $tagIds[] = $tag->id;
                }
                $highlight->tags()->sync($tagIds);
            } else {
                $highlight->tags()->detach();
            }

            // Albums (unchanged)
            $allFiles = $request->allFiles();
            if (isset($allFiles['albums']) && !empty($allFiles['albums'])) {
                $albumFiles = $allFiles['albums'];
                foreach ($albumFiles as $groupIndex => $albumGroup) {
                    foreach ($albumGroup as $albumFile) {
                        if ($albumFile instanceof \Illuminate\Http\UploadedFile) {
                            $albumName = date('Y-m-d') . '-' . microtime(true) . '-' . $albumFile->getClientOriginalName();
                            $bucket->upload(fopen($albumFile->getRealPath(), 'r'), ['name' => $albumName]);
                            Album::create([
                                'url' => $albumName,
                                'highlight_id' => $highlight->id,
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('all-highlight.index')
                ->with('success', 'Highlight updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            return back()->withErrors('Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $highlight = Highlight::findOrFail($id);
            $storage = new StorageClient([
                'projectId' => 'handy-amplifier-451806-b1',
                'keyFilePath' => storage_path('app/google/service-account.json'),
            ]);
            $bucket = $storage->bucket('highlight-image');

            $bucket->object($highlight->banner)->delete();
            foreach ($highlight->albums as $album) {
                $bucket->object($album->url)->delete();
                $album->delete();
            }

            $highlight->tags()->detach();
            $highlight->delete();

            return response()->json(['success' => 'Highlight deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
