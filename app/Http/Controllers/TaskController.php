<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Retourne la liste de toutes les tâches
     * GET /api/tasks
     */
    public function index()
    {
        return response()->json(Task::all());
    }

    /**
     * Crée une nouvelle tâche
     * POST /api/tasks
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status ?? 'a_faire',
        ]);
        return response()->json($task, 201);
    }

    /**
     * Retourne une tâche spécifique
     * GET /api/tasks/{id}
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Met à jour une tâche existante
     * PUT /api/tasks/{id}
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return response()->json($task);
    }

    /**
     * Supprime une tâche
     * DELETE /api/tasks/{id}
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Tâche supprimée avec succès'
        ]);
    }
}