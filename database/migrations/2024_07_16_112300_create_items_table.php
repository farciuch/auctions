<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('item_type_id')->constrained('item_types');
            $table->foreignId('condition_id')->constrained('conditions');
            $table->foreignId('category_id')->constrained('sub_sub_categories');
            $table->string('title');
            $table->text('description');
            $table->decimal('starting_price', 8, 2)->nullable();
            $table->decimal('current_price', 8, 2)->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->default(1)->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
