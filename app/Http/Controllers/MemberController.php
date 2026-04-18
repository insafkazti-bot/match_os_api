<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Retourne la liste de tous les membres
     * GET /api/members
     */
    public function index()
    {
        return response()->json(Member::all());
    }

    /**
     * Crée un nouveau membre
     * POST /api/members
     */
    public function store(Request $request)
    {
        $member = Member::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'phone'           => $request->phone,
            'profile_picture' => $request->profile_picture,
            'position'        => $request->position,
        ]);
        return response()->json($member, 201);
    }

    /**
     * Retourne un membre spécifique
     * GET /api/members/{id}
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    /**
     * Met à jour un membre existant
     * PUT /api/members/{id}
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->update($request->all());
        return response()->json($member);
    }

    /**
     * Supprime un membre
     * DELETE /api/members/{id}
     */
    public function destroy($id)
    {
        Member::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Membre supprimé avec succès'
        ]);
    }
}