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
        Schema::create('shareholder_share', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shareholder_id')->constrained()->onDelete('cascade');
            $table->foreignId('share_id')->constrained()->onDelete('cascade');
            $table->integer('units');
            $table->timestamps();

             $table->unique(['shareholder_id', 'share_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareholder_share');
    }
};
