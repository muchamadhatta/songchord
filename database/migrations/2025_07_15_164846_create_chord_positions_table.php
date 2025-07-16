<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chord_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('line_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('char_index');     // posisi sebelum huruf ke‑N (0‑based)
            $table->string('chord', 16);              // Am7, C/E, F#dim …
            $table->unsignedInteger('display_order'); // urutan jika lebih dari satu chord di index sama
            $table->timestamps();

            $table->index('line_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chord_positions');
    }
};
