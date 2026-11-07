@extends('layouts.admin')

@section('heading', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    @foreach([
        ['Classes', $classes, 'indigo'],
        ['MCQ', $mcqs, 'emerald'],
        ['Short', $shorts, 'amber'],
        ['CQ', $cqs, 'rose'],
        ['Papers', $papers, 'sky'],
        ['Users', $users, 'violet'],
    ] as [$label, $val, $color])
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-{{ $color }}-500">
            <div class="text-xs uppercase text-gray-500">{{ $label }}</div>
            <div class="text-2xl font-bold">{{ $val }}</div>
        </div>
    @endforeach
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold text-lg mb-3">Quick actions</h2>
    <div class="flex flex-wrap gap-3 text-sm">
        <a href="{{ route('admin.mcqs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ MCQ</a>
        <a href="{{ route('admin.shorts.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">+ Short</a>
        <a href="{{ route('admin.cqs.create') }}" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700">+ CQ</a>
        <a href="{{ route('admin.ocr.create') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">OCR Upload</a>
    </div>
</div>
@endsection
