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
        Schema::create('presses', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_presse_link')->nullable();
            $table->string('first_title');
            $table->longText('first_content');
            $table->string('second_title');
            $table->longText('second_content');
            $table->string('second_ticket')->nullable();
            $table->string('third_title');
            $table->longText('third_content');
            $table->string('third_ticket')->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presses');
    }
};
