<?php

// Importe la classe Route pour définir les routes
use Illuminate\Support\Facades\Route;

// Importe tous les controllers
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MatchTaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Toutes les routes sont préfixées par /api
| Exemple : /api/members, /api/matches, etc.
*/

// Routes pour les membres
// GET    /api/members       → index()   → liste tous les membres
// POST   /api/members       → store()   → crée un membre
// GET    /api/members/{id}  → show()    → retourne un membre
// PUT    /api/members/{id}  → update()  → modifie un membre
// DELETE /api/members/{id}  → destroy() → supprime un membre
Route::apiResource('members', MemberController::class);

// Routes pour les admins
// GET    /api/admins       → index()
// POST   /api/admins       → store()
// GET    /api/admins/{id}  → show()
// PUT    /api/admins/{id}  → update()
// DELETE /api/admins/{id}  → destroy()
Route::apiResource('admins', AdminController::class);

// Routes pour les matchs
// GET    /api/matches       → index()
// POST   /api/matches       → store()
// GET    /api/matches/{id}  → show()
// PUT    /api/matches/{id}  → update()
// DELETE /api/matches/{id}  → destroy()
Route::apiResource('matches', MatchController::class);

// Routes pour les tâches
// GET    /api/tasks       → index()
// POST   /api/tasks       → store()
// GET    /api/tasks/{id}  → show()
// PUT    /api/tasks/{id}  → update()
// DELETE /api/tasks/{id}  → destroy()
Route::apiResource('tasks', TaskController::class);

// Routes pour les match_tasks
// GET    /api/match-tasks       → index()
// POST   /api/match-tasks       → store()
// GET    /api/match-tasks/{id}  → show()
// PUT    /api/match-tasks/{id}  → update()
// DELETE /api/match-tasks/{id}  → destroy()
Route::apiResource('match-tasks', MatchTaskController::class);
