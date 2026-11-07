<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>{{ $paper->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Noto Sans Bengali', sans-serif; font-size: 13px; color: #111; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; margin: 0; }
        .header .meta { display: flex; justify-content: space-between; margin-top: 8px; font-size: 12px; }
        h2 { font-size: 15px; margin: 18px 0 8px; }
        ol { padding-left: 22px; }
        ol li { margin-bottom: 8px; }
        .options { margin-top: 3px; font-size: 12px; color: #333; }
        .parts { margin-top: 3px; padding-left: 18px; }
        .parts div { margin-bottom: 3px; }
        .marks { color: #555; }
        .footer { position: fixed; bottom: 10px; right: 15px; font-size: 10px; color: #777; }
        .answer-key { page-break-before: always; }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ $paper->school_name }}</h1>
    <div>{{ $paper->exam_name }}</div>
    <div class="meta">
        <span>সময়: {{ $paper->exam_time }}</span>
        <span>পূর্ণমান: {{ $paper->full_marks }}</span>
    </div>
</div>

@if($paper->mcqs->count())
    <h2>ক-বিভাগ: বহুনির্বাচনি প্রশ্ন</h2>
    <ol>
        @foreach($paper->mcqs as $m)
            <li>
                <div>{!! nl2br(e($m->question)) !!}</div>
                <div class="options">
                    ক) {{ $m->option_a }} &nbsp; খ) {{ $m->option_b }} &nbsp;
                    গ) {{ $m->option_c }} &nbsp; ঘ) {{ $m->option_d }}
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if($paper->shortQuestions->count())
    <h2>খ-বিভাগ: সংক্ষিপ্ত প্রশ্ন</h2>
    <ol>
        @foreach($paper->shortQuestions as $s)
            <li>{{ $s->question }} <span class="marks">[{{ $s->marks }}]</span></li>
        @endforeach
    </ol>
@endif

@if($paper->cqs->count())
    <h2>গ-বিভাগ: সৃজনশীল প্রশ্ন</h2>
    <ol>
        @foreach($paper->cqs as $cq)
            <li>
                <div><b>উদ্দীপক:</b> {!! nl2br(e($cq->stem)) !!}</div>
                <div class="parts">
                    @foreach($cq->parts as $p)
                        <div><b>{{ $p->label }}.</b> {{ $p->question }} <span class="marks">[{{ $p->marks }}]</span></div>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if(!empty($includeAnswerKey) && $paper->mcqs->count())
    <div class="answer-key">
        <h2>উত্তরপত্র (MCQ)</h2>
        <ol>
            @foreach($paper->mcqs as $m)
                <li>{{ strtoupper($m->correct_answer ?? '—') }}</li>
            @endforeach
        </ol>
    </div>
@endif

<div class="footer">QuestionBank BD</div>

<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
        onload="renderMathInElement(document.body, {delimiters:[{left:'$$',right:'$$',display:true},{left:'$',right:'$',display:false}]});"></script>
</body>
</html>
