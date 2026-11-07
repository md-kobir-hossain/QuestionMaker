<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShortQuestionRequest;
use App\Models\Chapter;
use App\Models\SchoolClass;
use App\Models\ShortQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;

class ShortQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = ShortQuestion::with('chapter.subject.schoolClass');

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }
        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        return view('admin.shorts.index', [
            'shorts' => $query->latest()->paginate(15)->withQueryString(),
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.shorts.create', [
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function store(StoreShortQuestionRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        ShortQuestion::create($data);
        return redirect()->route('admin.shorts.index')->with('success', 'Short question created.');
    }

    public function edit(ShortQuestion $short)
    {
        return view('admin.shorts.edit', [
            'short' => $short,
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function update(StoreShortQuestionRequest $request, ShortQuestion $short)
    {
        $short->update($request->validated());
        return redirect()->route('admin.shorts.index')->with('success', 'Short question updated.');
    }

    public function destroy(ShortQuestion $short)
    {
        $short->delete();
        return redirect()->route('admin.shorts.index')->with('success', 'Short question deleted.');
    }
}
