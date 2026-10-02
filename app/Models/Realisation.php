<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Realisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'image',
        'lien_demo',
        'lien_github',
        'technologies',
        'date_realisation',
        'en_avant',
        'ordre',
    ];

    protected $casts = [
        'technologies' => 'array',
        'en_avant' => 'boolean',
        'date_realisation' => 'date',
    ];

    // Inclut image_url dans les réponses JSON (le site public l'utilise pour afficher l'image).
    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::creating(function (Realisation $realisation) {
            if (empty($realisation->slug)) {
                $realisation->slug = Str::slug($realisation->titre) . '-' . Str::random(5);
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
