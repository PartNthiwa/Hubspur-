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
        Schema::create('shareholder_incentive', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('shareholder_id');
            $table->unsignedBigInteger('incentive_id');
            $table->timestamps();

            $table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
            $table->foreign('incentive_id')->references('id')->on('incentives')->onDelete('cascade');
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareholder_incentive');
    }
};
