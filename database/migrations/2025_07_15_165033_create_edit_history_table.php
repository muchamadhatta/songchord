<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edit_history', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 32);      // SONG, LINE, CHORD_POS …
            $table->unsignedBigInteger('entity_id');
            $table->json('prev_json')->nullable();
            $table->json('new_json');
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edit_history');
    }
};
