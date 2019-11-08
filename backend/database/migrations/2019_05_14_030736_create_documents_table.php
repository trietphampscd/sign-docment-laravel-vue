<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_id', 250);
            $table->string('document_name', 250);
            $table->string('document_file', 250);
            $table->bigInteger('creator_id')->unsigned();
            $table->bigInteger('doc_folder_id')->unsigned()->nullable();
            $table->integer('expiration_days')->unsigned()->nullable();
            $table->string('action', 250)->nullable();
            $table->string('status', 250);
            $table->text('comment')->nullable();
            $table->bigInteger('first_request_id')->nullable();
            $table->string('password')->nullable();
            $table->string('signed_file', 250)->nullable();
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')->on('users');
            // $table->foreign('doc_folder_id')
            //     ->references('id')->on('doc_folders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
