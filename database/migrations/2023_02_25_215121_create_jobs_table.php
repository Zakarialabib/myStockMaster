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
        Schema::create('jobs', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('queue')->index();
            $blueprint->longText('payload');
            $blueprint->unsignedTinyInteger('attempts');
            $blueprint->unsignedInteger('reserved_at')->nullable();
            $blueprint->unsignedInteger('available_at');
            $blueprint->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $blueprint): void {
            $blueprint->string('id')->primary();
            $blueprint->string('name');
            $blueprint->integer('total_jobs');
            $blueprint->integer('pending_jobs');
            $blueprint->integer('failed_jobs');
            $blueprint->longText('failed_job_ids');
            $blueprint->mediumText('options')->nullable();
            $blueprint->integer('cancelled_at')->nullable();
            $blueprint->integer('created_at');
            $blueprint->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('uuid')->unique();
            $blueprint->text('connection');
            $blueprint->text('queue');
            $blueprint->longText('payload');
            $blueprint->longText('exception');
            $blueprint->timestamp('failed_at')->useCurrent();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
