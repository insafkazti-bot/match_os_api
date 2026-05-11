<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('score_a')->nullable()->change();
            $table->integer('score_b')->nullable()->change();
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE matches MODIFY status ENUM('planifie', 'en_cours', 'termine') NULL");
        } else {
            Schema::table('matches', function (Blueprint $table) {
                $table->string('status')->nullable()->change();
            });
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE match_tasks MODIFY status ENUM('a_faire', 'en_cours', 'termine') NULL");
        } else {
            Schema::table('match_tasks', function (Blueprint $table) {
                $table->string('status')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('score_a')->default(0)->nullable(false)->change();
            $table->integer('score_b')->default(0)->nullable(false)->change();
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE matches MODIFY status ENUM('planifie', 'en_cours', 'termine') NOT NULL DEFAULT 'planifie'");
        } else {
            Schema::table('matches', function (Blueprint $table) {
                $table->string('status')->default('planifie')->nullable(false)->change();
            });
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE match_tasks MODIFY status ENUM('a_faire', 'en_cours', 'termine') NOT NULL DEFAULT 'a_faire'");
        } else {
            Schema::table('match_tasks', function (Blueprint $table) {
                $table->string('status')->default('a_faire')->nullable(false)->change();
            });
        }
    }
};
