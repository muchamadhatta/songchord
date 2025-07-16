<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_version_id')->constrained()->cascadeOnDelete();
            $table->string('section_type', 32);    // VERSE, CHORUS, BRIDGE …
            $table->unsignedInteger('section_order'); // 1,2,3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
