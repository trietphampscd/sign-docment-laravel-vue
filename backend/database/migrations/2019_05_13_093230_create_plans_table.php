<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('plan_type_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->boolean('is_yearly_plan');
            $table->datetime('started_at');
            $table->boolean('is_ended');
            $table->integer('balances_used');
            $table->float('coupon_discount');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('plan_type_id')
                ->references('id')->on('plan_types');
            $table->foreign('currency_id')
                ->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
