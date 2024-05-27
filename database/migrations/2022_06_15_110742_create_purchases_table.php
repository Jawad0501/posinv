<?php

use App\Models\Purchase;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('reference_no')->nullable();
            $table->decimal('total_amount');
            $table->decimal('shipping_charge')->default(0);
            $table->decimal('discount_amount');
            $table->decimal('paid_amount');
            $table->boolean('status');
            $table->date('date');
            $table->enum('payment_type', [Purchase::PAYMENT_TYPE_CASH, Purchase::PAYMENT_TYPE_BANK, Purchase::PAYMENT_TYPE_DUE]);
            $table->text('details')->nullable();
            $table->boolean('settled_from_advance')->default(false);
            $table->decimal('change_amount')->nullable();
            $table->boolean('change_returned')->default(false);
            $table->decimal('due_amount')->nullable();
            $table->decimal('previous_due')->nullable();
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
        Schema::dropIfExists('purchases');
    }
};
