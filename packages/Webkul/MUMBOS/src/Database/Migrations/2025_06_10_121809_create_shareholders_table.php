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
      Schema::create('shareholders', function (Blueprint $table) {
    $table->id();

    // Relationships
    $table->unsignedInteger('customer_id');
   
    // Basic shareholder identity
    $table->string('shareholder_number')->unique();     // Internal ID like SH0001
    $table->string('full_name')->nullable();            // Optional override from Customer
    $table->string('id_number')->nullable();            // National ID / Passport
    $table->string('kra_pin')->nullable();              // Tax PIN (if required in your region)
    $table->string('email')->nullable();                // Override (optional)
    $table->string('phone')->nullable();                // Override (optional)

    // Address & location
    $table->string('postal_address')->nullable();
    $table->string('physical_address')->nullable();
    $table->string('city')->nullable();
    $table->string('country')->nullable();

    // Shareholding details
    $table->string('share_class')->nullable();          // Common / Preferred
    $table->integer('share_units')->default(0);         // Total shares owned
    $table->decimal('capital_paid', 12, 2)->default(0); // Total amount contributed
    $table->date('joined_at')->nullable();              // Date became shareholder
    $table->boolean('is_active')->default(true);        // Active/inactive

    // Role or position
    $table->boolean('is_board_member')->default(false); // For special permissions
    $table->string('position')->nullable();             // Chair, Treasurer, etc.

    // KYC & documents
    $table->string('id_document_path')->nullable();     // Uploaded ID (optional)
    $table->string('passport_photo_path')->nullable();  // Profile photo
    $table->string('signature_path')->nullable();       // Optional

    // Tracking
    $table->timestamp('last_profile_update')->nullable();
     $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');         
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareholders');
    }
};
