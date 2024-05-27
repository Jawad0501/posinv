<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['User', 'Rider'])->default('User');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_anniversary')->nullable();
            $table->integer('gst_number')->nullable();
            $table->json('address_book')->nullable();
            $table->integer('rewards')->default(0);
            $table->integer('rewards_available')->default(0);
            $table->string('google_id')->nullable();
            $table->string('image')->nullable();
            $table->string('otp_code')->nullable();
            $table->enum('verify_field', ['email', 'phone'])->nullable();
            $table->string('customer_id')->unique();
            $table->decimal('discount', 8, 1)->default(0);
            $table->decimal('wallet', 8, 1)->default(0);
            $table->decimal('due', 8, 1)->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
