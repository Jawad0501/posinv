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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->decimal('price');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('calorie')->nullable();
            $table->integer('processing_time')->nullable();
            $table->decimal('tax_vat')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('ingredient_id')->nullable();
            $table->string('token')->nullable();
            $table->string('sku')->nullable();
            $table->string('weight')->nullable();
            $table->string('gtin')->nullable();
            $table->boolean('online_item_visibility')->default(true);
            $table->boolean('sellable')->default(true);
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
        Schema::dropIfExists('foods');
    }
};
