<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fixes: Unknown column 'match_tasks.id_match' when the physical table still uses
 * another name (match_id, game_match_id, matches_id, or a custom FK column).
 *
 * This migration runs after 2026_05_04_210000_* and uses information_schema to
 * discover FK source columns when hard-coded names do not match.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('match_tasks')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $pairs = [
            ['id_match', 'matches', ['game_match_id', 'match_id', 'matches_id', 'fk_match', 'matchId']],
            ['id_task', 'tasks', ['task_id', 'tasks_id', 'fk_task']],
            ['id_member', 'members', ['member_id', 'members_id', 'fk_member']],
        ];

        foreach ($pairs as [$expected, $refTable, $aliases]) {
            if (Schema::hasColumn('match_tasks', $expected)) {
                continue;
            }

            $from = $this->findFkColumnReferencing('match_tasks', $refTable)
                ?? $this->firstExistingColumn('match_tasks', $aliases);

            if ($from === null || $from === $expected) {
                continue;
            }

            $this->renameMysqlColumnPreservingType('match_tasks', $from, $expected, $refTable);
        }
    }

    public function down(): void
    {
        //
    }

    private function firstExistingColumn(string $table, array $names): ?string
    {
        foreach ($names as $name) {
            if (Schema::hasColumn($table, $name)) {
                return $name;
            }
        }

        return null;
    }

    private function findFkColumnReferencing(string $table, string $referencedTable): ?string
    {
        $db = Schema::getConnection()->getDatabaseName();

        $rows = DB::select(
            'SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
             AND REFERENCED_TABLE_NAME = ? AND REFERENCED_COLUMN_NAME = ?
             LIMIT 1',
            [$db, $table, $referencedTable, 'id']
        );

        if (empty($rows)) {
            return null;
        }

        $col = $rows[0]->COLUMN_NAME;

        return $col === 'id' ? null : $col;
    }

    private function mysqlAlterTypeFragment(string $table, string $column): ?string
    {
        $db = Schema::getConnection()->getDatabaseName();

        $row = DB::selectOne(
            'SELECT COLUMN_TYPE, IS_NULLABLE
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$db, $table, $column]
        );

        if ($row === null) {
            return null;
        }

        $t = $row->COLUMN_TYPE;
        $t .= $row->IS_NULLABLE === 'YES' ? ' NULL' : ' NOT NULL';

        return $t;
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

        $seen = [];
        foreach ($rows as $row) {
            $name = $row->CONSTRAINT_NAME;
            if (isset($seen[$name])) {
                continue;
            }
            $seen[$name] = true;
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$name}`");
        }
    }

    private function renameMysqlColumnPreservingType(string $table, string $from, string $to, string $refTable): void
    {
        if (! Schema::hasColumn($table, $from) || Schema::hasColumn($table, $to)) {
            return;
        }

        $type = $this->mysqlAlterTypeFragment($table, $from);
        if ($type === null) {
            return;
        }

        $this->dropMysqlForeignKeysUsingColumn($table, $from);

        DB::statement("ALTER TABLE `{$table}` CHANGE `{$from}` `{$to}` {$type}");

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
};
