<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('template_id', 64);
            $table->string('template_name', 128);
            $table->string('template_file', 128);
            $table->bigInteger('creator_id')->unsigned();
            $table->bigInteger('first_signer_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')->on('clients');
            $table->foreign('first_signer_id')
                ->references('id')->on('template_signers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
