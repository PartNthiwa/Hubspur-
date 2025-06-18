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
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->text('description')->nullable();
            $table->integer('units');
            $table->integer('available_units')->nullable(); // optional
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_value', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('icon_url')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
