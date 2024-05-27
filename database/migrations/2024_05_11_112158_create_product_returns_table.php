<?php

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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderdetail_id');
            $table->enum('product_return_person', ['supplier', 'customer']);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('invoice_no');
            $table->string('return_invoice')->nullable();
            $table->date('return_date');
            $table->string('payment_method');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('grand_total')->nullable();
            $table->decimal('paid_amount')->nullable();
            $table->decimal('due_amount')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
