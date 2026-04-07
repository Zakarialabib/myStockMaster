<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $blueprint): void {
            $blueprint->bigIncrements('id');

            $blueprint->morphs('model');
            $blueprint->uuid('uuid')->nullable()->unique();
            $blueprint->string('collection_name');
            $blueprint->string('name');
            $blueprint->string('file_name');
            $blueprint->string('mime_type')->nullable();
            $blueprint->string('disk');
            $blueprint->string('conversions_disk')->nullable();
            $blueprint->unsignedBigInteger('size');
            $blueprint->json('manipulations');
            $blueprint->json('custom_properties');
            $blueprint->json('generated_conversions');
            $blueprint->json('responsive_images');
            $blueprint->unsignedInteger('order_column')->nullable()->index();

            $blueprint->nullableTimestamps();
        });
    }
};
