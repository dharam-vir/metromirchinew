<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sub_cat_id');
            $table->unsignedBigInteger('listing_id');
            $table->enum('status', ['yes', 'no'])->default('no');
            $table->integer('last_activity')->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sub_cat_id')->references('id')->on('sub_category')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listing')->onDelete('cascade');
            // Optionally, you can add indexes on foreign keys for better performance
            $table->index('user_id');
            $table->index('sub_cat_id');
            $table->index('listing_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
