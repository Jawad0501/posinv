<?php

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->enum('method', [PaymentMethod::CARD->value, PaymentMethod::CASH->value, PaymentMethod::STRIPE->value, PaymentMethod::PAYPAL->value, PaymentMethod::GIFTCARD->value]);
            $table->integer('rewards')->default(0);
            $table->decimal('reward_amount')->default(0);
            $table->decimal('give_amount');
            $table->decimal('change_amount');
            $table->decimal('grand_total');
            $table->boolean('change_returned')->default(true);
            $table->decimal('due_amount')->default(0);
            $table->string('btc_wallet')->nullable();
            $table->string('trx')->nullable();
            $table->enum('status', [PaymentStatus::PENDING->value, PaymentStatus::SUCCESS->value, PaymentStatus::CANCEL->value])->default(PaymentStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
