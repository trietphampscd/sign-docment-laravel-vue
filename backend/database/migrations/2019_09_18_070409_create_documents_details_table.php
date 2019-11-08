<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_details', function (Blueprint $table) {
            $table->string('document_id')->primary();
            $table->bigInteger('creator_id')->unsigned();
            $table->string('name');
            $table->integer('expiration_days')->nullable();
            $table->string('action', 16)->nullable();
            $table->string('status', 16);
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents_details');
    }
}
