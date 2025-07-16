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
       Schema::create('phase_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shareholder_id');
            $table->unsignedBigInteger('phase_id');
             $table->date('phase_end_date');
            $table->integer('days_left');
            $table->timestamps();

            $table->unique(['shareholder_id', 'phase_id', 'days_left'], 'unique_phase_notice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phase_notifications');
    }
};
