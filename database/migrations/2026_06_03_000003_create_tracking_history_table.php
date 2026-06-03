<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('description');
            $table->string('location')->nullable();
            $table->timestamp('created_at');

            $table->index('package_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_histories');
    }
};
