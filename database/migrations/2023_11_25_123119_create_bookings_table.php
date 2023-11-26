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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->foreignUuid('user_id')->index();
            $table->foreignId('spot_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('plate_number'); // We can use a foreign key to a table of vehicle to have more details about vehicles
            $table->string('total_price');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
