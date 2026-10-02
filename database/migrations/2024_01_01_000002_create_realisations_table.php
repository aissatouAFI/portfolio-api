<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table "realisations" -> section "Mes réalisations"
     */
    public function up(): void
    {
        Schema::create('realisations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image')->nullable(); // chemin vers l'image (storage)
            $table->string('lien_demo')->nullable();
            $table->string('lien_github')->nullable();
            $table->json('technologies')->nullable(); // ["Laravel","React","MySQL"]
            $table->date('date_realisation')->nullable();
            $table->boolean('en_avant')->default(false); // mis en avant sur l'accueil
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisations');
    }
};
