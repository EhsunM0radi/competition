<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('judge_id');
            $table->unsignedBigInteger('test_id');
            $table->foreign('judge_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            $table->unsignedBigInteger('score');
            $table->enum('type', ['group', 'individual']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
