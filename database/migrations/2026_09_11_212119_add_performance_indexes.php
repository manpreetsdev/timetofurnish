<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('products', 'category_id');
        $this->addTextIndexIfMissing('products', 'slug', 191);
        $this->addIndexIfMissing('products', 'user_id');
        $this->addIndexIfMissing('products', 'brand_id');
        $this->addCompositeIndexIfMissing('products', ['published', 'approved', 'auction_product'], 'products_published_approved_auction_index');

        $this->addIndexIfMissing('categories', 'parent_id');

        $this->addIndexIfMissing('carts', 'user_id');
        $this->addIndexIfMissing('carts', 'owner_id');
        $this->addIndexIfMissing('carts', 'temp_user_id');
        $this->addIndexIfMissing('carts', 'product_id');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('products', 'category_id');
        $this->dropIndexIfExists('products', 'slug');
        $this->dropIndexIfExists('products', 'user_id');
        $this->dropIndexIfExists('products', 'brand_id');
        $this->dropIndexIfExists('products', null, 'products_published_approved_auction_index');

        $this->dropIndexIfExists('categories', 'parent_id');

        $this->dropIndexIfExists('carts', 'user_id');
        $this->dropIndexIfExists('carts', 'owner_id');
        $this->dropIndexIfExists('carts', 'temp_user_id');
        $this->dropIndexIfExists('carts', 'product_id');
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

    private function addCompositeIndexIfMissing(string $table, array $columns, string $indexName): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                return;
            }
        }

        if ($this->indexExistsByName($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
            $blueprint->index($columns, $indexName);
        });
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

    private function dropIndexIfExists(string $table, ?string $column = null, ?string $indexName = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $name = $indexName ?? ($table . '_' . $column . '_index');

        if (!$this->indexExistsByName($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($name) {
            $blueprint->dropIndex($name);
        });
    }
};
