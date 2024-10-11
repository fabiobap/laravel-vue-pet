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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->text('symptoms');
            $table->datetime('appointment_date');
            $table->datetime('appointment_time');
            $table->timestamps();

            $table->index('appointment_date');
            $table->fullText('symptoms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
