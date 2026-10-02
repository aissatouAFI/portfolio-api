<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_competences', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ex: "Laravel"
            $table->enum('categorie', ['backend', 'frontend', 'outils', 'autre'])->default('autre');
            $table->unsignedTinyInteger('niveau')->nullable(); // 0-100, optionnel
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_competences');
    }
};
