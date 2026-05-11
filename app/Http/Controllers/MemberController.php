<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Admin;
use App\Models\Member;
use Throwable;

class MemberController extends Controller
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

        return MemberResource::collection(Member::orderByDesc('id')->paginate($perPage));
    }

    public function fanzone_create(StoreMemberRequest $request)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $validated = $request->validated();
            $member = Member::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'avatar_url' => $validated['avatar_url'] ?? null,
                'position' => $validated['position'] ?? null,
            ]);

            return (new MemberResource($member))->response()->setStatusCode(201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create member'], 500);
        }
    }

    public function fanzone_show(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        return new MemberResource(Member::findOrFail($id));
    }

    public function fanzone_edit(UpdateMemberRequest $request, int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        try {
            $member = Member::findOrFail($id);
            $validated = $request->validated();

            if (array_key_exists('password', $validated) && $validated['password']) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $member->update($validated);

            return new MemberResource($member->fresh());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update member'], 500);
        }
    }

    public function fanzone_delete(int $id)
    {
        if ($resp = $this->requireAdmin()) {
            return $resp;
        }

        Member::findOrFail($id)->delete();

        return response()->json(['message' => 'Membre supprimé avec succès']);
    }
}
