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
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->default('Untitled');
            $table->text('content')->default('');
            $table->boolean('is_deleted')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });

        Schema::create('note_tag', function (Blueprint $table) {
            $table->uuid('note_id');
            $table->foreign('note_id')->references('id')->on('notes')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['note_id', 'tag_id']);
        });

        Schema::create('note_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('source_note_id');
            $table->uuid('target_note_id');
            $table->timestamps();

            $table->foreign('source_note_id')->references('id')->on('notes')->cascadeOnDelete();
            $table->foreign('target_note_id')->references('id')->on('notes')->cascadeOnDelete();
            $table->unique(['source_note_id', 'target_note_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_links');
        Schema::dropIfExists('note_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('notes');
    }
};
