<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('poste');
            $table->string('entreprise');
            $table->string('lieu')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable(); // null = poste actuel
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_experiences');
    }
};
