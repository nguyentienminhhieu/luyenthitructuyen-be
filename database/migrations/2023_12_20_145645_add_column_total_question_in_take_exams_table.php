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
        Schema::table('take_exams', function (Blueprint $table) {
            $table->integer('total_question')->default(0);
            $table->integer('times')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('take_exams', function (Blueprint $table) {
            $table->dropColumn('total_question');
            $table->dropColumn('times');
        });
    }
};
