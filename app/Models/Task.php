<?php

// Définit l'espace de noms du fichier
namespace App\Models;

// Importe la classe Model de Laravel (classe parent)
use Illuminate\Database\Eloquent\Model;

// Importe les models nécessaires pour les relations
use App\Models\GameMatch;
use App\Models\MatchTask;

class Task extends Model
{
    // Liste des colonnes qu'on peut remplir via le code
    protected $fillable = [
        'title',        // Titre de la tâche
        'description',  // Description détaillée
        'status',       // a_faire / en_cours / termine
    ];

    /**
     * Relation 1-N avec MatchTask
     * Une tâche peut avoir PLUSIEURS match_tasks
     * Exemple : La tâche "Arbitrage" est dans 5 match_tasks
     */
    public function matchTasks()
    {
        // hasMany = "j'ai plusieurs"
        return $this->hasMany(MatchTask::class);
    }

    /**
     * Relation N-N avec GameMatch via match_tasks
     * Une tâche peut appartenir à PLUSIEURS matchs
     * Un match peut avoir PLUSIEURS tâches
     * Exemple : "Préparer terrain" existe dans Raja vs WAC ET FAR vs MAS
     */
    public function matches()
    {
        // belongsToMany = relation N-N
        // 'match_tasks' = table intermédiaire
        return $this->belongsToMany(GameMatch::class, 'match_tasks');
    }
}