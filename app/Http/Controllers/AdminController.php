<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Throwable;

class AdminController extends Controller
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

        return AdminResource::collection(Admin::orderByDesc('id')->paginate($perPage));
    }

    public function fanzone_create(StoreAdminRequest $request)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();
            $admin = Admin::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'avatar_url' => $validated['avatar_url'] ?? null,
            ]);

            return (new AdminResource($admin))->response()->setStatusCode(201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create admin'], 500);
        }
    }

    public function fanzone_show(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        return new AdminResource(Admin::findOrFail($id));
    }

    public function fanzone_edit(UpdateAdminRequest $request, int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $admin = Admin::findOrFail($id);
            $validated = $request->validated();

            if (array_key_exists('password', $validated) && $validated['password']) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $admin->update($validated);

            return new AdminResource($admin->fresh());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update admin'], 500);
        }
    }

    public function fanzone_delete(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        Admin::findOrFail($id)->delete();

        return response()->json(['message' => 'Admin supprimé avec succès']);
    }
}
