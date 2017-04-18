<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('famousinteractiveTranslators_content_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->index('fit_content_trans_content_id');
            $table->longText('value')->nullable();
            $table->string('lang');
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
        Schema::dropIfExists('famousinteractiveTranslators_content_translation');
    }
}
