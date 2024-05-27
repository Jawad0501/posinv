<?php

use App\Enum\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount');
            $table->enum('status', [PaymentStatus::PENDING->value, PaymentStatus::SUCCESS->value, PaymentStatus::CANCEL->value])->default(PaymentStatus::PENDING->value);
            $table->string('btc_wallet')->nullable();
            $table->string('trx')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
