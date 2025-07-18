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
        Schema::create('leaders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
       
        $table->string('name');
        $table->string('position');
        $table->string('slug')->unique();

        $table->string('photo')->nullable();
        $table->text('bio')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('linkedin_url')->nullable();

        $table->string('quote')->nullable();
        $table->string('qualifications')->nullable();
        $table->string('experience')->nullable();

        $table->json('social_links')->nullable(); 
        $table->enum('status', ['active', 'inactive', 'retired', 'suspended'])->default('active'); 
        $table->date('start_date')->nullable();                 
        $table->date('end_date')->nullable();                  
        $table->integer('priority')->default(0);          
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leadership_members');
    }
};
