@extends('layouts.app')

@section('title', 'Paper Builder — QuestionBank BD')

@push('head')
<style>
    /* Custom Scrollbar for Question List */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Animated Checkbox */
    .premium-checkbox {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid #cbd5e1;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        flex-shrink: 0;
        background-color: white;
    }
    .premium-checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .premium-checkbox:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 14px;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Smooth Fade In for Questions */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: none; }
    }
    .question-item {
        animation: fadeInUp 0.3s ease forwards;
    }

    /* Tab active state */
    .tab-active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
    }
</style>
@endpush

@section('content')
<div x-data="builder()" x-init="init()" x-cloak>

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Paper Builder</h1>
            <p class="text-sm text-gray-500 mt-1">নিচের ধাপগুলো অনুসরণ করে আপনার প্রশ্নপত্র তৈরি করুন</p>
        </div>
        <a href="{{ route('papers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Papers
        </a>
    </div>

    <div class="grid lg:grid-cols-12 gap-8 items-start">

        {{-- Left Column: Filters & Questions --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Step 1: Filter --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">১</div>
                    <h2 class="font-bold text-lg text-gray-900">অধ্যায় নির্বাচন করুন</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Class</label>
                        <select x-model="classId" @change="fetchSubjects()" class="w-full border-gray-300 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">— Select Class —</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Subject</label>
                        <select x-model="subjectId" @change="fetchChapters()" :disabled="!subjects.length" class="w-full border-gray-300 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition disabled:opacity-50">
                            <option value="">— Select Subject —</option>
                            <template x-for="s in subjects" :key="s.id">
                                <option :value="s.id" x-text="s.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Chapters Grid --}}
                <div x-show="chapters.length > 0" class="mt-6">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Select Chapters</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="ch in chapters" :key="ch.id">
                            <label class="group relative flex items-center cursor-pointer">
                                <input type="checkbox" class="hidden" :value="ch.id" x-model="selectedChapters" @change="loadQuestions()">
                                <div class="px-4 py-2 rounded-xl border text-sm font-medium transition-all duration-200"
                                     :class="selectedChapters.includes(ch.id)
                                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-200'
                                        : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50'">
                                    <span x-text="ch.name"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Step 2: Question List --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-gray-50/50">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">২</div>
                    <h2 class="font-bold text-lg text-gray-900">প্রশ্ন বাছাই করুন</h2>
                </div>

                {{-- Tabs --}}
                <div class="flex px-6 pt-2 border-b border-gray-100">
                    <template x-for="t in ['mcq','short','cq']" :key="t">
                        <button @click="tab = t"
                                class="px-6 py-4 font-semibold text-sm border-b-2 transition-colors relative"
                                :class="tab === t ? 'text-indigo-600 border-indigo-600' : 'text-gray-500 border-transparent hover:text-gray-700'">
                            <span x-text="t === 'mcq' ? 'বহুনির্বাচনি (MCQ)' : t === 'short' ? 'সংক্ষিপ্ত (Short)' : 'সৃজনশীল (CQ)'"></span>
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500"
                                  x-text="tab === 'mcq' ? sel.mcq.length : tab === 'short' ? sel.short.length : sel.cq.length"
                                  x-show="(tab === 'mcq' ? sel.mcq.length : tab === 'short' ? sel.short.length : sel.cq.length) > 0"></span>
                        </button>
                    </template>
                </div>

                <div class="p-6 max-h-[65vh] overflow-y-auto custom-scrollbar bg-gray-50/30 space-y-6">
                    {{-- Loading State --}}
                    <div x-show="loading" class="text-center py-12">
                        <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-gray-500 text-sm">Loading questions...</p>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="!loading && grouped[tab].length === 0" class="text-center py-16 border-2 border-dashed border-gray-200 rounded-xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-gray-500">No questions found.</p>
                        <p class="text-xs text-gray-400 mt-1">Please select chapters above.</p>
                    </div>

                    {{-- Questions --}}
                    <template x-for="group in grouped[tab]" :key="group.chapter">
                        <div>
                            <h3 class="font-bold text-sm text-indigo-800 bg-indigo-50 inline-block px-3 py-1 rounded-lg mb-3" x-text="group.chapter"></h3>
                            <div class="space-y-3">
                                <template x-for="q in group.items" :key="q.id">
                                    <label class="question-item block bg-white border rounded-xl p-4 text-sm cursor-pointer transition-all duration-200"
                                           :class="isSelected(tab, q.id) ? 'border-indigo-500 shadow-md ring-1 ring-indigo-500 bg-indigo-50/10' : 'border-gray-200 hover:border-indigo-300 hover:shadow-sm'">

                                        <div class="flex items-start gap-3">
                                            <input type="checkbox" class="premium-checkbox mt-0.5" :checked="isSelected(tab, q.id)" @change="toggle(tab, q)">

                                            <div class="flex-1">
                                                {{-- Question Text --}}
                                                <div class="text-gray-800 font-medium leading-relaxed" x-html="formatQ(q)"></div>

                                                {{-- MCQ Options --}}
                                                <template x-if="tab === 'mcq'">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 text-gray-600">
                                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><span class="font-semibold text-gray-400 mr-1">ক)</span> <span x-text="q.option_a"></span></div>
                                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><span class="font-semibold text-gray-400 mr-1">খ)</span> <span x-text="q.option_b"></span></div>
                                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><span class="font-semibold text-gray-400 mr-1">গ)</span> <span x-text="q.option_c"></span></div>
                                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><span class="font-semibold text-gray-400 mr-1">ঘ)</span> <span x-text="q.option_d"></span></div>
                                                    </div>
                                                </template>

                                                {{-- CQ Parts --}}
                                                <template x-if="tab === 'cq'">
                                                    <ul class="mt-3 space-y-2 text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                        <template x-for="p in q.parts" :key="p.id">
                                                            <li class="flex gap-2">
                                                                <span class="font-bold text-indigo-500 min-w-[20px]" x-text="p.label + '.'"></span>
                                                                <span class="flex-1" x-text="p.question"></span>
                                                                <span class="text-xs font-semibold text-gray-400 whitespace-nowrap">[<span x-text="p.marks"></span>]</span>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </template>

                                                {{-- Marks Badge --}}
                                                <div class="mt-3 inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-md">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Marks: <span x-text="q.marks ?? (q.parts ? q.parts.reduce((a,p)=>a+parseInt(p.marks),0) : '')"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right Column: Summary & Save --}}
        <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">

            {{-- Selection Summary --}}
            <div class="bg-[#0f172a] rounded-2xl shadow-xl p-6 text-white overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500 rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>

                <h3 class="font-bold text-lg mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Selected Items
                </h3>

                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-sm border-b border-white/10 pb-3">
                        <span class="text-gray-400">MCQ</span>
                        <span class="font-bold text-lg" x-text="sel.mcq.length"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-white/10 pb-3">
                        <span class="text-gray-400">Short Questions</span>
                        <span class="font-bold text-lg" x-text="sel.short.length"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-white/10 pb-3">
                        <span class="text-gray-400">CQ</span>
                        <span class="font-bold text-lg" x-text="sel.cq.length"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <span class="text-gray-300 font-medium">Total Marks</span>
                        <span class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400" x-text="totalMarks"></span>
                    </div>
                </div>
            </div>

            {{-- Paper Details Form --}}
            <form method="POST" action="{{ route('papers.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                @csrf

                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">৩</div>
                    <h2 class="font-bold text-lg text-gray-900">পেপার ডিটেইলস</h2>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Paper Title</label>
                    <input name="title" placeholder="e.g. প্রথম সাময়িক পরীক্ষা" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">School Name</label>
                    <input name="school_name" placeholder="e.g. আদর্শ উচ্চ বিদ্যালয়" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Exam Name</label>
                        <input name="exam_name" placeholder="e.g. বার্ষিক" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Time</label>
                        <input name="exam_time" placeholder="e.g. ৩ ঘণ্টা" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Full Marks</label>
                    <input name="full_marks" type="number" x-model="totalMarks" required class="w-full border-gray-300 rounded-lg bg-gray-50 font-bold text-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer mt-2">
                    <input type="checkbox" name="include_answer_key" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    Include MCQ Answer Key
                </label>

                {{-- Hidden Inputs for selected IDs --}}
                <template x-for="id in sel.mcq" :key="'m'+id"><input type="hidden" name="mcq_ids[]" :value="id"></template>
                <template x-for="id in sel.short" :key="'s'+id"><input type="hidden" name="short_ids[]" :value="id"></template>
                <template x-for="id in sel.cq" :key="'c'+id"><input type="hidden" name="cq_ids[]" :value="id"></template>

                <button type="submit" class="w-full px-4 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 mt-4" :disabled="totalMarks === 0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Save Paper
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function builder() {
    return {
        classId: '', subjectId: '', subjects: [], chapters: [], selectedChapters: [],
        tab: 'mcq', loading: false,
        data: { mcqs: [], shorts: [], cqs: [] },
        sel: { mcq: [], short: [], cq: [] },
        init() {},
        async fetchSubjects() {
            if (!this.classId) return;
            this.subjects = [];
            this.subjectId = '';
            this.chapters = [];
            this.selectedChapters = [];
            this.loadQuestions();

            const r = await fetch(`/api/subjects?class_id=${this.classId}`);
            if (r.ok) this.subjects = await r.json();
        },
        async fetchChapters() {
            if (!this.subjectId) return;
            this.chapters = [];
            this.selectedChapters = [];
            this.loadQuestions();

            const r = await fetch(`/api/chapters?subject_id=${this.subjectId}`);
            if (r.ok) this.chapters = await r.json();
        },
        async loadQuestions() {
            if (!this.selectedChapters.length) {
                this.data = { mcqs: [], shorts: [], cqs: [] };
                return;
            }
            this.loading = true;
            try {
                const r = await fetch('{{ route('papers.fetch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ chapter_ids: this.selectedChapters }),
                });
                this.data = await r.json();
            } finally {
                this.loading = false;
            }
        },
        get grouped() {
            const out = { mcq: [], short: [], cq: [] };
            const add = (type, items) => {
                if (!Array.isArray(items)) return;
                const map = {};
                items.forEach(it => {
                    const cname = it.chapter?.name || 'Unknown';
                    if (!map[cname]) map[cname] = [];
                    map[cname].push(it);
                });
                out[type] = Object.entries(map).map(([chapter, items]) => ({ chapter, items }));
            };
            add('mcq', this.data.mcqs);
            add('short', this.data.shorts);
            add('cq', this.data.cqs);
            return out;
        },
        isSelected(type, id) { return this.sel[type].includes(id); },
        toggle(type, q) {
            const idx = this.sel[type].indexOf(q.id);
            if (idx > -1) this.sel[type].splice(idx, 1);
            else this.sel[type].push(q.id);
        },
        formatQ(q) {
            const text = q.question || q.stem || '';
            return text.replace(/</g, '&lt;');
        },
        get totalMarks() {
            let total = 0;
            const find = (arr, id) => arr.find(x => x.id === id);
            this.sel.mcq.forEach(id => { const q = find(this.data.mcqs, id); if (q) total += parseInt(q.marks || 1); });
            this.sel.short.forEach(id => { const q = find(this.data.shorts, id); if (q) total += parseInt(q.marks || 1); });
            this.sel.cq.forEach(id => { const q = find(this.data.cqs, id); if (q) total += q.parts.reduce((a,p)=>a+parseInt(p.marks),0); });
            return total;
        }
    }
}
</script>
@endpush
