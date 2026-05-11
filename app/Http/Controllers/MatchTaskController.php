<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatchTaskRequest;
use App\Http\Requests\UpdateMatchTaskRequest;
use App\Http\Resources\MatchTaskResource;
use App\Models\Admin;
use App\Models\Member;
use App\Models\MatchTask;
use Throwable;

class MatchTaskController extends Controller
{
    private function requireMemberOrAdmin()
    {
        $user = request()->user();
        if (! $user || ! ($user instanceof Member) && ! ($user instanceof Admin)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return null;
    }

    public function fanzone_index()
    {
        if ($resp = $this->requireMemberOrAdmin()) {
            return $resp;
        }

        $perPage = (int) request()->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $query = MatchTask::with(['matches', 'task', 'member'])->orderByDesc('id');

        if (request()->has('id_match')) {
            $query->where('id_match', request()->input('id_match'));
        }

        return MatchTaskResource::collection(
            $query->paginate($perPage)
        );
    }

    public function fanzone_create(StoreMatchTaskRequest $request)
    {
        if ($resp = $this->requireMemberOrAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();
            $user = $request->user();

            $matchTask = MatchTask::create([
                'id_match' => $validated['id_match'],
                'id_task' => $validated['id_task'],
                'id_member' => $user instanceof Member ? $user->id : (int) request()->input('id_member'),
                'status' => $validated['status'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'deadline' => $validated['deadline'] ?? null,
            ]);

            return (new MatchTaskResource($matchTask->load(['matches', 'task', 'member'])))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create match task'], 500);
        }
    }

    public function fanzone_show(int $id)
    {
        if ($resp = $this->requireMemberOrAdmin()) {
            return $resp;
        }

        return new MatchTaskResource(MatchTask::with(['matches', 'task', 'member'])->findOrFail($id));
    }

    public function fanzone_edit(UpdateMatchTaskRequest $request, int $id)
    {
        if ($resp = $this->requireMemberOrAdmin()) {
            return $resp;
        }

        $user = $request->user();
        $matchTask = MatchTask::findOrFail($id);
        if ($user instanceof Member && (int) $matchTask->id_member !== (int) $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        try {
            $validated = $request->validated();
            if ($user instanceof Member) {
                unset($validated['id_member']);
            }

            $matchTask->update($validated);

            return new MatchTaskResource($matchTask->fresh()->load(['matches', 'task', 'member']));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update match task'], 500);
        }
    }

    public function fanzone_delete(int $id)
    {
        if ($resp = $this->requireMemberOrAdmin()) {
            return $resp;
        }

        $user = request()->user();
        $matchTask = MatchTask::findOrFail($id);
        if ($user instanceof Member && (int) $matchTask->id_member !== (int) $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $matchTask->delete();

        return response()->json(['message' => 'Match Task supprimée avec succès']);
    }
}
