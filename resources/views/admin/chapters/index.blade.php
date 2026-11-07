@extends('layouts.admin')
@section('heading', 'Chapters')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-600">{{ $chapters->total() }} chapters</p>
    <a href="{{ route('admin.chapters.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New Chapter</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-600">
            <tr><th class="p-3">Class</th><th class="p-3">Subject</th><th class="p-3">Name</th><th class="p-3">Position</th><th class="p-3 w-32">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($chapters as $ch)
            <tr class="border-t">
                <td class="p-3">{{ $ch->subject->schoolClass->name ?? '—' }}</td>
                <td class="p-3">{{ $ch->subject->name ?? '—' }}</td>
                <td class="p-3">{{ $ch->name }}</td>
                <td class="p-3">{{ $ch->position }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.chapters.edit', $ch) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.chapters.destroy', $ch) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-6 text-center text-gray-500">No chapters yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $chapters->links() }}</div>
@endsection
