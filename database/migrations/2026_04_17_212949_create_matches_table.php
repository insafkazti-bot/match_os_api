<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location')->nullable();
            $table->dateTime('match_date');
            $table->string('team_a_name');
            $table->string('team_b_name');
            $table->integer('score_a')->nullable();
            $table->integer('score_b')->nullable();
            $table->enum('status', ['planifie', 'en_cours', 'termine'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
