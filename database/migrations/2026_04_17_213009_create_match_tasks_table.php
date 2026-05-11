<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_match')->constrained('matches')->onDelete('cascade');
            $table->foreignId('id_task')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('id_member')->constrained('members')->onDelete('cascade');
            $table->enum('status', ['a_faire', 'en_cours', 'termine'])->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_tasks');
    }
};
