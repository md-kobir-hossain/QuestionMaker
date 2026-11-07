<!DOCTYPE html>
<html lang="bn" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QuestionBank BD')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; }

        /* ---------- scroll progress ---------- */
        #qbProgress { position: fixed; top: 0; left: 0; height: 3px; width: 100%; transform-origin: 0 50%; transform: scaleX(0); background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899); z-index: 100; }

        /* ---------- navbar ---------- */
        #qbNav { transition: background-color .3s, box-shadow .3s, border-color .3s; background: rgba(255,255,255,.72); backdrop-filter: saturate(180%) blur(14px); -webkit-backdrop-filter: saturate(180%) blur(14px); border-bottom: 1px solid rgba(229,231,235,.6); }
        #qbNav.scrolled { background: rgba(255,255,255,.92); box-shadow: 0 8px 30px -12px rgba(49,46,129,.22); border-color: rgba(229,231,235,1); }

        .qb-link { position: relative; padding: 6px 2px; color: #4b5563; transition: color .2s; }
        .qb-link::after { content: ""; position: absolute; left: 0; right: 0; bottom: -2px; height: 2px; border-radius: 2px; background: linear-gradient(90deg, #6366f1, #a855f7); transform: scaleX(0); transform-origin: left; transition: transform .3s cubic-bezier(.22,.8,.24,1); }
        .qb-link:hover { color: #4f46e5; }
        .qb-link:hover::after, .qb-link.active::after { transform: scaleX(1); }
        .qb-link.active { color: #4f46e5; font-weight: 600; }

        .qb-logo-mark { transition: transform .5s cubic-bezier(.22,.8,.24,1); }
        .group:hover .qb-logo-mark { transform: rotate(-8deg) scale(1.08); }

        /* ---------- dropdown ---------- */
        .qb-dropdown { opacity: 0; visibility: hidden; transform: translateY(8px) scale(.97); transform-origin: top right; transition: opacity .2s, transform .2s, visibility .2s; }
        .qb-dropdown.open { opacity: 1; visibility: visible; transform: none; }

        /* ---------- mobile menu ---------- */
        #qbMobile { max-height: 0; overflow: hidden; transition: max-height .4s cubic-bezier(.22,.8,.24,1); }
        #qbMobile.open { max-height: 460px; }
        .qb-burger i { display: block; width: 20px; height: 2px; background: #374151; border-radius: 2px; transition: transform .3s, opacity .3s; }
        .qb-burger i + i { margin-top: 5px; }
        .qb-burger.open i:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .qb-burger.open i:nth-child(2) { opacity: 0; }
        .qb-burger.open i:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ---------- toasts ---------- */
        .qb-toast { transform: translateX(120%); opacity: 0; animation: qbToastIn .5s cubic-bezier(.22,.8,.24,1) forwards; }
        .qb-toast.out { animation: qbToastOut .35s ease-in forwards; }
        @keyframes qbToastIn { to { transform: none; opacity: 1; } }
        @keyframes qbToastOut { to { transform: translateX(120%); opacity: 0; } }
        .qb-toast-bar { transform-origin: left; animation: qbBar 5s linear forwards; }
        @keyframes qbBar { to { transform: scaleX(0); } }

        /* ---------- page enter ---------- */
        @keyframes qbPage { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        #qbMain { animation: qbPage .5s ease-out both; }

        /* ---------- footer ---------- */
        .qb-footer { flex-shrink: 0; background: #0b1020; position: relative; overflow: hidden; isolation: isolate; }
        .qb-footer::before, .qb-footer::after { content: ""; position: absolute; border-radius: 9999px; filter: blur(90px); z-index: -1; }
        .qb-footer::before { width: 26rem; height: 26rem; background: rgba(79,70,229,.35); top: -12rem; left: -8rem; }
        .qb-footer::after { width: 24rem; height: 24rem; background: rgba(147,51,234,.28); bottom: -12rem; right: -6rem; }
        .qb-flink { transition: color .2s, transform .2s; display: inline-flex; align-items: center; gap: 8px; }
        .qb-flink:hover { color: #fff; transform: translateX(4px); }

        /* ---------- back to top ---------- */
        #qbTop { opacity: 0; visibility: hidden; transform: translateY(14px); transition: all .3s; }
        #qbTop.show { opacity: 1; visibility: visible; transform: none; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .qb-toast { transform: none; opacity: 1; }
        }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <div id="qbProgress"></div>

    {{-- ================= Navbar ================= --}}
    <nav id="qbNav" class="sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="qb-logo-mark w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-300/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">QuestionBank<span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"> BD</span></span>
            </a>

            {{-- Desktop links --}}
            <div class="hidden md:flex items-center gap-7 text-sm font-medium">
                <a href="{{ route('home') }}" class="qb-link {{ request()->routeIs('home') ? 'active' : '' }}">হোম</a>

                @auth
                    <a href="{{ route('papers.builder') }}" class="qb-link {{ request()->routeIs('papers.builder') ? 'active' : '' }}">প্রশ্নপত্র তৈরি করুন</a>
                    <a href="{{ route('papers.index') }}" class="qb-link {{ request()->routeIs('papers.index') ? 'active' : '' }}">আমার পেপার</a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Admin
                        </a>
                    @endif

                    <div class="w-px h-6 bg-gray-200"></div>

                    {{-- User dropdown --}}
                    <div class="relative">
                        <button type="button" id="qbUserBtn" class="flex items-center gap-2 pl-1 pr-2.5 py-1 rounded-full hover:bg-gray-100 transition" aria-haspopup="true" aria-expanded="false">
                            <span class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold uppercase shadow-md">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                            <span class="max-w-[110px] truncate text-gray-700">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div id="qbUserMenu" class="qb-dropdown absolute right-0 mt-3 w-64 bg-white rounded-2xl border border-gray-100 shadow-2xl shadow-indigo-900/10 p-2">
                            <div class="px-3 py-3 border-b border-gray-100 mb-1">
                                <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('papers.builder') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> নতুন পেপার
                            </a>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor"
     viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2"
          d="M3 13h8V3H3v10zm0 8h8v-4H3v4zm10 0h8V11h-8v10zm0-18v4h8V3h-8z"/>
</svg>
ড্যাশবোর্ড                            </a>
                            <a href="{{ route('papers.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> আমার পেপার
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 pt-1 border-t border-gray-100">@csrf
                                <button class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> লগআউট
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="qb-link {{ request()->routeIs('login') ? 'active' : '' }}">লগইন</a>
                    <a href="{{ route('register') }}" class="relative overflow-hidden px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-300/60 hover:shadow-xl hover:-translate-y-0.5 transition-all">রেজিস্টার</a>
                @endauth
            </div>

            {{-- Mobile burger --}}
            <button type="button" id="qbBurger" class="qb-burger md:hidden p-2.5 rounded-xl hover:bg-gray-100 transition" aria-label="মেনু" aria-expanded="false">
                <i></i><i></i><i></i>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="qbMobile" class="md:hidden border-t border-gray-100 bg-white/95 backdrop-blur">
            <div class="px-4 py-4 space-y-1 text-sm font-medium">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">হোম</a>
                @auth
                    <a href="{{ route('papers.builder') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('papers.builder') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">পেপার তৈরি</a>
<a href="{{ route('papers.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('papers.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">আমার পেপার</a>
                    <a href="{{ route('papers.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('papers.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">আমার পেপার</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-indigo-700 font-semibold hover:bg-indigo-50">Admin প্যানেল</a>
                    @endif
                    <div class="flex items-center gap-3 px-4 pt-4 mt-2 border-t border-gray-100">
                        <span class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold uppercase">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="px-3 py-2 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 text-sm font-semibold">লগআউট</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50">লগইন</a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl text-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-md">রেজিস্টার</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ================= Toasts (flash messages) ================= --}}
    <div id="qbToasts" class="fixed top-20 right-4 z-[90] w-[calc(100%-2rem)] max-w-sm space-y-3 pointer-events-none">
        @if(session('success'))
            <div class="qb-toast pointer-events-auto relative overflow-hidden bg-white border border-emerald-200 rounded-2xl shadow-xl shadow-emerald-900/10 px-4 py-3.5 flex items-start gap-3" role="status">
                <span class="w-8 h-8 shrink-0 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </span>
                <p class="flex-1 text-sm font-medium text-gray-800 pt-1">{{ session('success') }}</p>
                <button type="button" data-close class="text-gray-400 hover:text-gray-600 p-1" aria-label="বন্ধ করুন">✕</button>
                <span class="qb-toast-bar absolute left-0 bottom-0 h-1 w-full bg-emerald-400"></span>
            </div>
        @endif
        @if(session('error'))
            <div class="qb-toast pointer-events-auto relative overflow-hidden bg-white border border-red-200 rounded-2xl shadow-xl shadow-red-900/10 px-4 py-3.5 flex items-start gap-3" role="alert">
                <span class="w-8 h-8 shrink-0 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01"/></svg>
                </span>
                <p class="flex-1 text-sm font-medium text-gray-800 pt-1">{{ session('error') }}</p>
                <button type="button" data-close class="text-gray-400 hover:text-gray-600 p-1" aria-label="বন্ধ করুন">✕</button>
                <span class="qb-toast-bar absolute left-0 bottom-0 h-1 w-full bg-red-400"></span>
            </div>
        @endif
    </div>

    {{-- ================= Main ================= --}}
    <main id="qbMain" class="flex-grow w-full relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
            @yield('content')
        </div>
    </main>

    {{-- ================= Footer ================= --}}
    <footer class="qb-footer text-gray-400 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-5">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-900/40">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="text-xl font-bold text-white">QuestionBank <span class="text-indigo-300">BD</span></span>
                    </a>
                    <p class="text-sm text-gray-400 max-w-sm leading-relaxed">
                        বাংলাদেশের স্কুল ও কলেজের শিক্ষক এবং শিক্ষার্থীদের জন্য একটি আধুনিক ও সম্পূর্ণ বাংলা প্রশ্নব্যাংক প্ল্যাটফর্ম। সহজে প্রশ্ন তৈরি করুন এবং PDF ডাউনলোড করুন।
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-white mb-5 uppercase tracking-widest">লিংক</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="qb-flink"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg> হোম</a></li>
                        <li><a href="{{ route('papers.builder') }}" class="qb-flink"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg> পেপার তৈরি</a></li>
                        <li><a href="{{ route('login') }}" class="qb-flink"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg> লগইন</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-white mb-5 uppercase tracking-widest">যোগাযোগ</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-indigo-300"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                            support@questionbank.bd
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-indigo-300"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                            ঢাকা, বাংলাদেশ
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} QuestionBank BD. সর্বস্বত্ব সংরক্ষিত।</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">গোপনীয়তা নীতি</a>
                    <a href="#" class="hover:text-white transition">ব্যবহারের শর্তাবলী</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to top --}}
    <button type="button" id="qbTop" class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 text-white shadow-xl shadow-indigo-400/40 flex items-center justify-center hover:-translate-y-1 transition" aria-label="উপরে যান">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
    </button>

    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
        onload="document.addEventListener('DOMContentLoaded',function(){ if(window.renderMathInElement){ renderMathInElement(document.body, {delimiters:[{left:'$$',right:'$$',display:true},{left:'$',right:'$',display:false}]}); } });"></script>

    <script>
    (function () {
        var nav = document.getElementById('qbNav');
        var bar = document.getElementById('qbProgress');
        var top = document.getElementById('qbTop');

        /* navbar shadow, progress bar, back-to-top */
        function onScroll() {
            var y = window.scrollY || document.documentElement.scrollTop;
            var h = document.documentElement.scrollHeight - window.innerHeight;
            nav.classList.toggle('scrolled', y > 8);
            bar.style.transform = 'scaleX(' + (h > 0 ? Math.min(y / h, 1) : 0) + ')';
            top.classList.toggle('show', y > 500);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
        top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

        /* mobile menu */
        var burger = document.getElementById('qbBurger');
        var mobile = document.getElementById('qbMobile');
        burger.addEventListener('click', function () {
            var open = mobile.classList.toggle('open');
            burger.classList.toggle('open', open);
            burger.setAttribute('aria-expanded', open);
        });

        /* user dropdown */
        var ub = document.getElementById('qbUserBtn');
        var um = document.getElementById('qbUserMenu');
        if (ub && um) {
            ub.addEventListener('click', function (e) {
                e.stopPropagation();
                var open = um.classList.toggle('open');
                ub.setAttribute('aria-expanded', open);
            });
            document.addEventListener('click', function (e) {
                if (!um.contains(e.target)) { um.classList.remove('open'); ub.setAttribute('aria-expanded', 'false'); }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') um.classList.remove('open');
            });
        }

        /* toasts: auto-dismiss after 5s or on close */
        function dismiss(t) {
            if (!t || t.classList.contains('out')) return;
            t.classList.add('out');
            setTimeout(function () { t.remove(); }, 350);
        }
        document.querySelectorAll('.qb-toast').forEach(function (t) {
            var timer = setTimeout(function () { dismiss(t); }, 5000);
            t.querySelector('[data-close]').addEventListener('click', function () { clearTimeout(timer); dismiss(t); });
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
