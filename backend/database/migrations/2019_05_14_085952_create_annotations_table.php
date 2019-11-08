<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('annotation_id', 64);
            $table->bigInteger('creator_id')->unsigned();
            $table->bigInteger('actor_id')->unsigned();
            $table->bigInteger('doc_id')->unsigned();
            $table->string('type', 16);
            $table->integer('page_num')->unsigned();
            $table->float('pos_x')->unsigned();
            $table->float('pos_y')->unsigned();
            $table->float('size_w')->unsigned();
            $table->float('size_h')->unsigned();
            $table->integer('z_order')->default(0);
            $table->float('alpha')->default(1);
            $table->string('value')->nullable();
            $table->text('text')->nullable();
            $table->string('font_family', 64)->nullable();
            $table->float('font_size')->nullable();
            $table->string('font_weight', 16)->nullable();
            $table->string('font_color', 8)->nullable();
            $table->string('image_url', 128)->nullable();
            $table->timestamps();

            // $table->foreign('creator_id')
            //     ->references('id')->on('clients');
            // $table->foreign('actor_id')
            //     ->references('id')->on('clients');
            // $table->foreign('doc_id')
            //     ->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annotations');
    }
}
