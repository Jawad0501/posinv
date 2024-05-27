<?php

use App\Enum\OrderDeliveryType;
use App\Enum\OrderStatus;
use App\Enum\OrderType;
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

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->string('invoice')->unique()->nullable();
            $table->string('token_no')->nullable();
            $table->enum('status', [OrderStatus::DUE->value, OrderStatus::PAID->value])->default(OrderStatus::DUE->value);
            $table->enum('type', [OrderType::DINEIN->value, OrderType::TAKEWAY->value, OrderType::DELIVERY->value, OrderType::ONLINE->value])->nullable();

            $table->string('order_by');
            $table->boolean('settled_from_wallet')->default(false);

            $table->decimal('discount')->nullable();
            $table->decimal('rewards_amount')->default(0);
            $table->decimal('service_charge');
            $table->decimal('delivery_charge')->default(0);
            $table->decimal('grand_total')->nullable();
            $table->decimal('customer_previous_due')->nullable();

            $table->enum('delivery_type', [OrderDeliveryType::PICKUP->value, OrderDeliveryType::DELIVERY->value])->nullable();

            $table->json('address')->nullable();
            $table->text('note')->nullable();
            $table->integer('rewards')->default(0);
            $table->boolean('review_done')->default(false);
            $table->timestamp('seen_time')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('served_time')->nullable();
            $table->timestamp('delivered_time')->nullable();

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
        Schema::dropIfExists('order_foods');
    }
};
