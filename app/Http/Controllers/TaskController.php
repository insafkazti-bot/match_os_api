<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Admin;
use App\Models\Task;
use Throwable;

class TaskController extends Controller
{
    private function requireAdmin()
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

        return TaskResource::collection(Task::orderByDesc('id')->paginate($perPage));
    }

    public function fanzone_create(StoreTaskRequest $request)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();

            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'a_faire',
            ]);

            return (new TaskResource($task))->response()->setStatusCode(201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create task'], 500);
        }
    }

    public function fanzone_show(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        return new TaskResource(Task::findOrFail($id));
    }

    public function fanzone_edit(UpdateTaskRequest $request, int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();
            $task = Task::findOrFail($id);
            $task->update([
                'title' => array_key_exists('title', $validated) ? $validated['title'] : $task->title,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? $task->status,
            ]);

            return new TaskResource($task->fresh());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update task'], 500);
        }
    }

    public function fanzone_delete(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        Task::findOrFail($id)->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès']);
    }
}
