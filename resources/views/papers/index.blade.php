@extends('layouts.app')
@section('title', 'My Papers')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">আমার পেপার</h1>
    <a href="{{ route('papers.builder') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ নতুন পেপার</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-600">
            <tr><th class="p-3">Title</th><th class="p-3">School</th><th class="p-3">Exam</th><th class="p-3">Marks</th><th class="p-3">Date</th><th class="p-3 w-48">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($papers as $p)
            <tr class="border-t">
                <td class="p-3 font-medium">{{ $p->title }}</td>
                <td class="p-3">{{ $p->school_name }}</td>
                <td class="p-3">{{ $p->exam_name }}</td>
                <td class="p-3">{{ $p->full_marks }}</td>
                <td class="p-3">{{ $p->created_at->format('d M Y') }}</td>
                <td class="p-3 flex gap-3">
                    <a href="{{ route('papers.show', $p) }}" class="text-indigo-600 hover:underline">View</a>
                    <a href="{{ route('papers.download', $p) }}" class="text-emerald-600 hover:underline">PDF</a>
                    <form method="POST" action="{{ route('papers.destroy', $p) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="p-6 text-center text-gray-500">No papers yet. <a href="{{ route('papers.builder') }}" class="text-indigo-600 underline">Create one</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $papers->links() }}</div>
@endsection
