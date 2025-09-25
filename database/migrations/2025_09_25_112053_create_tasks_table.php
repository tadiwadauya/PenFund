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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Key task & objective
            $table->string('key_task');
            $table->text('objective')->nullable();

            // Individual task & target
            $table->string('task');
            $table->string('target')->nullable();

            // Ratings and comments
            $table->integer('self_rating')->nullable();
            $table->text('self_comment')->nullable();

            $table->integer('assessor_rating')->nullable();
            $table->text('assessor_comment')->nullable();

            $table->integer('reviewer_rating')->nullable();
            $table->text('reviewer_comment')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
