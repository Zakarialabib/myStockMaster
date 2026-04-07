<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        throw_if(blank($tableNames), Exception::class, 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');

        throw_if($teams && blank($columnNames['team_foreign_key'] ?? null), Exception::class, 'Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');

        Schema::create($tableNames['permissions'], function (Blueprint $blueprint): void {
            $blueprint->bigIncrements('id'); // permission id
            $blueprint->string('name');       // For MySQL 8.0 use string('name', 125);
            $blueprint->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $blueprint->timestamps();

            $blueprint->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $blueprint) use ($teams, $columnNames): void {
            $blueprint->bigIncrements('id'); // role id

            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $blueprint->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $blueprint->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }

            $blueprint->string('name');       // For MySQL 8.0 use string('name', 125);
            $blueprint->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $blueprint->timestamps();

            if ($teams || config('permission.testing')) {
                $blueprint->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $blueprint->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $blueprint) use ($tableNames, $columnNames, $pivotPermission, $teams): void {
            $blueprint->unsignedBigInteger($pivotPermission);

            $blueprint->string('model_type');
            $blueprint->uuid($columnNames['model_morph_key']);
            $blueprint->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $blueprint->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            if ($teams) {
                $blueprint->unsignedBigInteger($columnNames['team_foreign_key']);
                $blueprint->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $blueprint->primary(
                    [$columnNames['team_foreign_key'], $pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'
                );
            } else {
                $blueprint->primary(
                    [$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $blueprint) use ($tableNames, $columnNames, $pivotRole, $teams): void {
            $blueprint->unsignedBigInteger($pivotRole);

            $blueprint->string('model_type');
            $blueprint->uuid($columnNames['model_morph_key']);
            $blueprint->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $blueprint->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $blueprint->unsignedBigInteger($columnNames['team_foreign_key']);
                $blueprint->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $blueprint->primary(
                    [$columnNames['team_foreign_key'], $pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'
                );
            } else {
                $blueprint->primary(
                    [$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $blueprint) use ($tableNames, $pivotRole, $pivotPermission): void {
            $blueprint->unsignedBigInteger($pivotPermission);
            $blueprint->unsignedBigInteger($pivotRole);

            $blueprint->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $blueprint->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $blueprint->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        resolve(\Illuminate\Contracts\Cache\Factory::class)
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        throw_if(blank($tableNames), Exception::class, 'Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};
