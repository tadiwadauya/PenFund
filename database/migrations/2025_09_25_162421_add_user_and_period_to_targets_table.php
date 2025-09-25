<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->unsignedBigInteger('period_id')->nullable()->after('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['period_id']);
            $table->dropColumn(['user_id', 'period_id']);
        });
    }
};
