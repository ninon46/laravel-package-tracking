<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->string('qr_code')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->string('recipient_phone');
            $table->text('delivery_address');
            $table->string('city');
            $table->string('postal_code');
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('tracking_number');
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
