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
    Schema::table('user_learning_areas', function (Blueprint $table) {
        $table->enum('type', ['self', 'assessor'])->default('self')->after('user_id');
    });
}

public function down()
{
    Schema::table('user_learning_areas', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}

};
