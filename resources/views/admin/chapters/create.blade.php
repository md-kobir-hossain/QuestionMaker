@extends('layouts.admin')
@section('heading', 'New Chapter')

@section('content')
<form method="POST" action="{{ route('admin.chapters.store') }}" class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Subject</label>
        <select name="subject_id" required class="w-full border-gray-300 rounded-lg">
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" @selected(old('subject_id') == $s->id)>{{ $s->schoolClass->name ?? '' }} — {{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Position</label>
        <input name="position" type="number" value="{{ old('position', 0) }}" class="w-full border-gray-300 rounded-lg">
    </div>
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
</form>
@endsection
