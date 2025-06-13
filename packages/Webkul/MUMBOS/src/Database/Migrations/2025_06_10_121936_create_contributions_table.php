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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
              $table->foreignId('shareholder_id')
                ->constrained('shareholders')
                ->onDelete('cascade');
           
                 $table->decimal('amount', 12, 2);
                $table->string('type');                     // Cash, Transfer, M-Pesa, etc.
                $table->string('reference')->nullable();    // Bank/Mobile Txn Ref
                $table->string('note')->nullable();         // Remarks
                $table->enum('status', ['pending', 'approved', 'rejected'])
                    ->default('approved');
                $table->date('contributed_at');             // Actual payment date

                // Tracking
                $table->unsignedInteger('recorded_by')->nullable(); // Admin who recorded
                $table->foreign('recorded_by')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('set null');

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
