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
        Schema::create('leads', function (Blueprint $table) {
            $table->bigIncrements('id');  // Primary key
            $table->unsignedBigInteger('listing_id')->nullable();
            $table->foreign('listing_id')->references('id')->on('listing')->onDelete('cascade'); // Corrected to 'listings' table name
        
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('sub_category')->onDelete('cascade'); // Corrected to 'sub_categories' table name
        
            $table->text('description');
            $table->tinyInteger('status')->default(0);
        
            $table->unsignedBigInteger('user_assigned_id');
            $table->foreign('user_assigned_id')->references('id')->on('users')->onDelete('cascade');
        
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        
            $table->unsignedBigInteger('user_created_id');
            $table->foreign('user_created_id')->references('id')->on('admins')->onDelete('cascade');
        
            $table->dateTime('contact_date'); // Ensures proper data type for date and time
            $table->timestamps(); // Includes created_at and updated_at columns
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
