@extends('layouts.admin')
@section('heading', 'Edit Subject')

@section('content')
<form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block text-sm font-medium mb-1">Class</label>
        <select name="class_id" required class="w-full border-gray-300 rounded-lg">
            @foreach($classes as $c)
                <option value="{{ $c->id }}" @selected(old('class_id', $subject->class_id) == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name', $subject->name) }}" required class="w-full border-gray-300 rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">CQ Pattern</label>
        <input name="cq_pattern_text" value="{{ old('cq_pattern_text', implode(',', $subject->cq_pattern ?? [1,2,3,4])) }}" class="w-full border-gray-300 rounded-lg font-mono">
    </div>
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
</form>
@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function(){
    const t = document.querySelector('[name=cq_pattern_text]').value;
    t.split(',').map(x => x.trim()).filter(Boolean).forEach(p => {
        const h = document.createElement('input'); h.type='hidden'; h.name='cq_pattern[]'; h.value=Number(p);
        this.appendChild(h);
    });
});
</script>
@endpush
@endsection
