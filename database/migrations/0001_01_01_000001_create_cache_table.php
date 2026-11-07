<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('cqs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
        $table->text('stem'); // Uddipok
        $table->string('source_image')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
    });

    Schema::create('cq_parts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cq_id')->constrained()->cascadeOnDelete();
        $table->string('label', 10); // ক, খ, গ, ঘ
        $table->text('question');
        $table->integer('marks');
        $table->integer('position')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
