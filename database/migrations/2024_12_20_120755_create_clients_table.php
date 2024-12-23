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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();  // auto-incrementing primary key
            $table->string('name');  // client name
            $table->string('email')->unique();  // unique email
            $table->string('primary_number', 15)->nullable()->unique();  // unique phone number
            $table->string('secondary_number', 15)->nullable();  // added length for phone number
            $table->string('address')->nullable();  // nullable address
            $table->string('zipcode', 10)->nullable();  // added length for zipcode
            $table->string('city')->nullable();  // nullable city
            $table->timestamps();  // created_at, updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
