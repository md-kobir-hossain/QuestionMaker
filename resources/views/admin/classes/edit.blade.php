@extends('layouts.admin')
@section('heading', 'Edit Class')

@section('content')
<form method="POST" action="{{ route('admin.classes.update', $class) }}" class="bg-white rounded-xl shadow p-6 max-w-lg space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name', $class->name) }}" required class="w-full border-gray-300 rounded-lg shadow-sm">
    </div>
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
</form>
@endsection
