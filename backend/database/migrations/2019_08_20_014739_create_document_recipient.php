<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentRecipient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_recipient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('document_id')->unsigned();
            $table->bigInteger('recipient_id')->unsigned();
            $table->string('password')->nullable();
            $table->string('action');
            $table->timestamps();

            $table->foreign('document_id')
                ->references('id')->on('documents')->onDelete('cascade');          
            $table->foreign('recipient_id')
                ->references('id')->on('recipients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_recipient');
    }
}
