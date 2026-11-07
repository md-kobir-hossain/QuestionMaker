<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMcqRequest;
use App\Models\Chapter;
use App\Models\Mcq;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class McqController extends Controller
{
    public function index(Request $request)
    {
        $query = Mcq::with('chapter.subject.schoolClass');

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }
        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $mcqs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.mcqs.index', [
            'mcqs' => $mcqs,
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.mcqs.create', [
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function store(StoreMcqRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        Mcq::create($data);
        return redirect()->route('admin.mcqs.index')->with('success', 'MCQ created.');
    }

    public function edit(Mcq $mcq)
    {
        return view('admin.mcqs.edit', [
            'mcq' => $mcq,
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function update(StoreMcqRequest $request, Mcq $mcq)
    {
        $mcq->update($request->validated());
        return redirect()->route('admin.mcqs.index')->with('success', 'MCQ updated.');
    }

    public function destroy(Mcq $mcq)
    {
        $mcq->delete();
        return redirect()->route('admin.mcqs.index')->with('success', 'MCQ deleted.');
    }
}
