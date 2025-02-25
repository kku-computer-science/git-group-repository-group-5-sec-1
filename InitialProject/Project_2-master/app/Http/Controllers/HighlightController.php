<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Highlight;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class HighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::orderBy('created_at', 'desc')->get();
        return view('highlight.index', compact('highlights'));
    }

    public function destroy($id)
    {
        try {
            // Find the highlight or throw an exception
            $highlight = Highlight::findOrFail($id);

            // Attempt to update the selected status
            $highlight->selected = 0;
            $highlight->save();

            return response()->json([
                'success' => true,
                'message' => 'Highlight deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found with ID: ' . $id
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while deleting highlight: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error occurred while deleting highlight: ' . $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $highlightsData = $request->all();
            Highlight::query()->update(['selected' => 0]);

            for ($i = 1; $i <= 3; $i++) {
                if (isset($highlightsData[$i]) && $highlightsData[$i]) {
                    $highlight = Highlight::find($highlightsData[$i]);
                    if ($highlight) {
                        $highlight->selected = $i;
                        $highlight->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Highlights saved successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving highlights: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            Highlight::query()->update(['selected' => 0]);
            $topHighlights = Highlight::orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($topHighlights as $index => $highlight) {
                $highlight->selected = $index + 1;
                $highlight->save();
            }

            $highlightsData = $topHighlights->map(function ($highlight) {
                return [
                    'id' => $highlight->id,
                    'image_url' => url('/highlight-image/' . $highlight->banner),
                    'topic' => $highlight->topic,
                    'destroy_route' => route('highlight.destroy', $highlight->id)
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Highlights reset successfully',
                'highlights' => $highlightsData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting highlights: ' . $e->getMessage()
            ], 500);
        }
    }
<<<<<<< HEAD

    public function showHighlights()
    {
        $highlights = Highlight::orderBy('created_at', 'desc')->get();
        return view('highlight', compact('highlights'));
    }
    
    public function show($id)
    {
        $highlight = Highlight::findOrFail($id);
        return view('highlight.show', compact('highlight'));
    }

    public function details($id)
    {
        $highlight = Highlight::findOrFail($id);
        return view('highlight.details', compact('highlight'));
    }
=======
>>>>>>> 83e4b7e31aaa16e4b7aeba65c18f021a0cf1851c
}
