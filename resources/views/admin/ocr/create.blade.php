@extends('layouts.admin')
@section('heading', 'OCR — Image to Question')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-3xl" x-data="ocrForm()">
    <form @submit.prevent="upload" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Type</label>
            <select x-model="type" class="w-full border-gray-300 rounded-lg">
                <option value="mcq">MCQ</option>
                <option value="short">Short Question</option>
                <option value="cq">CQ (সৃজনশীল)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Image (jpg/png/webp, max 5MB)</label>
            <input type="file" accept="image/*" @change="file = $event.target.files[0]" class="w-full border-gray-300 rounded-lg">
        </div>
        <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50" :disabled="loading">
            <span x-show="!loading">Extract text</span>
            <span x-show="loading">Extracting...</span>
        </button>
    </form>

    <div x-show="error" class="mt-4 p-3 rounded-lg bg-red-100 text-red-800 text-sm" x-text="error"></div>

    <div x-show="result" class="mt-6 grid md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium mb-2">Image</p>
            <img :src="imageUrl" class="w-full rounded-lg border" alt="uploaded">
        </div>
        <div class="space-y-3">
            <p class="text-sm font-medium">Extracted text (edit & save)</p>
<template x-if="result">
    <div class="space-y-3">
        <template x-if="type !== 'cq'">
            <textarea x-model="result.question" rows="3" class="w-full border-gray-300 rounded-lg text-sm"></textarea>
        </template>

        <template x-if="type === 'mcq'">
            <div class="space-y-2">
                <template x-for="k in ['a','b','c','d']" :key="k">
                    <div class="flex items-center gap-2">
                        <span class="w-5 text-sm font-medium" x-text="k"></span>
                        <input type="text" x-model="result.options[k]" class="flex-1 border-gray-300 rounded-lg text-sm">
                    </div>
                </template>
                <select x-model="result.correct_answer" class="border-gray-300 rounded-lg text-sm">
                    <option :value="null">Correct answer?</option>
                    <template x-for="k in ['a','b','c','d']"><option :value="k" x-text="k"></option></template>
                </select>
            </div>
        </template>

        <template x-if="type === 'cq'">
            <div class="space-y-2">
                <textarea x-model="result.stem" rows="3" class="w-full border-gray-300 rounded-lg text-sm" placeholder="উদ্দীপক"></textarea>
                <template x-for="(p, i) in result.parts" :key="i">
                    <div class="flex gap-2">
                        <input x-model="p.label" class="w-12 border-gray-300 rounded-lg text-sm">
                        <input x-model="p.text" class="flex-1 border-gray-300 rounded-lg text-sm">
                        <input x-model="p.marks" type="number" class="w-16 border-gray-300 rounded-lg text-sm">
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function ocrForm() {
    return {
        type: 'mcq', file: null, loading: false, error: '', result: null, imageUrl: '',
        async upload() {
            this.error = ''; this.result = null;
            if (!this.file) { this.error = 'Please select an image.'; return; }
            this.loading = true;
            try {
                const fd = new FormData();
                fd.append('image', this.file);
                fd.append('type', this.type);
                const res = await fetch('{{ route('admin.ocr.extract') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                    body: fd,
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message || 'Extraction failed');
                this.result = json.data;
                this.imageUrl = json.image_url;
            } catch (e) {
                this.error = e.message || 'Extraction failed — enter manually.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
