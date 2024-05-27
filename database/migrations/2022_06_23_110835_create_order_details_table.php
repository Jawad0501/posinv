<?php

use App\Enum\OrderDetailsStatus;
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->integer('processing_time')->nullable()->comment('Count in minutes');
            $table->enum('status', [OrderDetailsStatus::PENDING->value, OrderDetailsStatus::COOKING->value, OrderDetailsStatus::READY->value, OrderDetailsStatus::SERVED->value, OrderDetailsStatus::CANCEL->value])->default(OrderDetailsStatus::PENDING->value);
            $table->decimal('price');
            $table->integer('quantity');
            $table->decimal('vat')->nullable();
            $table->decimal('total_price');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('order_details');
    }
};
