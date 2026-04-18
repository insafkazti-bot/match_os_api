<?php

// Définit l'espace de noms du fichier
namespace App\Models;

// Importe la classe Model de Laravel (classe parent)
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // Liste des colonnes qu'on peut remplir via le code
    // Protège contre les attaques "mass assignment"
    protected $fillable = [
        'first_name',       // Prénom de l'admin
        'last_name',        // Nom de l'admin
        'email',            // Email unique de l'admin
        'password',         // Mot de passe (hashé)
        'profile_picture',  // Photo de profil
    ];

    // Colonnes cachées dans les réponses JSON
    // Le mot de passe ne sera jamais affiché
    protected $hidden = [
        'password',
    ];

    // Admin n'a pas de relations avec les autres tables
    // Il gère juste l'application
}