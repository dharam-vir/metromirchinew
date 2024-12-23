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
        Schema::create('business_hours', function (Blueprint $table) {
            $table->engine = 'InnoDB';  // Ensure InnoDB is used
            $table->id();
            $table->unsignedBigInteger('listing_id');  // Correct data type
            $table->foreign('listing_id')->references('id')->on('listing')->onDelete('cascade');
            $table->string('day');  // The day of the week (e.g., 'Monday', 'Tuesday', etc.)
            $table->time('opening_time');  // Opening time for that day
            $table->time('closing_time');  // Closing time for that day
            $table->boolean('is_closed')->default(false);  // Optionally indicate whether the business is closed for the day
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
