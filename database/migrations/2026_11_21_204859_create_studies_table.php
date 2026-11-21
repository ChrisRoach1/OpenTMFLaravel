<?php

use Database\Seeders\MetadataTypeSeeder;
use Database\Seeders\StudySeeder;
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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->string('protocol_title');
            $table->date('start_date');
            $table->date('end_date')->nullable(true);
            $table->date('database_lock_date')->nullable(true);
            $table->timestamps();
        });

        (new StudySeeder)->run();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
