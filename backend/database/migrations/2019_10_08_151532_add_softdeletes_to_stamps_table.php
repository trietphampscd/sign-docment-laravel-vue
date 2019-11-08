<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletesToStampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stamps', function (Blueprint $table) {
          $table->string('title', 5)->after('stamp_type')->nullable();
          $table->string('font_size')->after('font_face');
          $table->string('language')->after('font_size');

          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('stamps', function (Blueprint $table) {
        $table->dropColumn('title');
        $table->dropColumn('font_size');
        $table->dropColumn('language');

        $table->dropSoftDeletes();
      });
    }
}
