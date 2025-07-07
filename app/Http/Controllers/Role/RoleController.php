<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $roles = Role::where('name', 'like', "%{$search}%")->orderBy('id', 'desc')->paginate(25);

        
        return response()->json([
            "total" => $roles->total(),
            "pagination" => 25,
            "roles" => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                    'created_at' => $role->created_at->format('Y-m-d H:i A'),
                ];
            }),
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existRole = Role::where('name', $request->name)->first();
        if($existRole) {
            return response()->json([
                'message' => 'Role already exists', 
                'code' => 409,
            ]);
        }
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);
        // Assign permissions to the role
        $permissions = $request->permissions;
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
        return response()->json([
            'message' => 'Role created successfully',
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                'created_at' => $role->created_at->format('Y-m-d H:i A'),
            ],
            "code" => 201,
            "message" => "Rol creado exitosamente",
        ], );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $existRole = Role::where('id', '<>', $id)->where('name', $request->name)->first();
        if($existRole) {
            return response()->json([
                'message' => 'Role already exists', 
                'code' => 409,
            ]);
        }   

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);
            // Assign permissions to the role
        $permissions = $request->permissions;
        $role->syncPermissions($permissions);
        
        return response()->json([
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
                'created_at' => $role->created_at->format('Y-m-d H:i A'),
            ],
            "code" => 201,
            "message" => "Rol editado exitosamente",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente',
            'code' => 200,
        ]);
    }
}
