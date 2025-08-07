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
        Schema::create('location_edges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('from_location_id')->constrained('delivery_locations')->onDelete('cascade');
            $table->foreignUuid('to_location_id')->constrained('delivery_locations')->onDelete('cascade');
            $table->float('distance_km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_routes');
    }
};
