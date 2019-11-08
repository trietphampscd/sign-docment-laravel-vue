<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('address', 128)->nullable();
            $table->string('address2', 128)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('zip_code', 16)->nullable();
            $table->string('phone_number', 16)->nullable();
            $table->string('account_type', 16)->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->string('company_name', 128)->nullable();
            $table->bigInteger('company_size_id')->unsigned()->nullable();
            $table->bigInteger('industry_id')->unsigned()->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('plan_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('country_id')
                ->references('id')->on('countries');
            $table->foreign('state_id')
                ->references('id')->on('states');
            $table->foreign('company_size_id')
                ->references('id')->on('company_sizes');
            $table->foreign('industry_id')
                ->references('id')->on('industries');
            $table->foreign('department_id')
                ->references('id')->on('departments');
            $table->foreign('plan_id')
                ->references('id')->on('plans');
            $table->foreign('language_id')
                ->references('id')->on('languages');
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
        Schema::dropIfExists('clients');
    }
}
