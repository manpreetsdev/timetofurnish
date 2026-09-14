<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('reviews', 'product_id');
        $this->addIndexIfMissing('reviews', 'user_id');

        $this->addIndexIfMissing('wishlists', 'user_id');
        $this->addIndexIfMissing('wishlists', 'product_id');

        $this->addIndexIfMissing('product_stocks', 'product_id');

        $this->addIndexIfMissing('orders', 'user_id');

        $this->addIndexIfMissing('blogs', 'status');
        $this->addTextIndexIfMissing('blogs', 'slug', 191);
    }

    public function down(): void
    {
        $this->dropIndexIfExists('reviews', 'product_id');
        $this->dropIndexIfExists('reviews', 'user_id');

        $this->dropIndexIfExists('wishlists', 'user_id');
        $this->dropIndexIfExists('wishlists', 'product_id');

        $this->dropIndexIfExists('product_stocks', 'product_id');

        $this->dropIndexIfExists('orders', 'user_id');

        $this->dropIndexIfExists('blogs', 'status');
        $this->dropIndexIfExists('blogs', 'slug');
    }

    private function addIndexIfMissing(string $table, string $column): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        if ($this->indexExists($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->index($column);
        });
    }

    private function addTextIndexIfMissing(string $table, string $column, int $length): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        if ($this->indexExists($table, $column)) {
            return;
        }

        $indexName = $table . '_' . $column . '_index';
        DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` (`{$column}`({$length}))");
    }

    private function indexExists(string $table, string $column): bool
    {
        $connection = Schema::getConnection()->getDatabaseName();
        $result = DB::select(
            "SELECT COUNT(1) as count FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
            [$connection, $table, $column]
        );

        return ($result[0]->count ?? 0) > 0;
    }

    private function indexExistsByName(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection()->getDatabaseName();
        $result = DB::select(
            "SELECT COUNT(1) as count FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?",
            [$connection, $table, $indexName]
        );

        return ($result[0]->count ?? 0) > 0;
    }

    private function dropIndexIfExists(string $table, string $column): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $name = $table . '_' . $column . '_index';

        if (!$this->indexExistsByName($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($name) {
            $blueprint->dropIndex($name);
        });
    }
};
