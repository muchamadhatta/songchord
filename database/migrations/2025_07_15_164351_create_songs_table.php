<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('original_key', 3)->nullable(); // C, C#, Dm, …
            $table->unsignedSmallInteger('bpm')->nullable();
            $table->string('time_signature', 8)->nullable(); // 4/4, 3/4 …
            $table->tinyInteger('capo')->nullable(); // fret #
            $table->string('youtube_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
