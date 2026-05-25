<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Billet extends Model
{
    /** @use HasFactory<\Database\Factories\BilletFactory> */
    use HasFactory;

    protected $table = 'billets';

    protected $fillable = [
        'BIL_DATE',
        'BIL_TITRE',
        'BIL_CONTENU',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'BIL_DATE' => 'date',
        ];
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
