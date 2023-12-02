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
        Schema::create('take_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exerise_id');
            $table->unsignedBigInteger('user_id');
            $table->text('take_exerise')->nullable();
            $table->integer('total_score')->default(0);
            $table->integer('total_question_success')->default(0);
            $table->integer('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('take_exercises');
    }
};
