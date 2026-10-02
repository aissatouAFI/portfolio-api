<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Une seule ligne active à la fois = le PDF de CV téléchargeable actuellement en ligne.
     */
    public function up(): void
    {
        Schema::create('cv_pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('fichier'); // chemin storage
            $table->string('nom_original');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_pdfs');
    }
};
