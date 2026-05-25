<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    /** @use HasFactory<\Database\Factories\BilletFactory> */
    use HasFactory;
    protected $fillable = [
        'BIL_DATE',
        'BIL_TITRE',
        'BIL_CONTENU',
    ];

    protected $hidden = [
		'id',
		'created_at',
		'updated_at'
	];

    //Un billet a plusieurs commentaires.
    //Cette fonction sera utile pour afficher les commentaires d'un billet sélectionné.
    public function commentaires()
	{
		return $this->hasMany(Commentaire::class);
	}
}
