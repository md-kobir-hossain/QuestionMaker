@extends('layouts.admin')
@section('heading', 'CQ Questions (সৃজনশীল)')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <form method="GET" class="flex gap-2 text-sm">
        <input name="search" value="{{ request('search') }}" placeholder="Search stem..." class="border-gray-300 rounded-lg">
        <select name="chapter_id" class="border-gray-300 rounded-lg">
            <option value="">All chapters</option>
            @foreach($chapters as $ch)
                <option value="{{ $ch->id }}" @selected(request('chapter_id') == $ch->id)>{{ $ch->name }}</option>
            @endforeach
        </select>
        <button class="px-3 py-1.5 bg-gray-800 text-white rounded-lg">Filter</button>
    </form>
    <a href="{{ route('admin.cqs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New CQ</a>
</div>
<div class="space-y-3">
@forelse($cqs as $cq)
    <div class="bg-white rounded-xl shadow p-4">
        <div class="flex justify-between items-start gap-3">
            <div class="flex-1">
                <div class="text-xs text-gray-500">{{ $cq->chapter->subject->name ?? '' }} · {{ $cq->chapter->name ?? '' }} · Total: {{ $cq->total_marks }} marks</div>
                <div class="font-medium mt-1">উদ্দীপক: {!! nl2br(e($cq->stem)) !!}</div>
                <ul class="mt-2 text-sm space-y-1">
                    @foreach($cq->parts as $p)
                        <li><span class="font-semibold">{{ $p->label }}.</span> {{ $p->question }} <span class="text-gray-500">[{{ $p->marks }}]</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.cqs.edit', $cq) }}" class="text-indigo-600 hover:underline text-sm">Edit</a>
                <form method="POST" action="{{ route('admin.cqs.destroy', $cq) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-red-600 hover:underline text-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">No CQs yet.</div>
@endforelse
</div>
<div class="mt-4">{{ $cqs->links() }}</div>
@endsection
