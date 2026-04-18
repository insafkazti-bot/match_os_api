<?php

// Définit l'espace de noms du fichier
namespace App\Models;

// Importe la classe Model de Laravel (classe parent)
use Illuminate\Database\Eloquent\Model;

// Importe le model MatchTask pour les relations
use App\Models\MatchTask;

class Member extends Model
{
    // Liste des colonnes qu'on peut remplir via le code
    // Protège contre les attaques "mass assignment"
    protected $fillable = [
        'first_name',       // Prénom du membre
        'last_name',        // Nom du membre
        'email',            // Email unique du membre
        'password',         // Mot de passe (hashé)
        'phone',            // Numéro de téléphone
        'profile_picture',  // Photo de profil
        'position',         // Position (attaquant, défenseur...)
    ];

    // Colonnes cachées dans les réponses JSON
    // Le mot de passe ne sera jamais affiché
    protected $hidden = [
        'password',
    ];

    /**
     * Relation 1-N avec MatchTask
     * Un membre peut avoir PLUSIEURS match_tasks
     * Exemple : Ahmed est assigné à 5 tâches de matchs différents
     */
    public function matchTasks()
    {
        // hasMany = "j'ai plusieurs"
        return $this->hasMany(MatchTask::class);
    }
}