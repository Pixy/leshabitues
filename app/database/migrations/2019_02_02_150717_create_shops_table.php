<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->integer('distance');
            $table->string('name');
            $table->string('chain');
            $table->string('address');
            $table->integer('zipCode');
            $table->integer('category_id');
            $table->string('category_name');
            $table->string('logo');
            $table->string('cover');
            $table->float('maxoffer');
            $table->string('currency');

            $table->string('hash')->nullable()->comment('Hash is used to compare data and see if update is needed');

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
        Schema::dropIfExists('shop');
    }
}
