<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align match_tasks columns with Eloquent (id_match, id_task, id_member).
     * Some databases use match_id / task_id / member_id or game_match_id instead.
     */
    public function up(): void
    {
        if (! Schema::hasTable('match_tasks')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // match_id from foreignIdFor(Matches::class) style migrations
            if (Schema::hasColumn('match_tasks', 'game_match_id') && ! Schema::hasColumn('match_tasks', 'id_match')) {
                $this->renameMysqlColumn('match_tasks', 'game_match_id', 'id_match', 'BIGINT UNSIGNED NOT NULL');
            }
            $this->renameMysqlColumnIfNeeded('match_tasks', 'match_id', 'id_match', 'BIGINT UNSIGNED NOT NULL');
            $this->renameMysqlColumnIfNeeded('match_tasks', 'task_id', 'id_task', 'BIGINT UNSIGNED NOT NULL');
            $this->renameMysqlColumnIfNeeded('match_tasks', 'member_id', 'id_member', 'BIGINT UNSIGNED NOT NULL');

            return;
        }

        if ($driver === 'sqlite') {
            foreach (
                [
                    ['game_match_id', 'id_match'],
                    ['match_id', 'id_match'],
                    ['task_id', 'id_task'],
                    ['member_id', 'id_member'],
                ] as [$from, $to]
            ) {
                if (Schema::hasColumn('match_tasks', $from) && ! Schema::hasColumn('match_tasks', $to)) {
                    Schema::table('match_tasks', function (Blueprint $table) use ($from, $to) {
                        $table->renameColumn($from, $to);
                    });
                }
            }
        }
    }

    public function down(): void
    {
        // Irreversible: restoring FK names / definitions may differ per environment.
    }

    private function renameMysqlColumnIfNeeded(string $table, string $from, string $to, string $sqlType): void
    {
        if (! Schema::hasColumn($table, $from) || Schema::hasColumn($table, $to)) {
            return;
        }

        $this->renameMysqlColumn($table, $from, $to, $sqlType);
    }

    private function renameMysqlColumn(string $table, string $from, string $to, string $sqlType): void
    {
        $this->dropMysqlForeignKeysUsingColumn($table, $from);

        DB::statement("ALTER TABLE `{$table}` CHANGE `{$from}` `{$to}` {$sqlType}");

        match ($to) {
            'id_match' => Schema::table($table, function (Blueprint $t) {
                $t->foreign('id_match')->references('id')->on('matches')->onDelete('cascade');
            }),
            'id_task' => Schema::table($table, function (Blueprint $t) {
                $t->foreign('id_task')->references('id')->on('tasks')->onDelete('cascade');
            }),
            'id_member' => Schema::table($table, function (Blueprint $t) {
                $t->foreign('id_member')->references('id')->on('members')->onDelete('cascade');
            }),
            default => null,
        };
    }

    private function dropMysqlForeignKeysUsingColumn(string $table, string $column): void
    {
        $db = Schema::getConnection()->getDatabaseName();

        $rows = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$db, $table, $column]
        );

        foreach ($rows as $row) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$row->CONSTRAINT_NAME}`");
        }
    }
};
