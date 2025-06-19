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
                $table->UnsignedInteger('shareholder_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->nullable();
                $table->enum('payment_method', ['cash','bank_transfer','mpesa','paypal'])->default('bank_transfer');
                $table->string('payment_channel')->nullable();
                $table->string('payment_reference')->nullable();
                $table->string('payment_receipt')->nullable();
                $table->enum('payment_status', ['pending','completed','failed'])->default('pending');
                $table->json('payment_metadata')->nullable();
                $table->decimal('payment_fee', 12, 2)->default(0.00);
                $table->timestamp('paid_at')->nullable();
                $table->date('contributed_at');
                $table->enum('status', ['pending','approved','rejected'])->default('pending');
                $table->UnsignedInteger('recorded_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->UnsignedInteger('approved_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('note')->nullable();
                $table->string('receipt_url')->nullable();
                $table->UnsignedInteger('created_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->UnsignedInteger('updated_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->UnsignedInteger('deleted_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->softDeletes();
                $table->timestamps();

                // Indexes
                $table->index('shareholder_id');
                $table->index('status');
                $table->index('payment_method');
                $table->index('payment_status');
                $table->index('contributed_at');
                $table->index('paid_at');
                $table->index('approved_at');
                $table->index('recorded_by');
                $table->index('approved_by');
                $table->index('payment_reference');
                $table->index(['shareholder_id','status','payment_method']);
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
