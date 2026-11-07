<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index()
    {
        $chapters = Chapter::with('subject.schoolClass')->orderBy('subject_id')->orderBy('position')->paginate(20);
        return view('admin.chapters.index', compact('chapters'));
    }

    public function create()
    {
        return view('admin.chapters.create', ['subjects' => Subject::with('schoolClass')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
        ]);
        Chapter::create($data);
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created.');
    }

    public function edit(Chapter $chapter)
    {
        return view('admin.chapters.edit', [
            'chapter' => $chapter,
            'subjects' => Subject::with('schoolClass')->get(),
        ]);
    }

    public function update(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
        ]);
        $chapter->update($data);
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated.');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter deleted.');
    }
}
