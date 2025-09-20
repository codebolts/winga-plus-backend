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
       Schema::create('business_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
    $table->string('business_name');
    $table->text('description')->nullable();
    $table->string('logo')->nullable();
    $table->string('website')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
