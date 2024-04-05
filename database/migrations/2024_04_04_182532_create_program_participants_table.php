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
        Schema::create('program_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->timestamps();

            // Optional: Add foreign keys for better data integrity. 
            // Note: These lines require your database to support foreign key constraints on nullable columns.
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        //    $table->foreign('entity_id', 'entity_type')->references('id')->on('users')->onDelete('cascade');
            // Add an index to improve query performance.
            $table->index(['entity_type', 'entity_id',]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_participants');
    }
};
