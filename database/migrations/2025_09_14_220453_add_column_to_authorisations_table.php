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
        Schema::table('authorisations', function (Blueprint $table) {
            $table->foreignId('authorised_by')->nullable()->constrained('users')->onDelete('set null'); // links to users
            $table->text('reviewercomment')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authorisations', function (Blueprint $table) {
            //
        });
    }
};
