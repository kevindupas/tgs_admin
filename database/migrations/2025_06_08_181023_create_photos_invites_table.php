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
        Schema::create('photos_invites', function (Blueprint $table) {
            $table->id();
            $table->string('mini_title');
            $table->string('title');
            $table->string('first_subtitle');
            $table->longText('first_content');
            $table->string('second_subtitle');
            $table->longText('second_content');
            $table->string('first_title_link');
            $table->string('first_link');
            $table->string('second_title_link');
            $table->string('second_link');
            $table->string('third_subtitle');
            $table->string('third_link')->nullable();
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
        Schema::dropIfExists('photos_invites');
    }
};
