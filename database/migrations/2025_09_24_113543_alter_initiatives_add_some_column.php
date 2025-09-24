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
        Schema::table('initiatives', function (Blueprint $table) {
            $table->integer('supervisorrating')->nullable()->after('rating');
            $table->text('supervisorcomment')->nullable()->after('comment');
            $table->integer('reviewerrating')->nullable()->after('supervisorcomment');
            $table->text('reviewercomment')->nullable()->after('reviewerrating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropColumn(['supervisorrating', 'supervisorcomment', 'reviewerrating', 'reviewercomment']);
        });
    }
};
