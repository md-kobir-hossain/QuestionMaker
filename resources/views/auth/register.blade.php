<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>রেজিস্ট্রেশন — QuestionBank BD</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=hind-siliguri:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Hind Siliguri', ui-sans-serif, system-ui, sans-serif; }
        .rg-ease { --e: cubic-bezier(.22,.8,.24,1); }

        /* entrance */
        @keyframes rgUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        .rg-in { opacity: 0; animation: rgUp .8s var(--e, ease) forwards; animation-delay: var(--d, 0s); }

        /* left aurora panel */
        .rg-left { background: #0b1020; isolation: isolate; }
        .rg-aurora { position: absolute; inset: 0; z-index: -1; overflow: hidden; }
        .rg-aurora i { position: absolute; border-radius: 9999px; filter: blur(80px); opacity: .55; animation: rgDrift 16s ease-in-out infinite alternate; }
        .rg-aurora i:nth-child(1) { width: 30rem; height: 30rem; background: #4f46e5; top: -8rem; left: -8rem; }
        .rg-aurora i:nth-child(2) { width: 26rem; height: 26rem; background: #9333ea; bottom: -10rem; right: -6rem; animation-delay: -6s; }
        .rg-aurora i:nth-child(3) { width: 18rem; height: 18rem; background: #0ea5e9; top: 40%; left: 40%; opacity: .3; animation-delay: -10s; }
        @keyframes rgDrift { from { transform: translate(0,0) scale(1); } to { transform: translate(50px,-40px) scale(1.18); } }
        .rg-grid { position: absolute; inset: 0; z-index: -1; background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size: 44px 44px; -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 78%); mask-image: radial-gradient(ellipse at center, #000 30%, transparent 78%); }

        /* 3D scene */
        .rg-scene { perspective: 1100px; height: 300px; position: relative; }
        .rg-mouse { position: absolute; inset: 0; transform-style: preserve-3d; transition: transform .25s ease-out; transform: rotateY(calc(var(--mx, 0) * 16deg)) rotateX(calc(var(--my, 0) * -16deg)); }
        .rg-stage { position: absolute; inset: 0; transform-style: preserve-3d; animation: rgIdle 8s ease-in-out infinite alternate; }
        @keyframes rgIdle { from { transform: rotateY(-14deg) rotateX(5deg); } to { transform: rotateY(14deg) rotateX(-3deg); } }
        .rg-card { position: absolute; left: 50%; top: 50%; width: 220px; height: 260px; margin: -130px 0 0 -110px; border-radius: 18px; transform-style: preserve-3d; box-shadow: 0 30px 60px -15px rgba(0,0,0,.6); }
        .rg-card.a { background: #c7d2fe; transform: translateZ(-80px) translateX(38px) rotate(7deg); }
        .rg-card.b { background: #e0e7ff; transform: translateZ(-35px) translateX(18px) rotate(3.5deg); }
        .rg-card.c { background: #fff; padding: 18px; transform: translateZ(20px); animation: rgBob 5s ease-in-out infinite; }
        @keyframes rgBob { 0%,100% { transform: translateZ(20px) translateY(0); } 50% { transform: translateZ(20px) translateY(-10px); } }
        .rg-bar { height: 7px; border-radius: 6px; background: #e5e7eb; margin-bottom: 9px; position: relative; overflow: hidden; }
        .rg-bar::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(99,102,241,.5), transparent); transform: translateX(-100%); animation: rgScan 2.4s ease-in-out infinite; animation-delay: var(--d, 0s); }
        @keyframes rgScan { to { transform: translateX(100%); } }
        .rg-chip { position: absolute; padding: 7px 14px; border-radius: 12px; font-weight: 700; font-size: 12px; color: #fff; border: 1px solid rgba(255,255,255,.35); backdrop-filter: blur(8px); box-shadow: 0 14px 24px -8px rgba(0,0,0,.5); animation: rgFloat 5s ease-in-out infinite; animation-delay: var(--d, 0s); }
        @keyframes rgFloat { 0%,100% { translate: 0 0; } 50% { translate: 0 -14px; } }
        .rg-chip.x { left: 4%; top: 12%; background: rgba(99,102,241,.8); transform: translateZ(140px); }
        .rg-chip.y { right: 2%; top: 38%; background: rgba(236,72,153,.8); transform: translateZ(160px); }
        .rg-chip.z { left: 10%; bottom: 8%; background: rgba(16,185,129,.85); transform: translateZ(120px); }
        .rg-cube-wrap { position: absolute; right: 8%; bottom: 0; width: 70px; height: 70px; perspective: 500px; transform: translateZ(90px); }
        .rg-cube { width: 100%; height: 100%; position: relative; transform-style: preserve-3d; animation: rgCube 14s linear infinite; }
        @keyframes rgCube { to { transform: rotateX(360deg) rotateY(360deg); } }
        .rg-cube span { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 17px; color: #fff; background: linear-gradient(135deg, rgba(251,191,36,.92), rgba(244,114,182,.92)); border: 1px solid rgba(255,255,255,.5); border-radius: 8px; }
        .rg-cube span:nth-child(1) { transform: translateZ(35px); }
        .rg-cube span:nth-child(2) { transform: rotateY(180deg) translateZ(35px); }
        .rg-cube span:nth-child(3) { transform: rotateY(90deg) translateZ(35px); }
        .rg-cube span:nth-child(4) { transform: rotateY(-90deg) translateZ(35px); }
        .rg-cube span:nth-child(5) { transform: rotateX(90deg) translateZ(35px); }
        .rg-cube span:nth-child(6) { transform: rotateX(-90deg) translateZ(35px); }

        .rg-grad-text { background: linear-gradient(90deg,#fde047,#fb923c,#f472b6,#fde047); background-size: 200% auto; -webkit-background-clip: text; background-clip: text; color: transparent; animation: rgShift 6s linear infinite; }
        @keyframes rgShift { to { background-position: 200% 50%; } }

        /* form */
        .rg-field { position: relative; }
        .rg-field .ico { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; transition: color .2s; pointer-events: none; }
        .rg-field input { width: 100%; padding: 13px 44px 13px 44px; border-radius: 12px; border: 1.5px solid #e5e7eb; background: #f9fafb; font-size: 15px; transition: border-color .2s, box-shadow .2s, background .2s; }
        .rg-field input:focus { outline: none; background: #fff; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,.15); }
        .rg-field:focus-within .ico { color: #6366f1; }
        .rg-field.err input { border-color: #f43f5e; background: #fff1f2; }
        .rg-eye { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); padding: 8px; color: #9ca3af; border-radius: 8px; }
        .rg-eye:hover { color: #4f46e5; background: #eef2ff; }

        .rg-meter { display: flex; gap: 6px; margin-top: 8px; }
        .rg-meter i { flex: 1; height: 5px; border-radius: 4px; background: #e5e7eb; transition: background .3s; }

        .rg-btn { position: relative; overflow: hidden; transition: transform .25s, box-shadow .25s; }
        .rg-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -10px rgba(79,70,229,.6); }
        .rg-btn::before { content: ""; position: absolute; top: 0; left: -80%; width: 50%; height: 100%; background: linear-gradient(120deg, transparent, rgba(255,255,255,.45), transparent); transform: skewX(-20deg); animation: rgShine 3.2s ease-in-out infinite; }
        @keyframes rgShine { 0% { left: -80%; } 60%,100% { left: 140%; } }
        .rg-spin { animation: rgSpin .8s linear infinite; }
        @keyframes rgSpin { to { transform: rotate(360deg); } }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .rg-in { opacity: 1 !important; }
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
<div class="min-h-screen grid lg:grid-cols-2">

    {{-- ============ LEFT: brand panel ============ --}}
    <aside id="rgLeft" class="rg-left relative hidden lg:flex flex-col justify-between overflow-hidden p-12 text-white">
        <div class="rg-aurora"><i></i><i></i><i></i></div>
        <div class="rg-grid"></div>

        <a href="{{ url('/') }}" class="rg-in flex items-center gap-3 w-fit" style="--d:.05s">
            <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-900/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <span class="text-xl font-bold tracking-tight">QuestionBank <span class="text-indigo-300">BD</span></span>
        </a>

        <div>
            <div class="rg-scene rg-in" style="--d:.2s">
                <div class="rg-mouse" id="rgMouse">
                    <div class="rg-stage">
                        <div class="rg-card a"></div>
                        <div class="rg-card b"></div>
                        <div class="rg-card c">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-bold text-indigo-700">QuestionBank BD</span>
                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold">PDF</span>
                            </div>
                            <div class="text-center text-xs font-bold text-gray-800 mb-3">বার্ষিক পরীক্ষা — পদার্থবিজ্ঞান</div>
                            <div class="rg-bar" style="width:92%"></div>
                            <div class="rg-bar" style="width:74%; --d:.3s"></div>
                            <div class="flex gap-1.5 my-2.5">
                                <span class="text-[9px] px-1.5 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">ক</span>
                                <span class="text-[9px] px-1.5 py-1 rounded bg-indigo-600 text-white font-semibold">খ</span>
                                <span class="text-[9px] px-1.5 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">গ</span>
                                <span class="text-[9px] px-1.5 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold">ঘ</span>
                            </div>
                            <div class="rg-bar" style="width:86%; --d:.6s"></div>
                            <div class="rg-bar" style="width:68%; --d:.9s"></div>
                            <div class="mt-2 text-[10px] font-mono text-purple-700 text-center bg-purple-50 rounded-lg py-1.5">F = ma</div>
                        </div>
                        <div class="rg-chip x" style="--d:0s">MCQ</div>
                        <div class="rg-chip y" style="--d:1.2s">সৃজনশীল</div>
                        <div class="rg-chip z" style="--d:2.2s">⬇ PDF</div>
                        <div class="rg-cube-wrap"><div class="rg-cube"><span>ক</span><span>খ</span><span>গ</span><span>ঘ</span><span>MCQ</span><span>CQ</span></div></div>
                    </div>
                </div>
            </div>

            <h2 class="rg-in text-3xl xl:text-4xl font-extrabold leading-[1.3] mt-6 mb-4" style="--d:.35s">
                সেকেন্ডে তৈরি করুন<br><span class="rg-grad-text">মানসম্মত প্রশ্নপত্র</span>
            </h2>
            <ul class="rg-in space-y-2.5 text-indigo-100/90 text-[15px]" style="--d:.5s">
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-400/20 text-emerald-300 flex items-center justify-center text-xs">✓</span> ৫,০০০+ প্রশ্নের ভাণ্ডার</li>
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-400/20 text-emerald-300 flex items-center justify-center text-xs">✓</span> MCQ, সংক্ষিপ্ত ও সৃজনশীল প্রশ্ন</li>
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-400/20 text-emerald-300 flex items-center justify-center text-xs">✓</span> এক ক্লিকে সুন্দর PDF ডাউনলোড</li>
            </ul>
        </div>

        <p class="rg-in text-xs text-indigo-200/60" style="--d:.6s">© {{ date('Y') }} QuestionBank BD · সম্পূর্ণ বিনামূল্যে</p>
    </aside>

    {{-- ============ RIGHT: form ============ --}}
    <main class="relative flex items-center justify-center px-5 py-10 sm:px-10">
        <div class="absolute inset-0 overflow-hidden pointer-events-none lg:hidden">
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-indigo-200/50 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-purple-200/50 rounded-full blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-md">
            <a href="{{ url('/') }}" class="lg:hidden rg-in flex items-center justify-center gap-2.5 mb-8" style="--d:.05s">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span class="text-lg font-bold">QuestionBank <span class="text-indigo-600">BD</span></span>
            </a>

            <div class="rg-in mb-7" style="--d:.1s">
                <h1 class="text-3xl font-extrabold text-gray-900">নতুন অ্যাকাউন্ট খুলুন 👋</h1>
                <p class="text-gray-500 mt-1.5">কয়েক সেকেন্ডেই শুরু করুন — কোনো ক্রেডিট কার্ড লাগবে না।</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="rgForm" class="rg-in bg-white rounded-2xl border border-gray-100 shadow-xl shadow-indigo-900/5 p-6 sm:p-8 space-y-5" style="--d:.2s">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">পুরো নাম</label>
                    <div class="rg-field @error('name') err @enderror">
                        <svg class="ico w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="আপনার নাম" required autofocus autocomplete="name">
                    </div>
                    @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">ইমেইল</label>
                    <div class="rg-field @error('email') err @enderror">
                        <svg class="ico w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username">
                    </div>
                    @error('email') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড</label>
                    <div class="rg-field @error('password') err @enderror">
                        <svg class="ico w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="password" type="password" name="password" placeholder="কমপক্ষে ৮ অক্ষর" required autocomplete="new-password">
                        <button type="button" class="rg-eye" data-eye="password" aria-label="পাসওয়ার্ড দেখুন">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div class="rg-meter" id="rgMeter"><i></i><i></i><i></i><i></i></div>
                    <p class="mt-1.5 text-xs text-gray-500" id="rgMeterText">একটি শক্তিশালী পাসওয়ার্ড দিন</p>
                    @error('password') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <div class="rg-field @error('password_confirmation') err @enderror">
                        <svg class="ico w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="আবার লিখুন" required autocomplete="new-password">
                        <button type="button" class="rg-eye" data-eye="password_confirmation" aria-label="পাসওয়ার্ড দেখুন">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="mt-1.5 text-xs hidden" id="rgMatch"></p>
                    @error('password_confirmation') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" id="rgSubmit" class="rg-btn w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30">
                    <span id="rgBtnText">অ্যাকাউন্ট তৈরি করুন</span>
                    <svg id="rgBtnArrow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <svg id="rgBtnSpin" class="w-5 h-5 rg-spin hidden" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                </button>
            </form>

            <p class="rg-in text-center text-sm text-gray-600 mt-6" style="--d:.35s">
                আগে থেকেই অ্যাকাউন্ট আছে?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 underline-offset-4 hover:underline">লগইন করুন</a>
            </p>
        </div>
    </main>
</div>

<script>
(function () {
    /* show / hide password */
    document.querySelectorAll('[data-eye]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var inp = document.getElementById(btn.getAttribute('data-eye'));
            inp.type = inp.type === 'password' ? 'text' : 'password';
            btn.style.color = inp.type === 'text' ? '#4f46e5' : '';
        });
    });

    /* password strength */
    var pw = document.getElementById('password');
    var cf = document.getElementById('password_confirmation');
    var bars = document.querySelectorAll('#rgMeter i');
    var txt = document.getElementById('rgMeterText');
    var colors = ['#f43f5e', '#f59e0b', '#84cc16', '#10b981'];
    var labels = ['খুব দুর্বল', 'মোটামুটি', 'ভালো', 'শক্তিশালী'];

    function score(v) {
        var s = 0;
        if (v.length >= 8) s++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
        if (/\d/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) s++;
        return s;
    }
    pw.addEventListener('input', function () {
        var v = pw.value, s = v ? Math.max(1, score(v)) : 0;
        bars.forEach(function (b, i) { b.style.background = i < s ? colors[s - 1] : '#e5e7eb'; });
        txt.textContent = s ? labels[s - 1] : 'একটি শক্তিশালী পাসওয়ার্ড দিন';
        txt.style.color = s ? colors[s - 1] : '#6b7280';
        checkMatch();
    });

    /* confirm match */
    var m = document.getElementById('rgMatch');
    function checkMatch() {
        if (!cf.value) { m.classList.add('hidden'); return; }
        m.classList.remove('hidden');
        if (cf.value === pw.value) { m.textContent = '✓ পাসওয়ার্ড মিলেছে'; m.style.color = '#059669'; }
        else { m.textContent = '✕ পাসওয়ার্ড মিলছে না'; m.style.color = '#e11d48'; }
    }
    cf.addEventListener('input', checkMatch);

    /* submit loading state */
    document.getElementById('rgForm').addEventListener('submit', function () {
        var b = document.getElementById('rgSubmit');
        b.classList.add('opacity-80', 'pointer-events-none');
        document.getElementById('rgBtnText').textContent = 'অপেক্ষা করুন...';
        document.getElementById('rgBtnArrow').classList.add('hidden');
        document.getElementById('rgBtnSpin').classList.remove('hidden');
    });

    /* 3D mouse parallax on left panel */
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var left = document.getElementById('rgLeft');
    var mouse = document.getElementById('rgMouse');
    if (left && mouse) {
        left.addEventListener('mousemove', function (e) {
            var r = left.getBoundingClientRect();
            mouse.style.setProperty('--mx', ((e.clientX - r.left) / r.width - 0.5).toFixed(3));
            mouse.style.setProperty('--my', ((e.clientY - r.top) / r.height - 0.5).toFixed(3));
        });
        left.addEventListener('mouseleave', function () {
            mouse.style.setProperty('--mx', 0);
            mouse.style.setProperty('--my', 0);
        });
    }
})();
</script>
</body>
</html>
