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
        Schema::create('article_salon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('availability_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();

            // Champs pour le contenu spécifique au salon
            $table->text('content_salon')->nullable();
            $table->json('gallery_salon')->nullable();
            $table->json('videos_salon')->nullable();

            // Champs pour le planning
            $table->boolean('is_scheduled')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('schedule_content')->nullable();

            // Autres métadonnées spécifiques au salon
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // Clés étrangères
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('set null');

            // Unicité d'un article dans un salon
            $table->unique(['article_id', 'salon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_salon');
    }
};
