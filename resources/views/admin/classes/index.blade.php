@extends('layouts.admin')
@section('heading', 'Classes')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-gray-600 text-sm">{{ $classes->total() }} classes</p>
    <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New Class</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-600">
            <tr><th class="p-3">Name</th><th class="p-3">Subjects</th><th class="p-3 w-32">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($classes as $c)
            <tr class="border-t">
                <td class="p-3">{{ $c->name }}</td>
                <td class="p-3">{{ $c->subjects_count }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.classes.edit', $c) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.classes.destroy', $c) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-6 text-center text-gray-500">No classes yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $classes->links() }}</div>
@endsection
