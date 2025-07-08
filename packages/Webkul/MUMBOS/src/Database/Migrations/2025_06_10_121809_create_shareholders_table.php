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
        $table->unsignedInteger('customer_id');
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');         
       $table->foreignId('membership_type_id')->nullable()->constrained('membership_types')->onDelete('set null');

        // Identity
        $table->string('shareholder_number')->unique();     
        $table->string('full_name')->nullable();            
        $table->string('id_number')->unique();           
        $table->string('kra_pin')->nullable();              
        $table->string('email')->unique();                
        $table->string('phone')->nullable();               

        // Address 
        $table->string('postal_address')->nullable();
        $table->string('physical_address')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->nullable();

        // Shareholding 
        $table->string('share_class')->nullable();          
        $table->integer('share_units')->default(0);        
        $table->decimal('capital_paid', 12, 2)->default(0);
        $table->date('joined_at')->nullable();              
        $table->boolean('is_active')->default(true);        

        // Role 
        $table->boolean('is_board_member')->default(false); 
        $table->string('position')->nullable();             

        // KYC
        $table->string('id_document_path')->nullable();     
        $table->string('passport_photo_path')->nullable();  
        $table->string('signature_path')->nullable();       

        // Tracking
        $table->timestamp('last_profile_update')->nullable();
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
