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
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('event_logo')->nullable();
            $table->string('event_date')->nullable();
            $table->string('edition')->nullable();
            $table->string('edition_color')->nullable();
            $table->timestamp('countdown')->nullable();
            $table->text('ticket_link')->nullable();
            $table->text('second_ticket_link')->nullable();
            $table->text('message_ticket_link')->nullable();
            $table->string('website_link')->nullable();

            // Park Informations
            $table->string('park_address')->nullable();
            $table->string('park_link')->nullable();

            // Footer
            $table->string('footer_image')->nullable();
            $table->string('all_salon_image')->nullable();
            $table->string('salon_pop_culture_image')->nullable();
            $table->string('youtube_image')->nullable();

            // Découverte salon
            $table->string('title_discover')->nullable();
            $table->text('content_discover')->nullable(); // Je l'ai changé en text pour plus de contenu
            $table->string('youtube_discover')->nullable();

            // Informations
            $table->string('halls')->nullable();
            $table->string('scenes')->nullable();
            $table->string('invites')->nullable();
            $table->string('exposants')->nullable();
            $table->string('associations')->nullable();
            $table->string('animations')->nullable();

            $table->string('plan_pdf')->nullable();
            $table->string('planning_pdf')->nullable();

            // Social
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('tiktok_link')->nullable();

            // Press
            $table->string('presse_kit')->nullable();

            // About
            $table->text('about_us')->nullable();
            $table->text('practical_info')->nullable();

            $table->timestamps();
        });

        Schema::create('salon_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_user');
        Schema::dropIfExists('salons');
    }
};
