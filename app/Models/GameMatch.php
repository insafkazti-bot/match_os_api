<?php

// Définit l'espace de noms du fichier
namespace App\Models;

// Importe la classe Model de Laravel (classe parent)
use Illuminate\Database\Eloquent\Model;

// Importe les models nécessaires pour les relations
use App\Models\MatchTask;
use App\Models\Task;
use App\Models\Member;

class GameMatch extends Model
{
    // On définit manuellement le nom de la table
    // car "Match" est un mot réservé en PHP
    // Sans cette ligne Laravel chercherait "game_matches"
    protected $table = 'matches';

    // Liste des colonnes qu'on peut remplir via le code
    protected $fillable = [
        'title',        // Titre du match
        'location',     // Lieu du match
        'match_date',   // Date et heure du match
        'team_a_name',  // Nom de l'équipe A
        'team_b_name',  // Nom de l'équipe B
        'score_a',      // Score de l'équipe A
        'score_b',      // Score de l'équipe B
        'status',       // planifie / en_cours / termine
    ];

    /**
     * Relation 1-N avec MatchTask
     * Un match peut avoir PLUSIEURS match_tasks
     * Exemple : Match Raja vs WAC a 10 tâches assignées
     */
    public function matchTasks()
    {
        // hasMany = "j'ai plusieurs"
        return $this->hasMany(MatchTask::class);
    }

    /**
     * Relation N-N avec Task via match_tasks
     * Un match peut avoir PLUSIEURS tasks
     * Une task peut appartenir à PLUSIEURS matchs
     * Exemple : La tâche "Préparer terrain" existe dans 3 matchs
     */
    public function tasks()
    {
        // belongsToMany = relation N-N
        // 'match_tasks' = table intermédiaire
        return $this->belongsToMany(Task::class, 'match_tasks');
    }

    /**
     * Relation N-N avec Member via match_tasks
     * Un match peut avoir PLUSIEURS membres assignés
     * Un membre peut participer à PLUSIEURS matchs
     * Exemple : Ahmed participe dans 5 matchs différents
     */
    public function members()
    {
        // belongsToMany = relation N-N
        // 'match_tasks' = table intermédiaire
        return $this->belongsToMany(Member::class, 'match_tasks');
    }
}