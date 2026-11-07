@extends('layouts.admin')
@section('heading', 'Edit CQ')
@section('content')
<form method="POST" action="{{ route('admin.cqs.update', $cq) }}" class="bg-white rounded-xl shadow p-6 max-w-4xl space-y-4">
    @csrf @method('PUT')
    @include('admin.cqs._form')
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
</form>
@endsection
