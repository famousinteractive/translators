<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('famousinteractiveTranslators_content', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique('fit_content_unique');
            $table->string('description')->nullable();
            $table->boolean('html')->default(0);
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
        Schema::dropIfExists('famousinteractiveTranslators_content');
    }
}
