<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Cq;
use App\Models\Mcq;
use App\Models\SchoolClass;
use App\Models\ShortQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;

class PaperBuilderController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $chapters = Chapter::when($request->subject_id, fn ($q) => $q->where('subject_id', $request->subject_id))
            ->orderBy('position')->get();

        return view('papers.builder', compact('classes', 'subjects', 'chapters'));
    }

    public function fetch(Request $request)
    {
        $request->validate([
            'chapter_ids' => 'required|array|min:1',
            'chapter_ids.*' => 'exists:chapters,id',
        ]);

        $chapterIds = $request->chapter_ids;

        return response()->json([
            'mcqs' => Mcq::with('chapter')
                ->whereIn('chapter_id', $chapterIds)
                ->orderBy('chapter_id')->orderBy('id')->get(),

            'shorts' => ShortQuestion::with('chapter')
                ->whereIn('chapter_id', $chapterIds)
                ->orderBy('chapter_id')->orderBy('id')->get(),

            'cqs' => Cq::with(['chapter', 'parts'])
                ->whereIn('chapter_id', $chapterIds)
                ->orderBy('chapter_id')->orderBy('id')->get(),
        ]);
    }
}
