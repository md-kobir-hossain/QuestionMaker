@extends('layouts.admin')
@section('heading', 'New Short Question')
@section('content')
<form method="POST" action="{{ route('admin.shorts.store') }}" class="bg-white rounded-xl shadow p-6 max-w-3xl space-y-4">
    @csrf
    @include('admin.shorts._form')
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
</form>
@endsection
