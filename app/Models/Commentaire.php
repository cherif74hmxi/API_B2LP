<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    /** @use HasFactory<\Database\Factories\CommentaireFactory> */
    use HasFactory;
    protected $fillable = [
        'COM_DATE',
        'COM_CONTENU',
        'billet_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
