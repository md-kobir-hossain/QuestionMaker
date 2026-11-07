@extends('layouts.app')
@section('title', $paper->title)

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">{{ $paper->title }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('papers.download', $paper) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Download PDF</a>
        <a href="{{ route('papers.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Back</a>
    </div>
</div>
<div class="bg-white rounded-xl shadow p-6 space-y-6">
    <div class="text-center border-b pb-3">
        <h2 class="text-xl font-bold">{{ $paper->school_name }}</h2>
        <p class="text-sm">{{ $paper->exam_name }}</p>
        <p class="text-sm">সময়: {{ $paper->exam_time }} · পূর্ণমান: {{ $paper->full_marks }}</p>
    </div>
    @if($paper->mcqs->count())
        <section>
            <h3 class="font-bold text-lg mb-2">ক-বিভাগ: বহুনির্বাচনি প্রশ্ন</h3>
            <ol class="space-y-2 text-sm list-decimal list-inside">
                @foreach($paper->mcqs as $m)
                    <li>
                        <div>{!! nl2br(e($m->question)) !!}</div>
                        <div class="ml-4 text-xs text-gray-700">ক) {{ $m->option_a }} · খ) {{ $m->option_b }} · গ) {{ $m->option_c }} · ঘ) {{ $m->option_d }}</div>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif
    @if($paper->shortQuestions->count())
        <section>
            <h3 class="font-bold text-lg mb-2">খ-বিভাগ: সংক্ষিপ্ত প্রশ্ন</h3>
            <ol class="space-y-2 text-sm list-decimal list-inside">
                @foreach($paper->shortQuestions as $s)
                    <li>{!! nl2br(e($s->question)) !!} <span class="text-xs text-gray-500">[{{ $s->marks }}]</span></li>
                @endforeach
            </ol>
        </section>
    @endif
    @if($paper->cqs->count())
        <section>
            <h3 class="font-bold text-lg mb-2">গ-বিভাগ: সৃজনশীল প্রশ্ন</h3>
            <ol class="space-y-4 text-sm list-decimal list-inside">
                @foreach($paper->cqs as $cq)
                    <li>
                        <div class="font-medium">উদ্দীপক: {!! nl2br(e($cq->stem)) !!}</div>
                        <ul class="ml-4 mt-1 space-y-1">
                            @foreach($cq->parts as $p)
                                <li><b>{{ $p->label }}.</b> {{ $p->question }} <span class="text-gray-500">[{{ $p->marks }}]</span></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif
</div>
@endsection
