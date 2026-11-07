<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ocr\QuestionExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OcrController extends Controller
{
    public function create()
    {
        return view('admin.ocr.create', [
            'chapters' => \App\Models\Chapter::with('subject.schoolClass')->get(),
        ]);
    }

    public function extract(Request $request, QuestionExtractor $extractor)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'type' => 'required|in:mcq,short,cq',
        ]);

        $file = $request->file('image');
        $path = $file->store('question-sources', 'public');

        try {
            $data = $extractor->extract(
                Storage::disk('public')->path($path),
                $file->getMimeType(),
                $request->type
            );
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => asset('storage/' . $path),
            'data' => $data,
        ]);
    }
}
