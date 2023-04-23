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
        Schema::create('UserEloquent', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user');
            $table->string('color');
            $table->integer('pixels')->default(10);
            $table->integer('total_pixels')->default(0);
            $table->integer('level')->default(0);
            $table->date('last_update')->default(date('Y-m-d'));
            $table->string('mail')->unique();
            $table->string('password', 256);
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
        Schema::dropIfExists('UserEloquent');
    }
};
