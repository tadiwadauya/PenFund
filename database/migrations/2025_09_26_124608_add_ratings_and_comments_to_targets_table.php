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
            $table->integer('self_rating')->nullable()->after('target_name');
            $table->text('self_comment')->nullable()->after('self_rating');
            $table->integer('assessor_rating')->nullable()->after('self_comment');
            $table->text('assessor_comment')->nullable()->after('assessor_rating');
            $table->integer('reviewer_rating')->nullable()->after('assessor_comment');
            $table->text('reviewer_comment')->nullable()->after('reviewer_rating');
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
            $table->dropColumn([
                'self_rating',
                'self_comment',
                'assessor_rating',
                'assessor_comment',
                'reviewer_rating',
                'reviewer_comment',
            ]);
        });
    }
};
