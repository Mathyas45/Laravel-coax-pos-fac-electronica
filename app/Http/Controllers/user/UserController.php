<?php

namespace App\Http\Controllers\user;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\user\UserCollection;
use App\Http\Resources\user\UserResource;
use LDAP\Result;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::whereRaw("
            CONCAT(
                users.name, ' ',
                IFNULL(users.surname, ' '), ' ',
                IFNULL(users.phone, ' '), ' ',
                users.email, ' ',
                IFNULL(users.n_document, ' ')
            ) LIKE ?", "%{$search}%")
        ->orderBy("id", "desc")
        ->paginate(25);
        $roles = Role::all();
        return response()->json([
            "total" => $users->total(),
            "pagination" => 25,
            "users" => UserCollection::make($users),
            "roles" => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
        ]);
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = User::where('email', $request->email)->first();

        if ($is_exists) {
            return response()->json([
                "code" => 409,
                "message" => "Un usuario ya existe con ese correo electrónico"
            ]); // Return a 400 Bad Request response
        }
        if($request ->hasFile("imagen")){
            $path = Storage::putFile("users", $request->file("imagen"));
            $request->merge(['avatar' => $path]);
        }
        if ($request->password) {
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        $user = User::create($request->all());
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        return response()->json([
            "code" => 201,
            "message" => "Usuario creado correctamente",
            "user" => UserResource::make($user)
        ]);
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
          $is_exists = User::where('email', $request->email)->where('id', '<>', $id)->first();

        if ($is_exists) {
            return response()->json([
                "code" => 409,
                "message" => "Un usuario ya existe con ese correo electrónico"
            ]); // Return a 400 Bad Request response
        }
        $user = User::findOrFail($id);
        if($request ->hasFile("imagen")){
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = Storage::putFile("users", $request->file("imagen"));
            $request->request->add(['avatar' => $path]);
        }
        // actualizar password si es que viene nuevo
        $password_limpio = $request->password ? trim($request->password) : null;
        if ($password_limpio) {
            $request->request->add(['password' => bcrypt($password_limpio)]);
        } else {
            unset($request['password']);
        }

        if($user -> role_id != $request->role_id){
            $user->removeRole($user->role_id);
            $role = Role::findOrFail($request->role_id);
            $user->assignRole($role);
        }
        
        $user->update($request->all());
        return response()->json([
            "code" => 201,
            "message" => "Usuario editado  correctamente",
            "user" => UserResource::make($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }
        $user->delete();
        return response()->json([
            "code" => 200,
            "message" => "Usuario eliminado correctamente"
        ]);
    }
}
