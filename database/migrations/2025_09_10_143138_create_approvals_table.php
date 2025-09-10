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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();  $table->foreignId('user_id')->constrained()->onDelete('cascade'); // employee
            $table->foreignId('period_id')->constrained()->onDelete('cascade'); // year/period
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // manager
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comment')->nullable(); // rejection reason
            $table->timestamps();
            $table->unique(['user_id', 'period_id']); // one approval per user per year
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
