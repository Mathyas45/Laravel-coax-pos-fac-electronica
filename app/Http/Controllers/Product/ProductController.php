<!-- <?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->input('search');
        $categorie_id = $request->input('categorie_id');
        $state = $request->input('state');
        $unidad_medida = $request->input('unidad_medida');

        $products = Product::whereRaw("
            CONCAT(
                products.name, ' ',
                IFNULL(products.sku, ' '), ' ',
                IFNULL(products.price_general, ' '), ' ',
                IFNULL(products.price_company, ' '), ' ',
                IFNULL(products.description, ' '), ' ',
                IFNULL(products.unidad_medida, ' '), ' ',
                IFNULL(products.stock, ' '), ' ',
                IFNULL(products.stock_minimo, ' '), ' ',
                IFNULL(products.fecha_vencimiento, ' ')
            ) LIKE ?", "%{$search}%")
        ->orderBy("id", "desc")
        ->paginate(25);
        return response()->json([
            "total" => $products->total(),
            "pagination" => 25,
            "products" => ProductCollection::make($products),
        ]);
    }
        return response()->json([
            "total" => $products->total(),
            "pagination" => 25,
            "prod$products" => UserCollection::make($products),
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
} -->
