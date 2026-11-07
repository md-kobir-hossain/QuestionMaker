<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCqRequest;
use App\Models\Chapter;
use App\Models\Cq;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CqController extends Controller
{
    public function index(Request $request)
    {
        $query = Cq::with(['chapter.subject.schoolClass', 'parts']);

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }
        if ($request->filled('search')) {
            $query->where('stem', 'like', '%' . $request->search . '%');
        }

        return view('admin.cqs.index', [
            'cqs' => $query->latest()->paginate(15)->withQueryString(),
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.cqs.create', [
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function store(StoreCqRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $cq = Cq::create([
                'chapter_id' => $data['chapter_id'],
                'stem' => $data['stem'],
                'created_by' => $request->user()->id,
            ]);

            foreach (array_values($data['parts']) as $i => $p) {
                $cq->parts()->create([
                    'label' => $p['label'],
                    'question' => $p['question'],
                    'marks' => $p['marks'],
                    'position' => $i,
                ]);
            }
        });

        return redirect()->route('admin.cqs.index')->with('success', 'CQ created.');
    }

    public function edit(Cq $cq)
    {
        $cq->load('parts');
        return view('admin.cqs.edit', [
            'cq' => $cq,
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'chapters' => Chapter::with('subject')->get(),
        ]);
    }

    public function update(StoreCqRequest $request, Cq $cq)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $cq) {
            $cq->update([
                'chapter_id' => $data['chapter_id'],
                'stem' => $data['stem'],
            ]);
            $cq->parts()->delete();

            foreach (array_values($data['parts']) as $i => $p) {
                $cq->parts()->create([
                    'label' => $p['label'],
                    'question' => $p['question'],
                    'marks' => $p['marks'],
                    'position' => $i,
                ]);
            }
        });

        return redirect()->route('admin.cqs.index')->with('success', 'CQ updated.');
    }

    public function destroy(Cq $cq)
    {
        $cq->delete();
        return redirect()->route('admin.cqs.index')->with('success', 'CQ deleted.');
    }
}
