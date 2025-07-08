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
       Schema::create('membership_type_shareholder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shareholder_id');
            $table->unsignedBigInteger('membership_type_id');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
            $table->foreign('membership_type_id')->references('id')->on('membership_types')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_type_shareholder');
    }
};
