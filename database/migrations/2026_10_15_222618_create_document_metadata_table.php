<?php

use App\Models\MetadataType;
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
        Schema::create('document_metadata', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable(false);
            $table->foreignIdFor(MetadataType::class);
            $table->boolean('is_required')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_metadata');
    }
};
