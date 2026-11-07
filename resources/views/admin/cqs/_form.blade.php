@php
    $isEdit = isset($cq);
    $existingParts = old('parts', $isEdit ? $cq->parts->map(fn($p) => ['label' => $p->label, 'question' => $p->question, 'marks' => $p->marks])->toArray() : []);
    $banglaLabels = ['ক','খ','গ','ঘ'];
@endphp

<div x-data="cqForm(@js($existingParts), @js($banglaLabels))">
    <div>
        <label class="block text-sm font-medium mb-1">Chapter</label>
        <select name="chapter_id" required class="w-full border-gray-300 rounded-lg"
                x-on:change="loadPattern($event.target.value)">
            <option value="">— Select —</option>
            @foreach($chapters as $ch)
                <option value="{{ $ch->id }}"
                    data-pattern="{{ implode(',', $ch->subject->cq_pattern ?? [1,2,3,4]) }}"
                    @selected(old('chapter_id', $isEdit ? $cq->chapter_id : null) == $ch->id)>
                    {{ $ch->subject->name ?? '' }} — {{ $ch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Stem (উদ্দীপক)</label>
        <textarea name="stem" rows="3" required class="w-full border-gray-300 rounded-lg font-mono text-sm">{{ old('stem', $isEdit ? $cq->stem : '') }}</textarea>
    </div>

    <div class="mt-4">
        <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-medium">Parts</label>
            <button type="button" @click="addPart()" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded">+ Add part</button>
        </div>
        <template x-for="(part, idx) in parts" :key="idx">
            <div class="border rounded-lg p-3 mb-2 bg-gray-50 space-y-2">
                <div class="flex items-center gap-2">
                    <input x-model="part.label" :name="`parts[${idx}][label]`" class="w-16 border-gray-300 rounded text-center font-semibold" readonly>
                    <input x-model="part.marks" :name="`parts[${idx}][marks]`" type="number" min="1" class="w-20 border-gray-300 rounded" placeholder="marks">
                    <span class="text-xs text-gray-500">marks</span>
                    <button type="button" @click="removePart(idx)" class="ml-auto text-xs text-red-600 hover:underline">Remove</button>
                </div>
                <textarea x-model="part.question" :name="`parts[${idx}][question]`" rows="2" required class="w-full border-gray-300 rounded font-mono text-sm" placeholder="Question text"></textarea>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function cqForm(initial, labels) {
    return {
        parts: initial && initial.length ? initial : [
            { label: labels[0], question: '', marks: 1 },
            { label: labels[1], question: '', marks: 2 },
            { label: labels[2], question: '', marks: 3 },
            { label: labels[3], question: '', marks: 4 },
        ],
        labels,
        addPart() {
            if (this.parts.length >= this.labels.length) return;
            this.parts.push({ label: this.labels[this.parts.length], question: '', marks: 1 });
        },
        removePart(i) {
            this.parts.splice(i, 1);
            this.parts.forEach((p, idx) => p.label = this.labels[idx]);
        },
        loadPattern(chapterId) {
            const opt = document.querySelector(`option[value="${chapterId}"]`);
            if (!opt) return;
            const pattern = (opt.dataset.pattern || '').split(',').map(Number).filter(Boolean);
            if (!pattern.length) return;
            this.parts = pattern.map((m, i) => ({ label: this.labels[i], question: '', marks: m }));
        }
    }
}
</script>
@endpush
