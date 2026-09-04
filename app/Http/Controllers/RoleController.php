<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $title = "Role";
        $roles = Role::orderBy('name', 'asc')->get();
        return view('role.index', compact('title', 'roles'));
    }
    public function create()
    {
        $title = "Create Role";
        return view('role.create', compact('title'));
    }
    public function store(Request $request)
    {

        Role::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);
        return redirect()->to('role');
    }
    public function edit(Request $request, int $id)
    {
        $title = "Edit Role";
        $role = Role::findOrFail($id);
        return view('role.edit', compact('title', 'role'));
    }
    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);
        return redirect()->to('role');
    }
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->to('role');
    }
}
