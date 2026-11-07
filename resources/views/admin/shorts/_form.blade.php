@php $isEdit = isset($short); @endphp
<div>
    <label class="block text-sm font-medium mb-1">Chapter</label>
    <select name="chapter_id" required class="w-full border-gray-300 rounded-lg">
        <option value="">— Select —</option>
        @foreach($chapters as $ch)
            <option value="{{ $ch->id }}" @selected(old('chapter_id', $isEdit ? $short->chapter_id : null) == $ch->id)>{{ $ch->subject->name ?? '' }} — {{ $ch->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Question</label>
    <textarea name="question" rows="3" required class="w-full border-gray-300 rounded-lg font-mono text-sm">{{ old('question', $isEdit ? $short->question : '') }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Marks</label>
    <input name="marks" type="number" min="1" value="{{ old('marks', $isEdit ? $short->marks : 2) }}" class="w-full border-gray-300 rounded-lg">
</div>
