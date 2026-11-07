@extends('layouts.app')

@section('title', 'Dashboard — QuestionBank BD')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">স্বাগতম, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-1">আজ আপনি কী করতে চান?</p>
        </div>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Admin Panel এ যান
            </a>
        @endif
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1 --}}
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:scale-110 transition"></div>
            <div class="relative z-10">
                <div class="text-indigo-100 text-sm font-medium mb-1">আমার মোট পেপার</div>
                <div class="text-4xl font-extrabold">{{ $totalPapers ?? '০' }}</div>
                <a href="{{ route('papers.index') }}" class="inline-flex items-center gap-1 text-sm mt-4 text-indigo-100 hover:text-white transition">
                    সব দেখুন <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-gray-500 text-sm font-medium mb-1">সর্বশেষ পেপার</div>
            <div class="text-lg font-bold text-gray-900 truncate">
                {{ $latestPaper->title ?? 'কোনো পেপার নেই' }}
            </div>
            @if($latestPaper)
                <div class="text-xs text-gray-400 mt-1">{{ $latestPaper->created_at->diffForHumans() }}</div>
            @endif
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="text-gray-500 text-sm font-medium mb-1">কুইক অ্যাকশন</div>
            <a href="{{ route('papers.builder') }}" class="inline-flex items-center justify-center w-full mt-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                + নতুন পেপার তৈরি করুন
            </a>
        </div>
    </div>

    {{-- Recent Papers List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="font-bold text-lg text-gray-900">সাম্প্রতিক পেপারসমূহ</h2>
            <a href="{{ route('papers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">সব দেখুন →</a>
        </div>

        @if($recentPapers && $recentPapers->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($recentPapers as $paper)
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $paper->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $paper->school_name }} • {{ $paper->exam_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-gray-900">{{ $paper->full_marks }} Marks</div>
                                <div class="text-xs text-gray-400">{{ $paper->created_at->format('d M, Y') }}</div>
                            </div>
                            <a href="{{ route('papers.show', $paper) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('papers.download', $paper) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">কোনো পেপার পাওয়া যায়নি</h3>
                <p class="text-gray-500 mb-4">আপনি এখনো কোনো প্রশ্নপত্র তৈরি করেননি।</p>
                <a href="{{ route('papers.builder') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                    প্রথম পেপার তৈরি করুন
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
