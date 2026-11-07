@extends('layouts.admin')
@section('heading', 'Subjects')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-600">{{ $subjects->total() }} subjects</p>
    <a href="{{ route('admin.subjects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New Subject</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-600">
            <tr><th class="p-3">Class</th><th class="p-3">Name</th><th class="p-3">CQ Pattern</th><th class="p-3">Chapters</th><th class="p-3 w-32">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($subjects as $s)
            <tr class="border-t">
                <td class="p-3">{{ $s->schoolClass->name ?? '—' }}</td>
                <td class="p-3">{{ $s->name }}</td>
                <td class="p-3 font-mono text-xs">[{{ implode(',', $s->cq_pattern ?? []) }}]</td>
                <td class="p-3">{{ $s->chapters_count }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.subjects.edit', $s) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.subjects.destroy', $s) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-6 text-center text-gray-500">No subjects yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $subjects->links() }}</div>
@endsection
