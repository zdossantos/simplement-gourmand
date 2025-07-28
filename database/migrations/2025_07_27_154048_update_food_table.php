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
        Schema::table('food', function (Blueprint $table) {
            $table->string('unit')->nullable()->change();
            $table->unsignedBigInteger('openfoodfacts_id')->change();
            $table->renameColumn('image_url', 'image');
        });
        Schema::rename('food', 'fridge_food');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('fridge_food', 'food');
        Schema::table('food', function (Blueprint $table) {
            $table->string('unit')->nullable(false)->change();
            $table->string('openfoodfacts_id')->change();
            $table->renameColumn('image', 'image_url');
        });
    }
};
