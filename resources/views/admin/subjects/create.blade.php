@extends('layouts.admin')
@section('heading', 'New Subject')

@section('content')
<form method="POST" action="{{ route('admin.subjects.store') }}" class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Class</label>
        <select name="class_id" required class="w-full border-gray-300 rounded-lg">
            @foreach($classes as $c)
                <option value="{{ $c->id }}" @selected(old('class_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">CQ Pattern (comma-separated marks)</label>
        <input name="cq_pattern_text" value="{{ old('cq_pattern_text', '1,2,3,4') }}" class="w-full border-gray-300 rounded-lg font-mono" placeholder="1,2,3,4 or 2,4,4">
        <p class="text-xs text-gray-500 mt-1">Bangla: ক=1, খ=2, গ=3, ঘ=4. Math: ক=2, খ=4, গ=4.</p>
    </div>
    <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
</form>
@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function(){
    const t = document.querySelector('[name=cq_pattern_text]').value;
    const parts = t.split(',').map(x => x.trim()).filter(Boolean).map(Number);
    const hidden = document.createElement('input');
    hidden.type = 'hidden'; hidden.name = 'cq_pattern[]';
    parts.forEach(p => {
        const h = document.createElement('input'); h.type='hidden'; h.name='cq_pattern[]'; h.value=p;
        this.appendChild(h);
    });
});
</script>
@endpush
@endsection
