<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
          $table->boolean('default')->after('user_id')->nullable();
          $table->string('uploaded_url', 128)->nullable()->change();
          $table->string('initial_uploaded_url', 128)->after('uploaded_url')->nullable();
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
          $table->dropColumn('default');
          $table->string('uploaded_url', 128)->change();
          $table->dropColumn('initial_uploaded_url');
        });
    }
}
