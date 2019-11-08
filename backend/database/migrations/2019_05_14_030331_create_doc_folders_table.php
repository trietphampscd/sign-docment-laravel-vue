<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_folders', function (Blueprint $table) {
           $table->BigIncrements('id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('doc_folder_id', 64)->nullable();
            $table->string('doc_folder_name', 128);
            $table->bigInteger('user_id')->unsigned();
            $table->integer('priority')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
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
        Schema::dropIfExists('doc_folders');
    }
}
