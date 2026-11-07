<?php

use Database\Seeders\MetadataTypeSeeder;
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
        Schema::create('metadata_types', function (Blueprint $table) {
            $table->id();
            $table->text('display');
            $table->text('value');
            $table->timestamps();
        });

        (new MetadataTypeSeeder)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metdata_types');
    }
};
