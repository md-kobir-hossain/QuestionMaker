@extends('layouts.app')

@section('title', 'QuestionBank BD — প্রশ্নব্যাংক ও পরীক্ষার পেপার তৈরি করুন')

@push('head')
<style>
    /* Base */
    .qb-page { --qb-ease: cubic-bezier(.22,.8,.24,1); }
    .qb-page * { -webkit-tap-highlight-color: transparent; }

    /* Scroll reveal */
    .qb-reveal { opacity: 0; transform: translateY(32px) scale(.98); transition: opacity .8s var(--qb-ease), transform .8s var(--qb-ease); transition-delay: var(--d, 0s); }
    .qb-reveal.is-in { opacity: 1; transform: none; }

    /* Hero text entrance */
    @keyframes qbUp { from { opacity: 0; transform: translateY(28px); } to { opacity: 1; transform: none; } }
    .qb-in { opacity: 0; animation: qbUp .9s var(--qb-ease) forwards; animation-delay: var(--d, 0s); }

    /* Animated gradient text */
    @keyframes qbShift { 0% { background-position: 0% 50%; } 100% { background-position: 200% 50%; } }
    .qb-grad-text { background: linear-gradient(90deg,#fde047,#fb923c,#f472b6,#fde047); background-size: 200% auto; -webkit-background-clip: text; background-clip: text; color: transparent; animation: qbShift 6s linear infinite; }

    /* Aurora background */
    .qb-hero { background: #0b1020; isolation: isolate; }
    .qb-aurora { position: absolute; inset: 0; z-index: -1; overflow: hidden; }
    .qb-aurora i { position: absolute; border-radius: 9999px; filter: blur(80px); opacity: .55; animation: qbDrift 18s ease-in-out infinite alternate; }
    .qb-aurora i:nth-child(1) { width: 34rem; height: 34rem; background: #4f46e5; top: -10rem; left: -8rem; }
    .qb-aurora i:nth-child(2) { width: 30rem; height: 30rem; background: #9333ea; bottom: -12rem; right: -6rem; animation-delay: -6s; }
    .qb-aurora i:nth-child(3) { width: 22rem; height: 22rem; background: #0ea5e9; top: 30%; left: 45%; opacity: .3; animation-delay: -11s; }
    @keyframes qbDrift { from { transform: translate(0,0) scale(1); } to { transform: translate(60px,-40px) scale(1.18); } }
    .qb-grid { position: absolute; inset: 0; z-index: -1; background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size: 48px 48px; -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%); mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%); }

    /* 3D hero scene */
    .qb-scene { perspective: 1200px; width: 100%; height: 440px; position: relative; }
    .qb-mouse { position: absolute; inset: 0; transform-style: preserve-3d; transition: transform .25s ease-out; transform: rotateY(calc(var(--mx, 0) * 14deg)) rotateX(calc(var(--my, 0) * -14deg)); }
    .qb-stage { position: absolute; inset: 0; transform-style: preserve-3d; animation: qbIdle 9s ease-in-out infinite alternate; }
    @keyframes qbIdle { from { transform: rotateY(-16deg) rotateX(6deg); } to { transform: rotateY(14deg) rotateX(-4deg); } }

    .qb-paper { position: absolute; left: 50%; top: 50%; width: 270px; height: 350px; margin: -175px 0 0 -135px; border-radius: 18px; background: #fff; box-shadow: 0 30px 60px -15px rgba(0,0,0,.55); padding: 22px; transform-style: preserve-3d; }
    .qb-paper.p3 { transform: translateZ(-90px) translateX(46px) rotate(7deg); background: #e0e7ff; }
    .qb-paper.p2 { transform: translateZ(-40px) translateX(22px) rotate(3.5deg); background: #c7d2fe; }
    .qb-paper.p1 { transform: translateZ(20px); animation: qbBob 5s ease-in-out infinite; }
    @keyframes qbBob { 0%,100% { transform: translateZ(20px) translateY(0); } 50% { transform: translateZ(20px) translateY(-12px); } }
    .qb-line { height: 8px; border-radius: 6px; background: #e5e7eb; margin-bottom: 10px; position: relative; overflow: hidden; }
    .qb-line::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(99,102,241,.45), transparent); transform: translateX(-100%); animation: qbScan 2.6s ease-in-out infinite; animation-delay: var(--d, 0s); }
    @keyframes qbScan { to { transform: translateX(100%); } }

    .qb-chip { position: absolute; padding: 9px 16px; border-radius: 14px; font-weight: 700; font-size: 13px; color: #fff; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.35); box-shadow: 0 18px 30px -10px rgba(0,0,0,.5); animation: qbFloat 5s ease-in-out infinite; animation-delay: var(--d, 0s); }
    @keyframes qbFloat { 0%,100% { translate: 0 0; } 50% { translate: 0 -16px; } }
    .qb-chip.c1 { left: 2%; top: 14%; background: rgba(99,102,241,.75); transform: translateZ(150px); }
    .qb-chip.c2 { right: 0; top: 34%; background: rgba(236,72,153,.75); transform: translateZ(170px); }
    .qb-chip.c3 { left: 8%; bottom: 10%; background: rgba(16,185,129,.8); transform: translateZ(130px); }

    .qb-cube-wrap { position: absolute; right: 6%; bottom: 2%; width: 90px; height: 90px; perspective: 600px; transform: translateZ(100px); }
    .qb-cube { width: 100%; height: 100%; position: relative; transform-style: preserve-3d; animation: qbCube 14s linear infinite; }
    @keyframes qbCube { to { transform: rotateX(360deg) rotateY(360deg); } }
    .qb-cube span { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 20px; color: #fff; background: linear-gradient(135deg, rgba(251,191,36,.9), rgba(244,114,182,.9)); border: 1px solid rgba(255,255,255,.5); border-radius: 10px; }
    .qb-cube span:nth-child(1) { transform: translateZ(45px); }
    .qb-cube span:nth-child(2) { transform: rotateY(180deg) translateZ(45px); }
    .qb-cube span:nth-child(3) { transform: rotateY(90deg) translateZ(45px); }
    .qb-cube span:nth-child(4) { transform: rotateY(-90deg) translateZ(45px); }
    .qb-cube span:nth-child(5) { transform: rotateX(90deg) translateZ(45px); }
    .qb-cube span:nth-child(6) { transform: rotateX(-90deg) translateZ(45px); }

    .qb-ring { position: absolute; left: 50%; top: 50%; width: 380px; height: 380px; margin: -190px 0 0 -190px; border-radius: 9999px; border: 1px dashed rgba(255,255,255,.25); transform: translateZ(-160px); animation: qbSpin 30s linear infinite; }
    .qb-ring::after { content: ""; position: absolute; top: -6px; left: 50%; width: 12px; height: 12px; border-radius: 9999px; background: #fde047; box-shadow: 0 0 20px #fde047; }
    @keyframes qbSpin { to { transform: translateZ(-160px) rotate(360deg); } }

    /* Buttons */
    .qb-btn { position: relative; overflow: hidden; transition: transform .25s var(--qb-ease), box-shadow .25s; }
    .qb-btn:hover { transform: translateY(-3px); }
    .qb-btn.shine::before { content: ""; position: absolute; top: 0; left: -80%; width: 50%; height: 100%; background: linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent); transform: skewX(-20deg); animation: qbShine 3.2s ease-in-out infinite; }
    @keyframes qbShine { 0% { left: -80%; } 60%,100% { left: 140%; } }

    /* Tilt cards */
    .qb-tilt { transform-style: preserve-3d; transition: transform .2s ease-out, box-shadow .3s; will-change: transform; position: relative; }
    .qb-tilt .qb-glare { position: absolute; inset: 0; border-radius: inherit; pointer-events: none; opacity: 0; transition: opacity .3s; background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(255,255,255,.55), transparent 55%); }
    .qb-tilt:hover .qb-glare { opacity: 1; }
    .qb-tilt .qb-pop { transform: translateZ(40px); }
    .qb-card-border { background: linear-gradient(#fff,#fff) padding-box, linear-gradient(135deg, #c7d2fe, #f5d0fe, #bae6fd) border-box; border: 1px solid transparent; }

    /* Steps */
    .qb-step-line { background: linear-gradient(90deg, #a5b4fc 50%, transparent 50%); background-size: 14px 2px; animation: qbDash 1s linear infinite; }
    @keyframes qbDash { to { background-position: 14px 0; } }
    .qb-step-num { animation: qbPulse 3s ease-in-out infinite; }
    @keyframes qbPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(99,102,241,.35); } 50% { box-shadow: 0 0 0 16px rgba(99,102,241,0); } }

    /* Marquee */
    .qb-marquee { overflow: hidden; -webkit-mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); }
    .qb-track { display: flex; gap: 12px; width: max-content; animation: qbMarq 36s linear infinite; }
    .qb-track.rev { animation-direction: reverse; animation-duration: 44s; }
    .qb-marquee:hover .qb-track { animation-play-state: paused; }
    @keyframes qbMarq { to { transform: translateX(-50%); } }

    /* CTA */
    .qb-cta { background: linear-gradient(120deg, #4338ca, #7e22ce, #db2777, #4338ca); background-size: 300% 300%; animation: qbBg 10s ease infinite; }
    @keyframes qbBg { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

    .qb-mouse-hint { width: 24px; height: 38px; border: 2px solid rgba(255,255,255,.5); border-radius: 14px; position: relative; }
    .qb-mouse-hint::after { content: ""; position: absolute; top: 7px; left: 50%; width: 4px; height: 8px; margin-left: -2px; border-radius: 4px; background: #fff; animation: qbWheel 1.8s infinite; }
    @keyframes qbWheel { 0% { opacity: 1; transform: translateY(0); } 100% { opacity: 0; transform: translateY(14px); } }

    @media (prefers-reduced-motion: reduce) {
        .qb-page *, .qb-page *::before, .qb-page *::after { animation: none !important; transition: none !important; }
        .qb-reveal, .qb-in { opacity: 1 !important; transform: none !important; }
    }
    @media (max-width: 1023px) { .qb-scene { height: 360px; transform: scale(.85); } }
</style>
@endpush

@section('content')
<div class="qb-page -mt-8"> {{-- Negative margin to pull hero up if needed --}}

{{-- ============ HERO ============ --}}
<section id="qbHero" class="qb-hero relative overflow-hidden px-4 sm:px-6 lg:px-8 pt-20 pb-32">
    <div class="qb-aurora"><i></i><i></i><i></i></div>
    <div class="qb-grid"></div>

    <div class="relative max-w-6xl mx-auto grid lg:grid-cols-2 gap-10 items-center text-white">
        <div class="text-center lg:text-left">
            <span class="qb-in inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur rounded-full text-xs font-semibold mb-6 border border-white/20" style="--d:.05s">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                🇧🇩 বাংলাদেশের প্রথম বাংলা প্রশ্নব্যাংক প্ল্যাটফর্ম
            </span>

            <h1 class="qb-in text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.2] mb-6" style="--d:.15s">
                সেকেন্ডে তৈরি করুন<br class="hidden sm:block">
                <span class="qb-grad-text">মানসম্মত প্রশ্নপত্র</span>
            </h1>

            <p class="qb-in text-lg text-indigo-100/90 max-w-xl mx-auto lg:mx-0 mb-9 leading-relaxed" style="--d:.28s">
                ক্লাস ৯-১০ থেকে একাদশ-দ্বাদশ — বাংলা, ইংরেজি, গণিত, পদার্থবিজ্ঞান, রসায়নসহ সব বিষয়ের MCQ, সংক্ষিপ্ত ও সৃজনশীল প্রশ্ন থেকে নিজের পছন্দমতো পরীক্ষার পেপার বানান এবং PDF ডাউনলোড করুন।
            </p>

            <div class="qb-in flex flex-col sm:flex-row gap-3 justify-center lg:justify-start items-center" style="--d:.4s">
                @auth
                    <a href="{{ route('papers.builder') }}" class="qb-btn shine inline-flex items-center gap-2 px-8 py-3.5 bg-white text-indigo-700 font-semibold rounded-xl shadow-xl shadow-indigo-900/40">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        পেপার তৈরি করুন
                    </a>
                    <a href="{{ route('papers.index') }}" class="qb-btn inline-flex items-center gap-2 px-8 py-3.5 bg-white/10 backdrop-blur text-white font-semibold rounded-xl hover:bg-white/20 border border-white/30">
                        আমার পেপারসমূহ
                    </a>
                @else
                    <a href="{{ route('register') }}" class="qb-btn shine inline-flex items-center gap-2 px-8 py-3.5 bg-white text-indigo-700 font-semibold rounded-xl shadow-xl shadow-indigo-900/40">
                        বিনামূল্যে শুরু করুন
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="qb-btn inline-flex items-center gap-2 px-8 py-3.5 bg-white/10 backdrop-blur text-white font-semibold rounded-xl hover:bg-white/20 border border-white/30">
                        লগইন
                    </a>
                @endauth
            </div>

            <p class="qb-in mt-7 text-sm text-indigo-200/90 flex flex-wrap gap-x-5 gap-y-1 justify-center lg:justify-start" style="--d:.52s">
                <span>✅ সম্পূর্ণ বাংলায়</span><span>✅ KaTeX সাপোর্ট</span><span>✅ ইনস্ট্যান্ট PDF</span>
            </p>
        </div>

        <div class="qb-in" style="--d:.3s">
            <div class="qb-scene" id="qbScene">
                <div class="qb-mouse" id="qbMouse">
                    <div class="qb-stage">
                        <div class="qb-ring"></div>
                        <div class="qb-paper p3"></div>
                        <div class="qb-paper p2"></div>
                        <div class="qb-paper p1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-[11px] font-bold text-indigo-700">QuestionBank BD</div>
                                <div class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold">PDF</div>
                            </div>
                            <div class="text-center mb-4">
                                <div class="text-sm font-bold text-gray-800">বার্ষিক পরীক্ষা — গণিত</div>
                                <div class="text-[10px] text-gray-500">পূর্ণমান: ১০০ · সময়: ৩ ঘণ্টা</div>
                            </div>
                            <div class="qb-line" style="--d:0s; width:92%"></div>
                            <div class="qb-line" style="--d:.3s; width:78%"></div>
                            <div class="flex gap-2 my-3">
                                <span class="text-[10px] px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">ক) ২</span>
                                <span class="text-[10px] px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">খ) ৪</span>
                                <span class="text-[10px] px-2 py-1 rounded bg-indigo-600 text-white font-semibold">গ) ৮</span>
                                <span class="text-[10px] px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">ঘ) ১৬</span>
                            </div>
                            <div class="qb-line" style="--d:.6s; width:88%"></div>
                            <div class="qb-line" style="--d:.9s; width:70%"></div>
                            <div class="qb-line" style="--d:1.2s; width:95%"></div>
                            <div class="mt-3 text-[11px] font-mono text-purple-700 text-center bg-purple-50 rounded-lg py-2">x = (−b ± √(b²−4ac)) / 2a</div>
                        </div>

                        <div class="qb-chip c1" style="--d:0s">MCQ</div>
                        <div class="qb-chip c2" style="--d:1.2s">সৃজনশীল</div>
                        <div class="qb-chip c3" style="--d:2.2s">⬇ PDF</div>

                        <div class="qb-cube-wrap">
                            <div class="qb-cube"><span>ক</span><span>খ</span><span>গ</span><span>ঘ</span><span>MCQ</span><span>CQ</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 hidden md:block"><div class="qb-mouse-hint"></div></div>

    <svg class="absolute bottom-0 left-0 w-full text-gray-50" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" style="height:50px"><path d="M0,32 C240,70 480,0 720,24 C960,48 1200,64 1440,20 L1440,60 L0,60 Z"/></svg>
</section>

{{-- ============ STATS ============ --}}
@php
    $statsData = [
        ['num' => 100, 'suffix' => '+', 'label' => 'বিষয় ও অধ্যায়'],
        ['num' => 5000, 'suffix' => '+', 'label' => 'প্রশ্নের ভাণ্ডার'],
        ['num' => 1, 'suffix' => ' মিনিটে', 'label' => 'পেপার তৈরি'],
        ['num' => 100, 'suffix' => '%', 'label' => 'বিনামূল্যে'],
    ];
@endphp
<section class="max-w-6xl mx-auto -mt-10 relative z-10 px-4">
    <div class="qb-reveal bg-white/90 backdrop-blur rounded-2xl shadow-2xl shadow-indigo-900/10 border border-gray-100 grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
        @foreach($statsData as $stat)
            <div class="p-6 text-center">
                <div class="text-2xl sm:text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    <span data-count="{{ $stat['num'] }}">0</span><span>{{ $stat['suffix'] }}</span>
                </div>
                <div class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ FEATURES ============ --}}
@php
$features = [
    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'বিষয়ভিত্তিক প্রশ্নব্যাংক', 'desc' => 'বাংলা, ইংরেজি, গণিত, পদার্থবিজ্ঞান, রসায়নসহ সব বিষয়ের অধ্যায়ভিত্তিক প্রশ্নের সম্পূর্ণ সংগ্রহ।', 'grad' => 'from-indigo-500 to-indigo-700'],
    ['icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', 'title' => 'ড্র্যাগ অ্যান্ড ড্রপ বিল্ডার', 'desc' => 'MCQ, সংক্ষিপ্ত ও সৃজনশীল — তিন ধরনের প্রশ্ন একসাথে সাজিয়ে পূর্ণাঙ্গ প্রশ্নপত্র তৈরি করুন।', 'grad' => 'from-emerald-500 to-teal-600'],
    ['icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'title' => 'সৃজনশীল CQ সাপোর্ট', 'desc' => 'উদ্দীপক + ক, খ, গ, ঘ (বা Math-এ ক, খ, গ) — সাবজেক্টের প্যাটার্ন অনুযায়ী অটোমেটিক পার্ট তৈরি।', 'grad' => 'from-rose-500 to-pink-600'],
    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'ইনস্ট্যান্ট PDF', 'desc' => 'সুন্দর ফরম্যাটে প্রশ্নপত্র ডাউনলোড করুন — বাংলা যুক্তাক্ষর ও গণিতের সূত্র নিখুঁতভাবে রেন্ডার হয়।', 'grad' => 'from-sky-500 to-blue-600'],
    ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'ছবি থেকে প্রশ্ন (OCR)', 'desc' => 'প্রশ্নের ছবি আপলোড করুন — AI স্বয়ংক্রিয়ভাবে টেক্সট বের করে ফর্ম পূরণ করে দিবে, শুধু রিভিউ করে সেভ করুন।', 'grad' => 'from-amber-500 to-orange-600'],
    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'পেপার হিস্টোরি', 'desc' => 'তৈরি করা সব পেপার সেভ থাকে — যেকোনো সময় পুনরায় ডাউনলোড, সম্পাদনা বা ডুপ্লিকেট করুন।', 'grad' => 'from-violet-500 to-purple-700'],
];
@endphp
<section class="max-w-6xl mx-auto px-4 py-24">
    <div class="qb-reveal text-center mb-14">
        <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase">ফিচারসমূহ</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2 mb-3">কেন QuestionBank BD?</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">শিক্ষক ও শিক্ষার্থী উভয়ের জন্য দ্রুত, সহজ ও নির্ভরযোগ্য সমাধান</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" style="perspective:1200px">
        @foreach($features as $index => $feature)
            <div class="qb-reveal" style="--d: {{ ($index % 3) * 0.1 }}s">
                <div class="qb-tilt qb-card-border bg-white rounded-2xl p-7 shadow-sm hover:shadow-2xl hover:shadow-indigo-200/60 h-full" data-tilt>
                    <div class="qb-pop w-14 h-14 rounded-2xl bg-gradient-to-br {{ $feature['grad'] }} text-white flex items-center justify-center mb-5 shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="qb-pop font-bold text-lg text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                    <div class="qb-glare"></div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ HOW IT WORKS ============ --}}
@php
    $steps = [
        ['num' => '১', 'title' => 'অধ্যায় নির্বাচন', 'desc' => 'ক্লাস ও বিষয় বেছে নিন, তারপর যে অধ্যায়গুলো থেকে প্রশ্ন নিতে চান সেগুলো টিক দিন।'],
        ['num' => '২', 'title' => 'প্রশ্ন বাছাই', 'desc' => 'MCQ, সংক্ষিপ্ত ও সৃজনশীল ট্যাব থেকে পছন্দের প্রশ্ন সিলেক্ট করুন। মোট নম্বর স্বয়ংক্রিয়ভাবে হিসাব হবে।'],
        ['num' => '৩', 'title' => 'PDF ডাউনলোড', 'desc' => 'স্কুলের নাম, পরীক্ষার নাম ও সময় দিন — এক ক্লিকে সুন্দর ফরম্যাটে PDF নামিয়ে নিন।'],
    ];
@endphp
<section class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-indigo-50/60 to-purple-50/50 py-24 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto relative">
        <div class="qb-reveal text-center mb-16">
            <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase">কীভাবে কাজ করে</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2 mb-3">মাত্র ৩ ধাপে প্রশ্নপত্র তৈরি</h2>
            <p class="text-gray-600">কোনো জটিলতা নেই, কোনো লম্বা ফর্ম নেই</p>
        </div>

        <div class="grid md:grid-cols-3 gap-10 relative">
            <div class="hidden md:block absolute top-10 left-[18%] right-[18%] h-0.5 qb-step-line"></div>

            @foreach($steps as $index => $step)
                <div class="qb-reveal relative text-center" style="--d: {{ $index * 0.15 }}s">
                    <div class="qb-step-num relative z-10 w-20 h-20 mx-auto rounded-3xl bg-white border-4 border-indigo-100 shadow-xl flex items-center justify-center mb-6 rotate-3 hover:rotate-0 transition">
                        <span class="text-3xl font-extrabold bg-gradient-to-br from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ SUBJECTS (marquee) ============ --}}
@php
    $row1 = ['বাংলা', 'ইংরেজি', 'গণিত', 'পদার্থবিজ্ঞান', 'রসায়ন', 'জীববিজ্ঞান'];
    $row2 = ['উচ্চতর গণিত', 'বাংলাদেশ ও বিশ্বপরিচয়', 'ইসলাম শিক্ষা', 'তথ্য ও যোগাযোগ প্রযুক্তি', 'হিসাববিজ্ঞান', 'অর্থনীতি'];
    $marquees = [
        ['row' => $row1, 'cls' => ''],
        ['row' => $row2, 'cls' => 'rev'],
    ];
@endphp
<section class="py-24">
    <div class="qb-reveal text-center mb-12 px-4">
        <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase">বিষয়সমূহ</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-2 mb-3">সব বিষয় এক প্ল্যাটফর্মে</h2>
        <p class="text-gray-600">বিজ্ঞান, মানবিক ও ব্যবসায় শিক্ষা — সব শাখার জন্য</p>
    </div>

    <div class="space-y-4">
        @foreach($marquees as $marquee)
            <div class="qb-marquee">
                <div class="qb-track {{ $marquee['cls'] }}">
                    @for($k = 0; $k < 4; $k++)
                        @foreach($marquee['row'] as $sub)
                            <span class="px-6 py-3 bg-white rounded-full border border-gray-200 text-gray-700 text-sm font-semibold shadow-sm hover:border-indigo-400 hover:text-indigo-600 hover:shadow-md transition whitespace-nowrap">{{ $sub }}</span>
                        @endforeach
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ CTA ============ --}}
<section class="max-w-6xl mx-auto px-4 pb-24">
    <div class="qb-reveal qb-cta relative overflow-hidden rounded-3xl px-8 py-20 text-center text-white shadow-2xl shadow-purple-900/30" style="perspective:1000px">
        <div class="absolute -top-16 -right-16 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-pink-400/20 rounded-full blur-3xl"></div>
        <div class="absolute top-8 left-10 w-10 h-10 border-2 border-white/30 rounded-lg" style="animation: qbFloat 6s ease-in-out infinite"></div>
        <div class="absolute bottom-10 right-14 w-8 h-8 border-2 border-white/30 rounded-full" style="animation: qbFloat 5s ease-in-out infinite -2s"></div>

        <div class="relative">
            <h2 class="text-3xl sm:text-5xl font-extrabold mb-4">আজই শুরু করুন</h2>
            <p class="text-indigo-100 text-lg mb-9 max-w-2xl mx-auto">
                রেজিস্ট্রেশন সম্পূর্ণ বিনামূল্যে। কোনো ক্রেডিট কার্ড লাগবে না। মিনিটের মধ্যে প্রথম প্রশ্নপত্র তৈরি করুন।
            </p>
            @auth
                <a href="{{ route('papers.builder') }}" class="qb-btn shine inline-flex items-center gap-2 px-9 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl">
                    পেপার তৈরি করুন →
                </a>
            @else
                <a href="{{ route('register') }}" class="qb-btn shine inline-flex items-center gap-2 px-9 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl">
                    বিনামূল্যে অ্যাকাউন্ট খুলুন →
                </a>
            @endauth
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var bn = '০১২৩৪৫৬৭৮৯';
    function toBn(n) { return String(n).replace(/\d/g, function (d) { return bn[d]; }); }

    /* 1. Scroll reveal */
    var reveals = document.querySelectorAll('.qb-reveal');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* 2. Count-up stats */
    var counters = document.querySelectorAll('[data-count]');
    function runCount(el) {
        var target = parseInt(el.getAttribute('data-count'), 10);
        if (reduce) { el.textContent = toBn(target.toLocaleString('en-US')); return; }
        var dur = 1600, start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = toBn(Math.floor(target * eased).toLocaleString('en-US'));
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = toBn(target.toLocaleString('en-US'));
        }
        requestAnimationFrame(step);
    }
    if ('IntersectionObserver' in window) {
        var cio = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { runCount(e.target); cio.unobserve(e.target); }
            });
        }, { threshold: 0.6 });
        counters.forEach(function (c) { cio.observe(c); });
    } else { counters.forEach(runCount); }

    if (reduce) return;

    /* 3. Hero 3D mouse parallax */
    var hero = document.getElementById('qbHero');
    var mouse = document.getElementById('qbMouse');
    if (hero && mouse) {
        hero.addEventListener('mousemove', function (e) {
            var r = hero.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width - 0.5;
            var y = (e.clientY - r.top) / r.height - 0.5;
            mouse.style.setProperty('--mx', x.toFixed(3));
            mouse.style.setProperty('--my', y.toFixed(3));
        });
        hero.addEventListener('mouseleave', function () {
            mouse.style.setProperty('--mx', 0);
            mouse.style.setProperty('--my', 0);
        });
    }

    /* 4. 3D tilt + glare on feature cards (hover devices only) */
    if (window.matchMedia('(hover: hover)').matches) {
        document.querySelectorAll('[data-tilt]').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                var r = card.getBoundingClientRect();
                var px = (e.clientX - r.left) / r.width;
                var py = (e.clientY - r.top) / r.height;
                var rx = (0.5 - py) * 14;
                var ry = (px - 0.5) * 14;
                card.style.transform = 'perspective(900px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg) translateY(-6px)';
                card.style.setProperty('--gx', (px * 100) + '%');
                card.style.setProperty('--gy', (py * 100) + '%');
            });
            card.addEventListener('mouseleave', function () {
                card.style.transform = '';
            });
        });
    }
})();
</script>
@endpush
