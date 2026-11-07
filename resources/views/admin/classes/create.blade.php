@extends('layouts.admin')
@section('heading', 'New Class')

@section('content')
<form method="POST" action="{{ route('admin.classes.store') }}" class="bg-white rounded-xl shadow p-6 max-w-lg space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Class 9-10">
    </div>
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
</form>
@endsection
