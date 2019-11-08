<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_name')->nullable()->after('id');
            $table->string('provider_id')->nullable()->after('provider_name');
            $table->string('name', 100)->nullable()->change();
            // $table->string('first_name', 45);
            // $table->string('last_name', 45);
            // $table->datetime('last_login_at')->nullable();
            $table->boolean('admin')->default(false);//0:user, 1:admin, 2:masuser
            $table->boolean('active')->default(false);
            $table->string('activation_token')->nullable();
            // $table->integer('status')->default(0);
            // $table->string('group', 16);
            // $table->string('ipaddress', 16);
            // $table->string('browser_name', 32);
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->dropColumn(
                'provider_name',
                'provider_id',
            //     'first_name',
            //     'last_name',
            //     'last_login_at',
                'admin',
                'active',
                'activation_token'
            //     'status',
            //     'group',
            //     'social_type',
            //     'ipaddress',
            //     'browser_name'
            );
            $table->dropSoftDeletes();
        });
    }
}
