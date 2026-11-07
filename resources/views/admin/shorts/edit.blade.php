@extends('layouts.admin')
@section('heading', 'Edit Short Question')
@section('content')
<form method="POST" action="{{ route('admin.shorts.update', $short) }}" class="bg-white rounded-xl shadow p-6 max-w-3xl space-y-4">
    @csrf @method('PUT')
    @include('admin.shorts._form')
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
</form>
@endsection
