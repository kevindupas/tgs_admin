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
        Schema::table('salons', function (Blueprint $table) {
            $table->boolean('show_presses')->default(false);
            $table->boolean('show_photos_invites')->default(false);
            $table->boolean('show_become_an_exhibitor')->default(false);
            $table->boolean('show_become_a_staff')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn('show_presses');
            $table->dropColumn('show_photos_invites');
            $table->dropColumn('show_become_an_exhibitor');
            $table->dropColumn('show_become_a_staff');
        });
    }
};
