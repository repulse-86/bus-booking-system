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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('bus_type')->unique();
            $table->string('departure_location');
            $table->string('destination_location');
            $table->time('time_available_start');
            $table->time('time_available_end');
            $table->decimal('price_per_ticket', 8, 2);
            $table->integer('seats');
            $table->integer('available_seats');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
