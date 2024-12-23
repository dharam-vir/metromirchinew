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
        Schema::create('sub_category', function (Blueprint $table) {
            $table->id();
            $table->string('subcat_code')->unique();  
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->string('name');
            $table->string('url');
            $table->decimal('price', 8, 2)->default('0.000'); 
            $table->integer('last_activity')->index();
            $table->enum('status', ['yes', 'no'])->default('no');
            $table->timestamps();
        
            // Explicit foreign key definition
            $table->foreign('category_id')->references('id')->on('category')->onDelete('set null');
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_category');
    }
};
