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
        Schema::create('incentive_shareholder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incentive_id')->constrained()->onDelete('cascade');
            $table->foreignId('shareholder_id')->constrained()->onDelete('cascade');
            $table->integer('units');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('incentive_shareholder');
    }
};
