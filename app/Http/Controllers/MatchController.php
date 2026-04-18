<?php
namespace App\Http\Controllers;

use App\Models\GameMatch;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Retourne la liste de tous les matchs
     * GET /api/matches
     */
    public function index()
    {
        return response()->json(GameMatch::all());
    }

    /**
     * Crée un nouveau match
     * POST /api/matches
     */
    public function store(Request $request)
    {
        $match = GameMatch::create([
            'title'       => $request->title,
            'location'    => $request->location,
            'match_date'  => $request->match_date,
            'team_a_name' => $request->team_a_name,
            'team_b_name' => $request->team_b_name,
            'score_a'     => $request->score_a ?? 0,
            'score_b'     => $request->score_b ?? 0,
            'status'      => $request->status ?? 'planifie',
        ]);
        return response()->json($match, 201);
    }

    /**
     * Retourne un match spécifique avec ses tâches
     * GET /api/matches/{id}
     */
    public function show($id)
    {
        $match = GameMatch::with('matchTasks')->findOrFail($id);
        return response()->json($match);
    }

    /**
     * Met à jour un match existant
     * PUT /api/matches/{id}
     */
    public function update(Request $request, $id)
    {
        $match = GameMatch::findOrFail($id);
        $match->update($request->all());
        return response()->json($match);
    }

    /**
     * Supprime un match
     * DELETE /api/matches/{id}
     */
    public function destroy($id)
    {
        GameMatch::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Match supprimé avec succès'
        ]);
    }
}