<?php

declare(strict_types=1);

use App\Enums\CapabilityProfile;
use App\Enums\ConnectionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('connection_type', array_column(ConnectionType::cases(), 'value'));
            $table->enum('capability_profile', array_column(CapabilityProfile::cases(), 'value'))
                ->default(CapabilityProfile::DEFAULT->value);
            $table->unsignedInteger('char_per_line')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};