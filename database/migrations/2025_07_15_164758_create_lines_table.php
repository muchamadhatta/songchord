<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('line_order');
            $table->text('lyrics_text');
            $table->timestamps();

            $table->index(['section_id', 'line_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lines');
    }
};
