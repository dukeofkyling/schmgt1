<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('performance_analytics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained()->onDelete('cascade');
        $table->foreignId('term_id')->constrained()->onDelete('cascade');
        $table->decimal('average_score', 5, 2);
        $table->integer('total_subjects');
        $table->string('performance_level'); // e.g., 'Excellent', 'Good', 'Needs Improvement'
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_analytics');
    }
};
