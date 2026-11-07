<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — QuestionBank BD')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Noto Sans Bengali', ui-sans-serif, system-ui, sans-serif; } </style>
    @stack('head')
</head>
<body class="h-full bg-gray-100" x-data="{ open: false }">
<div class="flex h-full">

    {{-- Sidebar --}}
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-100 transform transition lg:translate-x-0 lg:static lg:inset-auto">
        <div class="p-5 border-b border-slate-700">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-300">QB Admin</a>
        </div>
        <nav class="p-3 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-indigo-300' : '' }}">Dashboard</a>
            <a href="{{ route('admin.classes.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.classes.*') ? 'bg-slate-800 text-indigo-300' : '' }}">Classes</a>
            <a href="{{ route('admin.subjects.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.subjects.*') ? 'bg-slate-800 text-indigo-300' : '' }}">Subjects</a>
            <a href="{{ route('admin.chapters.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.chapters.*') ? 'bg-slate-800 text-indigo-300' : '' }}">Chapters</a>
            <div class="pt-3 text-xs uppercase text-slate-400 px-3">Questions</div>
            <a href="{{ route('admin.mcqs.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.mcqs.*') ? 'bg-slate-800 text-indigo-300' : '' }}">MCQ</a>
            <a href="{{ route('admin.shorts.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.shorts.*') ? 'bg-slate-800 text-indigo-300' : '' }}">Short</a>
            <a href="{{ route('admin.cqs.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.cqs.*') ? 'bg-slate-800 text-indigo-300' : '' }}">CQ</a>
            <a href="{{ route('admin.ocr.create') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.ocr.*') ? 'bg-slate-800 text-indigo-300' : '' }}">OCR Upload</a>
            <div class="pt-3 text-xs uppercase text-slate-400 px-3">Site</div>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-slate-800">← Public Site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="w-full text-left px-3 py-2 rounded hover:bg-slate-800 text-red-300">Logout</button>
            </form>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-6">
            <button @click="open=!open" class="lg:hidden p-2 rounded hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold">@yield('heading', 'Admin')</h1>
            <div class="text-sm text-gray-500">{{ auth()->user()->name }}</div>
        </header>

        <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
            @if(session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 border border-red-200">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 border border-red-200">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
    onload="document.addEventListener('DOMContentLoaded',function(){ if(window.renderMathInElement){ renderMathInElement(document.body, {delimiters:[{left:'$$',right:'$$',display:true},{left:'$',right:'$',display:false}]}); } });"></script>
@stack('scripts')
</body>
</html>
