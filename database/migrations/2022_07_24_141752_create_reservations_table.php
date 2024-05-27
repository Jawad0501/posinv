<?php

use App\Enum\ReservationStatus;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('expected_date');
            $table->time('expected_time');
            $table->integer('total_person');
            $table->enum('status', [ReservationStatus::HOLD->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRM->value, ReservationStatus::CANCEL->value])->default(ReservationStatus::HOLD->value);
            $table->string('invoice')->nullable();
            $table->string('occasion')->nullable();
            $table->text('special_request')->nullable();
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
        Schema::dropIfExists('reservations');
    }
};
