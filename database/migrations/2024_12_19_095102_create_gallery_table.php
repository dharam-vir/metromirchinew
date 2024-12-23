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
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();           
            $table->unsignedBigInteger('listing_id');  // Correct data type
            $table->foreign('listing_id')->references('id')->on('listing')->onDelete('cascade');
            $table->string('image_path');  // Path to the image
            $table->string('title')->nullable();  // Title for the image
            $table->text('description')->nullable();
            $table->timestamps();  // Created at and updated at timestamps
        });       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
