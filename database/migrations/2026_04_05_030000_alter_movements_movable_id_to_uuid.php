<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('movements')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('movements', function (Blueprint $blueprint): void {
            $blueprint->dropIndex('movements_movable_type_movable_id_index');
        });

        DB::statement('ALTER TABLE `movements` MODIFY `movable_id` CHAR(36) NOT NULL');

        Schema::table('movements', function (Blueprint $blueprint): void {
            $blueprint->index(['movable_type', 'movable_id']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('movements')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('movements', function (Blueprint $blueprint): void {
            $blueprint->dropIndex('movements_movable_type_movable_id_index');
        });

        DB::statement('ALTER TABLE `movements` MODIFY `movable_id` BIGINT UNSIGNED NOT NULL');

        Schema::table('movements', function (Blueprint $blueprint): void {
            $blueprint->index(['movable_type', 'movable_id']);
        });
    }
};
