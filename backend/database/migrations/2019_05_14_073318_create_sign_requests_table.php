<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('document_id')->unsigned();
            $table->string('request_type', 16)->nullable();
            $table->bigInteger('signer_id')->unsigned();
            $table->datetime('signed_at')->nullable();
            $table->string('signed_file', 128)->nullable();
            $table->string('password')->nullable();
            $table->integer('expiration_days');
            $table->text('comment')->nullable();
            // $table->bigInteger('next_request_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('document_id')
                ->references('id')->on('documents');
            $table->foreign('signer_id')
                ->references('id')->on('users');
            // $table->foreign('next_request_id')
            //     ->references('id')->on('sign_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sign_requests');
    }
}
