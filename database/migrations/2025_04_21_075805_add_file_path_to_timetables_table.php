<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('timetables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('class_id');
        $table->string('file_path');
        $table->timestamps();
    
        $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
    });
    
}

public function down(): void
{
    Schema::table('timetables', function (Blueprint $table) {
        $table->dropColumn('file_path');
    });
}

};
