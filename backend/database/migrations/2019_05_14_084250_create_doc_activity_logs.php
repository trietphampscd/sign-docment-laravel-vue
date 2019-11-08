<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocActivityLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('activity_type', 32);
            $table->bigInteger('doc_id')->unsigned();
            $table->bigInteger('actor_id')->unsigned();
            $table->datetime('activity_date');
            $table->text('description')->nullable();

            $table->foreign('actor_id')
                ->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doc_activity_logs');
    }
}
