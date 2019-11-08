<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletesToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
          $table->string('initial', 5)->after('signature_type')->nullable();
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
      Schema::table('signatures', function (Blueprint $table) {
        $table->dropColumn('initial');
        $table->dropColumn('font_size');
        $table->dropColumn('language');

        $table->dropSoftDeletes();
      });
    }
}
