<?php

// Définit l'espace de noms du fichier
namespace App\Models;

// Importe la classe Model de Laravel (classe parent)
use Illuminate\Database\Eloquent\Model;

// Importe les models nécessaires pour les relations
use App\Models\GameMatch;
use App\Models\Task;
use App\Models\Member;

class MatchTask extends Model
{
    // Liste des colonnes qu'on peut remplir via le code
    protected $fillable = [
        'match_id',     // ID du match concerné (clé étrangère)
        'task_id',      // ID de la tâche concernée (clé étrangère)
        'member_id',    // ID du membre assigné (clé étrangère)
        'status',       // a_faire / en_cours / termine
        'notes',        // Notes supplémentaires
        'deadline',     // Date limite pour la tâche
    ];

    /**
     * Relation N-1 avec GameMatch
     * Une match_task appartient à UN SEUL match
     * Exemple : Cette tâche appartient au match Raja vs WAC
     */
    public function gameMatch()
    {
        // belongsTo = "j'appartiens à"
        // Utilise match_id pour trouver le match
        return $this->belongsTo(GameMatch::class);
    }

    /**
     * Relation N-1 avec Task
     * Une match_task appartient à UNE SEULE tâche
     * Exemple : Cette match_task est la tâche "Arbitrage"
     */
    public function task()
    {
        // belongsTo = "j'appartiens à"
        // Utilise task_id pour trouver la tâche
        return $this->belongsTo(Task::class);
    }

    /**
     * Relation N-1 avec Member
     * Une match_task appartient à UN SEUL membre
     * Exemple : Cette tâche est assignée à Ahmed
     */
    public function member()
    {
        // belongsTo = "j'appartiens à"
        // Utilise member_id pour trouver le membre
        return $this->belongsTo(Member::class);
    }
}