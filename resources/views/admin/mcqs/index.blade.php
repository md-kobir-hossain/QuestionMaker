@extends('layouts.admin')
@section('heading', 'MCQ Questions')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <form method="GET" class="flex gap-2 text-sm">
        <input name="search" value="{{ request('search') }}" placeholder="Search..." class="border-gray-300 rounded-lg">
        <select name="chapter_id" class="border-gray-300 rounded-lg">
            <option value="">All chapters</option>
            @foreach($chapters as $ch)
                <option value="{{ $ch->id }}" @selected(request('chapter_id') == $ch->id)>{{ $ch->name }}</option>
            @endforeach
        </select>
        <button class="px-3 py-1.5 bg-gray-800 text-white rounded-lg">Filter</button>
    </form>
    <a href="{{ route('admin.mcqs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New MCQ</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-600">
            <tr><th class="p-3">Question</th><th class="p-3">Chapter</th><th class="p-3">Marks</th><th class="p-3 w-40">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($mcqs as $m)
            <tr class="border-t align-top">
                <td class="p-3">
                    <div>{!! nl2br(e($m->question)) !!}</div>
                    <div class="text-xs text-gray-500 mt-1">ক) {{ $m->option_a }} · খ) {{ $m->option_b }} · গ) {{ $m->option_c }} · ঘ) {{ $m->option_d }} · ✔ {{ strtoupper($m->correct_answer) }}</div>
                </td>
                <td class="p-3">{{ $m->chapter->name ?? '—' }}</td>
                <td class="p-3">{{ $m->marks }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.mcqs.edit', $m) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.mcqs.destroy', $m) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="p-6 text-center text-gray-500">No MCQs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $mcqs->links() }}</div>
@endsection
