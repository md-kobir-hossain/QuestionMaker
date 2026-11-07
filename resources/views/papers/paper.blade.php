<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>{{ $paper->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <style>
        /* Page Setup */
        @page {
            margin: 30px 40px;
            /* পেজের চারপাশে প্রিমিয়াম বর্ডার */
            border: 2px solid #1e293b;
        }
        body {
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
            font-size: 14px;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header .logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
            color: #0f172a;
        }
        .header .exam-name {
            font-size: 16px;
            font-weight: 600;
            color: #4338ca; /* Premium Indigo Color */
            margin-top: 4px;
        }
        .header .meta {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 14px;
            font-weight: 500;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Section Headings */
        h2 {
            font-size: 16px;
            margin: 22px 0 12px;
            font-weight: 700;
            color: #0f172a;
            background: #eef2ff;
            padding: 6px 12px;
            border-left: 4px solid #4f46e5;
            border-radius: 2px;
        }

        /* Lists */
        ol {
            padding-left: 24px;
            margin: 0;
        }
        ol li {
            margin-bottom: 12px;
            padding-left: 4px;
        }
        ol li::marker {
            font-weight: bold;
            color: #4f46e5;
        }

        /* Question Content */
        .question-text {
            font-weight: 500;
            text-align: justify;
        }
        .options {
            margin-top: 6px;
            font-size: 13px;
            color: #334155;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .options span {
            min-width: 45%;
        }

        /* CQ (Srijonshil) Parts */
        .parts {
            margin-top: 8px;
            padding-left: 10px;
            border-left: 1px dashed #cbd5e1;
        }
        .parts div {
            margin-bottom: 6px;
        }
        .parts b {
            color: #4338ca;
            font-size: 15px;
        }

        /* Marks */
        .marks {
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.05; /* হালকা ওয়াটারমার্ক */
            z-index: -1000;
            text-align: center;
            width: 100%;
            pointer-events: none;
        }
        .watermark h1 {
            font-size: 50px;
            font-weight: 900;
            color: #4f46e5;
            margin: 0;
            letter-spacing: 4px;
        }
        .watermark p {
            font-size: 20px;
            color: #4f46e5;
            margin-top: 5px;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 10px;
            right: 15px;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Answer Key */
        .answer-key {
            page-break-before: always;
        }
    </style>
</head>
<body>

{{-- ===== WATERMARK START ===== --}}
<div class="watermark">
    <h1>QuestionBank BD</h1>
    <p>questionbank.bd</p>
</div>
{{-- ===== WATERMARK END ===== --}}

{{-- ===== HEADER START ===== --}}
<div class="header">
    {{-- লোগো যোগ করতে চাইলে নিচের লাইনটি আনকমেন্ট করুন এবং public/images/logo.png তে আপনার লোগো রাখুন --}}
    {{-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo"> --}}

    <h1>{{ $paper->school_name }}</h1>
    <div class="exam-name">{{ $paper->exam_name }}</div>

    <div class="meta">
        <span><strong>বিষয়:</strong> {{ $paper->title ?? 'সাধারণ' }}</span>
        <span><strong>সময়:</strong> {{ $paper->exam_time }}</span>
        <span><strong>পূর্ণমান:</strong> {{ $paper->full_marks }}</span>
    </div>
</div>
{{-- ===== HEADER END ===== --}}

@if($paper->mcqs->count())
    <h2>ক-বিভাগ: বহুনির্বাচনি প্রশ্ন (MCQ)</h2>
    <ol>
        @foreach($paper->mcqs as $m)
            <li>
                <div class="question-text">{!! nl2br(e($m->question)) !!}</div>
                <div class="options">
                    <span><b>ক)</b> {{ $m->option_a }}</span>
                    <span><b>খ)</b> {{ $m->option_b }}</span>
                    <span><b>গ)</b> {{ $m->option_c }}</span>
                    <span><b>ঘ)</b> {{ $m->option_d }}</span>
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if($paper->shortQuestions->count())
    <h2>খ-বিভাগ: সংক্ষিপ্ত প্রশ্ন</h2>
    <ol>
        @foreach($paper->shortQuestions as $s)
            <li>
                <div class="question-text">
                    {{ $s->question }} <span class="marks">[{{ $s->marks }}]</span>
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if($paper->cqs->count())
    <h2>গ-বিভাগ: সৃজনশীল প্রশ্ন (CQ)</h2>
    <ol>
        @foreach($paper->cqs as $cq)
            <li>
                <div class="question-text" style="margin-bottom: 8px;">
                    <b>উদ্দীপক:</b> {!! nl2br(e($cq->stem)) !!}
                </div>
                <div class="parts">
                    @foreach($cq->parts as $p)
                        <div>
                            <b>{{ $p->label }}.</b> {{ $p->question }} <span class="marks">[{{ $p->marks }}]</span>
                        </div>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if(!empty($includeAnswerKey) && $paper->mcqs->count())
    <div class="answer-key">
        <h2>উত্তরপত্র (MCQ)</h2>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; font-size: 16px; font-weight: bold;">
            @foreach($paper->mcqs as $index => $m)
                <div style="border-bottom: 1px solid #e2e8f0; padding: 5px 0;">
                    {{ $index + 1 }}. {{ strtoupper($m->correct_answer ?? '—') }}
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="footer">Generated by QuestionBank BD</div>

<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
        onload="renderMathInElement(document.body, {delimiters:[{left:'$$',right:'$$',display:true},{left:'$',right:'$',display:false}]});"></script>
</body>
</html>
