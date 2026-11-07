<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Cq;
use App\Models\Mcq;
use App\Models\SchoolClass;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $class = SchoolClass::firstOrCreate(['name' => 'Class 9-10']);

        $bangla = Subject::firstOrCreate(
            ['class_id' => $class->id, 'name' => 'বাংলা'],
            ['cq_pattern' => [1, 2, 3, 4]]
        );
        $english = Subject::firstOrCreate(
            ['class_id' => $class->id, 'name' => 'English'],
            ['cq_pattern' => [1, 2, 3, 4]]
        );
        $math = Subject::firstOrCreate(
            ['class_id' => $class->id, 'name' => 'গণিত'],
            ['cq_pattern' => [2, 4, 4]]
        );
        $physics = Subject::firstOrCreate(
            ['class_id' => $class->id, 'name' => 'পদার্থবিজ্ঞান'],
            ['cq_pattern' => [1, 2, 3, 4]]
        );

        $banglaCh1 = Chapter::firstOrCreate(['subject_id' => $bangla->id, 'name' => 'গদ্য'], ['position' => 1]);
        $mathCh1 = Chapter::firstOrCreate(['subject_id' => $math->id, 'name' => 'সূচক ও লগারিদম'], ['position' => 1]);
        $physicsCh1 = Chapter::firstOrCreate(['subject_id' => $physics->id, 'name' => 'ভৌত রাশি ও পরিমাপ'], ['position' => 1]);

        // Sample MCQs
        Mcq::firstOrCreate(
            ['question' => 'বাংলা বর্ণমালায় স্বরবর্ণ কতটি?'],
            [
                'chapter_id' => $banglaCh1->id,
                'option_a' => '৭', 'option_b' => '৯', 'option_c' => '১১', 'option_d' => '১৩',
                'correct_answer' => 'c', 'marks' => 1, 'created_by' => $admin->id,
            ]
        );

        Mcq::firstOrCreate(
            ['question' => '2^3 = ?'],
            [
                'chapter_id' => $mathCh1->id,
                'option_a' => '6', 'option_b' => '8', 'option_c' => '9', 'option_d' => '12',
                'correct_answer' => 'b', 'marks' => 1, 'created_by' => $admin->id,
            ]
        );

        // Sample Shorts
        ShortQuestion::firstOrCreate(
            ['question' => 'সূচকের নিয়ম লিখ।'],
            ['chapter_id' => $mathCh1->id, 'marks' => 2, 'created_by' => $admin->id]
        );

        // Sample CQs — Math with pattern [2,4,4]
        $mathCq = Cq::firstOrCreate(
            ['stem' => 'একটি ত্রিভুজের তিন বাহুর দৈর্ঘ্য যথাক্রমে $a=3$, $b=4$, $c=5$।'],
            ['chapter_id' => $mathCh1->id, 'created_by' => $admin->id]
        );

        if ($mathCq->parts()->count() === 0) {
            $parts = [
                ['label' => 'ক', 'question' => 'ত্রিভুজটির পরিসীমা নির্ণয় কর।', 'marks' => 2],
                ['label' => 'খ', 'question' => 'ত্রিভুজটির ক্ষেত্রফল নির্ণয় কর।', 'marks' => 4],
                ['label' => 'গ', 'question' => 'ত্রিভুজটি সমকোণী কিনা যাচাই কর।', 'marks' => 4],
            ];
            foreach ($parts as $i => $p) {
                $mathCq->parts()->create($p + ['position' => $i]);
            }
        }

        // Sample CQ — Bangla with pattern [1,2,3,4]
        $banglaCq = Cq::firstOrCreate(
            ['stem' => 'উদ্দীপক: একটি ছোট গল্পের সারাংশ এখানে দেওয়া হলো।'],
            ['chapter_id' => $banglaCh1->id, 'created_by' => $admin->id]
        );

        if ($banglaCq->parts()->count() === 0) {
            $parts = [
                ['label' => 'ক', 'question' => 'লেখক কে?', 'marks' => 1],
                ['label' => 'খ', 'question' => 'মূলভাব ব্যাখ্যা কর।', 'marks' => 2],
                ['label' => 'গ', 'question' => 'বিশ্লেষণ কর।', 'marks' => 3],
                ['label' => 'ঘ', 'question' => 'মূল্যায়ন কর।', 'marks' => 4],
            ];
            foreach ($parts as $i => $p) {
                $banglaCq->parts()->create($p + ['position' => $i]);
            }
        }
    }
}
