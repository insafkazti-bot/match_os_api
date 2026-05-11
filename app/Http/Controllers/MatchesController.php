<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatchesRequest;
use App\Http\Requests\UpdateMatchesRequest;
use App\Http\Resources\MatchesResource;
use App\Models\Admin;
use App\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class MatchesController extends Controller
{
    private function parseMatchDate(string $value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    private function requireAdmin(): ?JsonResponse
    {
        $user = request()->user();
        if (! $user || ! ($user instanceof Admin)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return null;
    }

    public function fanzone_index()
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        $perPage = (int) request()->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $query = Matches::query()->orderByDesc('match_date');

        $status = request()->query('status');
        if (is_string($status) && $status !== '') {
            $query->where('status', $status);
        }

        $title = request()->query('title');
        if (is_string($title) && trim($title) !== '') {
            $query->where('title', 'like', '%' . trim($title) . '%');
        }

        return MatchesResource::collection(
            $query->paginate($perPage)
        );
    }

    public function fanzone_show(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        return new MatchesResource(Matches::with('matchTasks')->findOrFail($id));
    }

    public function fanzone_create(StoreMatchesRequest $request)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();

            $match = Matches::create([
                'title' => $validated['title'],
                'location' => $validated['location'] ?? null,
                'match_date' => $this->parseMatchDate($validated['match_date']),
                'team_a_name' => $validated['team_a_name'],
                'team_b_name' => $validated['team_b_name'],
                'score_a' => array_key_exists('score_a', $validated) ? $validated['score_a'] : null,
                'score_b' => array_key_exists('score_b', $validated) ? $validated['score_b'] : null,
                'status' => $validated['status'] ?? null,
            ]);

            return (new MatchesResource($match))->response()->setStatusCode(201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create match'], 500);
        }
    }

    public function fanzone_edit(UpdateMatchesRequest $request, int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();

            $match = Matches::findOrFail($id);
            $match->update([
                'title' => $validated['title'],
                'location' => $validated['location'] ?? null,
                'match_date' => $this->parseMatchDate($validated['match_date']),
                'team_a_name' => $validated['team_a_name'],
                'team_b_name' => $validated['team_b_name'],
                'score_a' => array_key_exists('score_a', $validated) ? $validated['score_a'] : $match->score_a,
                'score_b' => array_key_exists('score_b', $validated) ? $validated['score_b'] : $match->score_b,
                'status' => array_key_exists('status', $validated) ? $validated['status'] : $match->status,
            ]);

            return new MatchesResource($match->fresh());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update match'], 500);
        }
    }

    public function fanzone_delete(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        Matches::findOrFail($id)->delete();

        return response()->json(['message' => 'Match supprimé avec succès']);
    }
}
