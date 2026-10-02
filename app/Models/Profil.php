<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profil extends Model
{
    protected $table = 'profil';

    protected $fillable = [
        'photo',
        'titre_accroche',
        'sous_titre',
        'a_propos',
    ];

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }

        return asset('storage/' . $this->photo);
    }
}
