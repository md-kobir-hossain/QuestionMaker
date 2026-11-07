<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Cq;
use App\Models\Mcq;
use App\Models\Paper;
use App\Models\SchoolClass;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // ---------- Users ----------
            $admin = User::updateOrCreate(
                ['email' => 'admin@example.com'],
                ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
            );
            $user = User::updateOrCreate(
                ['email' => 'user@example.com'],
                ['name' => 'Demo User', 'password' => Hash::make('password'), 'role' => 'user']
            );

            // ---------- Classes ----------
            $class9 = SchoolClass::firstOrCreate(['name' => 'Class 9-10']);
            $class11 = SchoolClass::firstOrCreate(['name' => 'Class 11-12']);

            // ---------- Subjects ----------
            $bangla = Subject::firstOrCreate(
                ['class_id' => $class9->id, 'name' => 'বাংলা'],
                ['cq_pattern' => [1, 2, 3, 4]]
            );
            $english = Subject::firstOrCreate(
                ['class_id' => $class9->id, 'name' => 'English'],
                ['cq_pattern' => [1, 2, 3, 4]]
            );
            $math = Subject::firstOrCreate(
                ['class_id' => $class9->id, 'name' => 'গণিত'],
                ['cq_pattern' => [2, 4, 4]]
            );
            $physics = Subject::firstOrCreate(
                ['class_id' => $class9->id, 'name' => 'পদার্থবিজ্ঞান'],
                ['cq_pattern' => [1, 2, 3, 4]]
            );
            $chemistry = Subject::firstOrCreate(
                ['class_id' => $class9->id, 'name' => 'রসায়ন'],
                ['cq_pattern' => [1, 2, 3, 4]]
            );

            // ---------- Chapters ----------
            $banglaCh1 = Chapter::firstOrCreate(
                ['subject_id' => $bangla->id, 'name' => 'গদ্য: সুভা'],
                ['position' => 1]
            );
            $banglaCh2 = Chapter::firstOrCreate(
                ['subject_id' => $bangla->id, 'name' => 'পদ্য: মানুষ'],
                ['position' => 2]
            );
            $engCh1 = Chapter::firstOrCreate(
                ['subject_id' => $english->id, 'name' => 'Unit 1: Good Citizens'],
                ['position' => 1]
            );
            $mathCh1 = Chapter::firstOrCreate(
                ['subject_id' => $math->id, 'name' => 'সূচক ও লগারিদম'],
                ['position' => 1]
            );
            $mathCh2 = Chapter::firstOrCreate(
                ['subject_id' => $math->id, 'name' => 'ত্রিভুজ'],
                ['position' => 2]
            );
            $physicsCh1 = Chapter::firstOrCreate(
                ['subject_id' => $physics->id, 'name' => 'ভৌত রাশি ও পরিমাপ'],
                ['position' => 1]
            );
            $chemCh1 = Chapter::firstOrCreate(
                ['subject_id' => $chemistry->id, 'name' => 'রসায়নের ধারণা'],
                ['position' => 1]
            );

            // ---------- MCQs (10) ----------
            $mcqData = [
                [
                    'chapter_id' => $banglaCh1->id,
                    'question' => 'বাংলা বর্ণমালায় স্বরবর্ণ কতটি?',
                    'option_a' => '৭', 'option_b' => '৯', 'option_c' => '১১', 'option_d' => '১৩',
                    'correct_answer' => 'c', 'marks' => 1,
                ],
                [
                    'chapter_id' => $banglaCh1->id,
                    'question' => 'বাংলা বর্ণমালায় ব্যঞ্জনবর্ণ কতটি?',
                    'option_a' => '৩৫', 'option_b' => '৩৭', 'option_c' => '৩৯', 'option_d' => '৪১',
                    'correct_answer' => 'c', 'marks' => 1,
                ],
                [
                    'chapter_id' => $banglaCh2->id,
                    'question' => '“মানুষ” কবিতাটি কার লেখা?',
                    'option_a' => 'রবীন্দ্রনাথ ঠাকুর', 'option_b' => 'কাজী নজরুল ইসলাম',
                    'option_c' => 'জীবনানন্দ দাশ', 'option_d' => 'সুকুমার রায়',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $mathCh1->id,
                    'question' => '$2^3 = ?$',
                    'option_a' => '6', 'option_b' => '8', 'option_c' => '9', 'option_d' => '12',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $mathCh1->id,
                    'question' => '$\\log_{10} 100 = ?$',
                    'option_a' => '1', 'option_b' => '2', 'option_c' => '10', 'option_d' => '100',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $mathCh2->id,
                    'question' => 'সমকোণী ত্রিভুজের সমকোণ সংলগ্ন বাহু দুটির নাম কী?',
                    'option_a' => 'অতিভুজ ও ভূমি', 'option_b' => 'লম্ব ও ভূমি',
                    'option_c' => 'লম্ব ও অতিভুজ', 'option_d' => 'কোনোটিই নয়',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $physicsCh1->id,
                    'question' => 'দৈর্ঘ্যের একক কোনটি?',
                    'option_a' => 'কেজি', 'option_b' => 'সেকেন্ড', 'option_c' => 'মিটার', 'option_d' => 'অ্যাম্পিয়ার',
                    'correct_answer' => 'c', 'marks' => 1,
                ],
                [
                    'chapter_id' => $physicsCh1->id,
                    'question' => 'আলোর বেগ কত?',
                    'option_a' => '$3 \\times 10^6$ m/s', 'option_b' => '$3 \\times 10^8$ m/s',
                    'option_c' => '$3 \\times 10^{10}$ m/s', 'option_d' => '$3 \\times 10^5$ m/s',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $chemCh1->id,
                    'question' => 'পানির রাসায়নিক সংকেত কোনটি?',
                    'option_a' => 'CO₂', 'option_b' => 'H₂O', 'option_c' => 'O₂', 'option_d' => 'NaCl',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
                [
                    'chapter_id' => $engCh1->id,
                    'question' => 'The synonym of "brave" is —',
                    'option_a' => 'coward', 'option_b' => 'bold', 'option_c' => 'weak', 'option_d' => 'lazy',
                    'correct_answer' => 'b', 'marks' => 1,
                ],
            ];

            foreach ($mcqData as $m) {
                Mcq::firstOrCreate(
                    ['question' => $m['question'], 'chapter_id' => $m['chapter_id']],
                    $m + ['created_by' => $admin->id]
                );
            }

            // ---------- Short Questions (6) ----------
            $shortData = [
                ['chapter_id' => $banglaCh1->id, 'question' => '“সুভা” গল্পের লেখকের নাম লিখ।', 'marks' => 2],
                ['chapter_id' => $banglaCh2->id, 'question' => '“মানুষ” কবিতার মূলভাব সংক্ষেপে লেখ।', 'marks' => 2],
                ['chapter_id' => $mathCh1->id, 'question' => 'সূচকের তিনটি নিয়ম লেখ।', 'marks' => 2],
                ['chapter_id' => $mathCh2->id, 'question' => 'পিথাগোরাসের উপপাদ্যটি বিবৃত কর।', 'marks' => 2],
                ['chapter_id' => $physicsCh1->id, 'question' => 'মৌলিক ও লব্ধ রাশির পার্থক্য লেখ।', 'marks' => 2],
                ['chapter_id' => $chemCh1->id, 'question' => 'রাসায়নিক বিক্রিয়ার দুটি বৈশিষ্ট্য লেখ।', 'marks' => 2],
            ];

            foreach ($shortData as $s) {
                ShortQuestion::firstOrCreate(
                    ['question' => $s['question']],
                    $s + ['created_by' => $admin->id]
                );
            }

            // ---------- CQs (4) ----------
            // 1) Math CQ — parts [2,4,4]
            $mathCq = Cq::firstOrCreate(
                ['stem' => 'একটি সমকোণী ত্রিভুজের বাহু তিনটির দৈর্ঘ্য যথাক্রমে $a = 3$ সে.মি., $b = 4$ সে.মি. এবং $c = 5$ সে.মি.।'],
                ['chapter_id' => $mathCh2->id, 'created_by' => $admin->id]
            );
            if ($mathCq->parts()->count() === 0) {
                $mathParts = [
                    ['label' => 'ক', 'question' => 'ত্রিভুজটির পরিসীমা নির্ণয় কর।', 'marks' => 2],
                    ['label' => 'খ', 'question' => 'ত্রিভুজটির ক্ষেত্রফল নির্ণয় কর।', 'marks' => 4],
                    ['label' => 'গ', 'question' => 'ত্রিভুজটি সমকোণী কি না যাচাই কর।', 'marks' => 4],
                ];
                foreach ($mathParts as $i => $p) {
                    $mathCq->parts()->create($p + ['position' => $i]);
                }
            }

            // 2) Bangla CQ — parts [1,2,3,4]
            $banglaCq = Cq::firstOrCreate(
                ['stem' => 'সুভা ছিল একটি বোবা মেয়ে। তার মনের ভাব প্রকাশের একমাত্র মাধ্যম ছিল তার চোখ। একদিন নদীর ধারে বসে সে দেখল—একদল ছেলে তাকে নিয়ে ঠাট্টা করছে।'],
                ['chapter_id' => $banglaCh1->id, 'created_by' => $admin->id]
            );
            if ($banglaCq->parts()->count() === 0) {
                $banglaParts = [
                    ['label' => 'ক', 'question' => '“সুভা” গল্পটি কার লেখা?', 'marks' => 1],
                    ['label' => 'খ', 'question' => 'সুভা বোবা হয়েও কীভাবে নিজের অনুভূতি প্রকাশ করত—ব্যাখ্যা কর।', 'marks' => 2],
                    ['label' => 'গ', 'question' => 'উদ্দীপকের ঘটনাটি গল্পের কোন অংশের সঙ্গে সাদৃশ্যপূর্ণ—আলোচনা কর।', 'marks' => 3],
                    ['label' => 'ঘ', 'question' => '“প্রকৃতির নিকটে থাকলেই মানুষ শান্তি পায়”—উক্তিটি উদ্দীপকের আলোকে বিশ্লেষণ কর।', 'marks' => 4],
                ];
                foreach ($banglaParts as $i => $p) {
                    $banglaCq->parts()->create($p + ['position' => $i]);
                }
            }

            // 3) Physics CQ — parts [1,2,3,4]
            $physCq = Cq::firstOrCreate(
                ['stem' => 'একজন ছাত্র স্কুলে যাওয়ার সময় ১০ মিনিটে ৬০০ মিটার পথ অতিক্রম করে। আবার ফেরার পথে সে একই দূরত্ব ১৫ মিনিটে অতিক্রম করে।'],
                ['chapter_id' => $physicsCh1->id, 'created_by' => $admin->id]
            );
            if ($physCq->parts()->count() === 0) {
                $physParts = [
                    ['label' => 'ক', 'question' => 'গতির সংজ্ঞা দাও।', 'marks' => 1],
                    ['label' => 'খ', 'question' => 'দ্রুতি ও বেগের পার্থক্য লেখ।', 'marks' => 2],
                    ['label' => 'গ', 'question' => 'যাওয়ার সময়কার গড় দ্রুতি নির্ণয় কর।', 'marks' => 3],
                    ['label' => 'ঘ', 'question' => 'যাওয়া ও ফেরার গড় দ্রুতির তুলনা কর।', 'marks' => 4],
                ];
                foreach ($physParts as $i => $p) {
                    $physCq->parts()->create($p + ['position' => $i]);
                }
            }

            // 4) Chemistry CQ — parts [1,2,3,4]
            $chemCq = Cq::firstOrCreate(
                ['stem' => 'একটি পরীক্ষাগারে লবণাক্ত পানি থেকে বিশুদ্ধ পানি সংগ্রহ করা হচ্ছে। এই প্রক্রিয়ায় তাপ প্রয়োগ করা হচ্ছে।'],
                ['chapter_id' => $chemCh1->id, 'created_by' => $admin->id]
            );
            if ($chemCq->parts()->count() === 0) {
                $chemParts = [
                    ['label' => 'ক', 'question' => 'পাতন কাকে বলে?', 'marks' => 1],
                    ['label' => 'খ', 'question' => 'উদ্দীপকে কোন প্রক্রিয়ার কথা বলা হয়েছে—ব্যাখ্যা কর।', 'marks' => 2],
                    ['label' => 'গ', 'question' => 'প্রক্রিয়াটির প্রয়োজনীয় শর্তসমূহ আলোচনা কর।', 'marks' => 3],
                    ['label' => 'ঘ', 'question' => 'উদ্দীপকের প্রক্রিয়াটি দৈনন্দিন জীবনে কীভাবে কাজে লাগে—বিশ্লেষণ কর।', 'marks' => 4],
                ];
                foreach ($chemParts as $i => $p) {
                    $chemCq->parts()->create($p + ['position' => $i]);
                }
            }

            // ---------- Sample Paper for demo user ----------
            $paper = Paper::firstOrCreate(
                ['user_id' => $user->id, 'title' => 'Sample অর্ধবার্ষিক পরীক্ষা'],
                [
                    'school_name' => 'মডেল স্কুল অ্যান্ড কলেজ',
                    'exam_name'   => 'অর্ধবার্ষিক পরীক্ষা ২০২৬',
                    'exam_time'   => '৩ ঘণ্টা',
                    'full_marks'  => 30,
                ]
            );

            if ($paper->mcqs()->count() === 0) {
                $mcqIds = Mcq::take(5)->pluck('id')->all();
                $attach = [];
                foreach ($mcqIds as $i => $id) {
                    $attach[$id] = ['position' => $i];
                }
                $paper->mcqs()->sync($attach);
            }
            if ($paper->shortQuestions()->count() === 0) {
                $shortIds = ShortQuestion::take(3)->pluck('id')->all();
                $attach = [];
                foreach ($shortIds as $i => $id) {
                    $attach[$id] = ['position' => $i];
                }
                $paper->shortQuestions()->sync($attach);
            }
            if ($paper->cqs()->count() === 0) {
                $paper->cqs()->sync([$banglaCq->id => ['position' => 0], $mathCq->id => ['position' => 1]]);
            }
        });
    }
}
