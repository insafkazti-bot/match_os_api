<?php
namespace App\Http\Controllers;

use App\Models\MatchTask;
use Illuminate\Http\Request;

class MatchTaskController extends Controller
{
    /**
     * Retourne la liste de toutes les match_tasks
     * avec les relations (match, task, member)
     * GET /api/match-tasks
     */
    public function index()
    {
        return response()->json(
            MatchTask::with(['gameMatch', 'task', 'member'])->get()
        );
    }

    /**
     * Crée une nouvelle match_task
     * POST /api/match-tasks
     */
    public function store(Request $request)
    {
        $matchTask = MatchTask::create([
            'match_id'  => $request->match_id,
            'task_id'   => $request->task_id,
            'member_id' => $request->member_id,
            'status'    => $request->status ?? 'a_faire',
            'notes'     => $request->notes,
            'deadline'  => $request->deadline,
        ]);
        return response()->json($matchTask, 201);
    }

    /**
     * Retourne une match_task spécifique
     * avec ses relations
     * GET /api/match-tasks/{id}
     */
    public function show($id)
    {
        $matchTask = MatchTask::with(['gameMatch', 'task', 'member'])
                              ->findOrFail($id);
        return response()->json($matchTask);
    }

    /**
     * Met à jour une match_task existante
     * PUT /api/match-tasks/{id}
     */
    public function update(Request $request, $id)
    {
        $matchTask = MatchTask::findOrFail($id);
        $matchTask->update($request->all());
        return response()->json($matchTask);
    }

    /**
     * Supprime une match_task
     * DELETE /api/match-tasks/{id}
     */
    public function destroy($id)
    {
        MatchTask::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Match Task supprimée avec succès'
        ]);
    }
}