<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaperRequest;
use App\Models\Paper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;

class PaperController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $papers = auth()->user()->papers()->latest()->paginate(15);
        return view('papers.index', compact('papers'));
    }

    public function store(StorePaperRequest $request)
    {
        $data = $request->validated();

        $paper = DB::transaction(function () use ($data, $request) {
            $paper = Paper::create([
                'user_id' => $request->user()->id,
                'title' => $data['title'],
                'school_name' => $data['school_name'],
                'exam_name' => $data['exam_name'],
                'exam_time' => $data['exam_time'],
                'full_marks' => $data['full_marks'],
            ]);

            if (! empty($data['mcq_ids'])) {
                $attach = [];
                foreach ($data['mcq_ids'] as $i => $id) {
                    $attach[$id] = ['position' => $i];
                }
                $paper->mcqs()->sync($attach);
            }

            if (! empty($data['short_ids'])) {
                $attach = [];
                foreach ($data['short_ids'] as $i => $id) {
                    $attach[$id] = ['position' => $i];
                }
                $paper->shortQuestions()->sync($attach);
            }

            if (! empty($data['cq_ids'])) {
                $attach = [];
                foreach ($data['cq_ids'] as $i => $id) {
                    $attach[$id] = ['position' => $i];
                }
                $paper->cqs()->sync($attach);
            }

            return $paper;
        });

        return redirect()->route('papers.show', $paper)->with('success', 'Paper saved.');
    }

    public function show(Paper $paper)
    {
        $this->authorize('view', $paper);
        $paper->load(['mcqs.chapter', 'shortQuestions.chapter', 'cqs.parts', 'cqs.chapter']);
        return view('papers.show', compact('paper'));
    }

    public function download(Paper $paper)
    {
        $this->authorize('view', $paper);
        $paper->load(['mcqs.chapter', 'shortQuestions.chapter', 'cqs.parts', 'cqs.chapter']);

        $pdf = Pdf::view('pdf.paper', [
            'paper' => $paper,
            'includeAnswerKey' => request()->boolean('answer_key', false),
        ])
            ->format('A4')
            ->name("paper-{$paper->id}.pdf");

        return $pdf->download();
    }

    public function destroy(Paper $paper)
    {
        $this->authorize('delete', $paper);
        $paper->delete();
        return redirect()->route('papers.index')->with('success', 'Paper deleted.');
    }
}
