<?php

use App\Models\BankTransaction;
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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained('banks')->cascadeOnDelete();
            $table->string('withdraw_deposite_id');
            $table->decimal('amount');
            $table->enum('type', [BankTransaction::TYPE_CREADIT, BankTransaction::TYPE_DEBIT]);
            $table->text('decsription')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('bank_transactions');
    }
};
