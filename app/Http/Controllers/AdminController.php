<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Retourne la liste de tous les admins
     * GET /api/admins
     */
    public function index()
    {
        return response()->json(Admin::all());
    }

    /**
     * Crée un nouvel admin
     * POST /api/admins
     */
    public function store(Request $request)
    {
        $admin = Admin::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'profile_picture' => $request->profile_picture,
        ]);
        return response()->json($admin, 201);
    }

    /**
     * Retourne un admin spécifique
     * GET /api/admins/{id}
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    /**
     * Met à jour un admin existant
     * PUT /api/admins/{id}
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update($request->all());
        return response()->json($admin);
    }

    /**
     * Supprime un admin
     * DELETE /api/admins/{id}
     */
    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Admin supprimé avec succès'
        ]);
    }
}