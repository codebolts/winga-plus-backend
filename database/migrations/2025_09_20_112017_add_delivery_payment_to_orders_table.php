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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->text('delivery_address')->nullable();
            $table->string('delivery_location')->nullable();
            $table->enum('payment_method', ['on_delivery', 'before_delivery'])->default('on_delivery');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->text('special_instructions')->nullable();
            $table->timestamp('delivered_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_cost',
                'delivery_address',
                'delivery_location',
                'payment_method',
                'payment_status',
                'special_instructions',
                'delivered_at'
            ]);
        });
    }
};
