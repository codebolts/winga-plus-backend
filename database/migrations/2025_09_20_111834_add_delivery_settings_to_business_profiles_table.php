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
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->boolean('free_delivery')->default(false);
            $table->decimal('delivery_cost', 10, 2)->nullable();
            $table->text('delivery_locations')->nullable(); // JSON string of locations
            $table->boolean('payment_on_delivery')->default(true);
            $table->boolean('payment_before_delivery')->default(false);
            $table->text('business_address')->nullable();
            $table->string('business_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'free_delivery',
                'delivery_cost',
                'delivery_locations',
                'payment_on_delivery',
                'payment_before_delivery',
                'business_address',
                'business_phone'
            ]);
        });
    }
};
