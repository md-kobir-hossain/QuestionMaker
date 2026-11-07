@php $isEdit = isset($mcq); @endphp
<div>
    <label class="block text-sm font-medium mb-1">Chapter</label>
    <select name="chapter_id" required class="w-full border-gray-300 rounded-lg">
        <option value="">— Select —</option>
        @foreach($chapters as $ch)
            <option value="{{ $ch->id }}" @selected(old('chapter_id', $isEdit ? $mcq->chapter_id : null) == $ch->id)>{{ $ch->subject->name ?? '' }} — {{ $ch->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Question (Bangla + LaTeX with $...$)</label>
    <textarea name="question" rows="3" required class="w-full border-gray-300 rounded-lg font-mono text-sm">{{ old('question', $isEdit ? $mcq->question : '') }}</textarea>
</div>
<div class="grid grid-cols-2 gap-3">
    @foreach(['a','b','c','d'] as $opt)
        <div>
            <label class="block text-sm font-medium mb-1">Option {{ strtoupper($opt) }}</label>
            <input name="option_{{ $opt }}" value="{{ old('option_'.$opt, $isEdit ? $mcq->{'option_'.$opt} : '') }}" required class="w-full border-gray-300 rounded-lg">
        </div>
    @endforeach
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-sm font-medium mb-1">Correct answer</label>
        <select name="correct_answer" required class="w-full border-gray-300 rounded-lg">
            @foreach(['a','b','c','d'] as $opt)
                <option value="{{ $opt }}" @selected(old('correct_answer', $isEdit ? $mcq->correct_answer : 'a') === $opt)>{{ strtoupper($opt) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Marks</label>
        <input name="marks" type="number" min="1" value="{{ old('marks', $isEdit ? $mcq->marks : 1) }}" class="w-full border-gray-300 rounded-lg">
    </div>
</div>
