<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiletable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('famousinteractiveTranslators_file', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('url');
            $table->string('disk')->default('public');
            $table->string('container')->default('default');
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
        Schema::dropIfExists('famousinteractiveTranslators_file');
    }
}
