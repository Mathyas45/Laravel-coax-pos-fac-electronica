<?php
  
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
  
  
class AuthController extends Controller
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
  
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();
  
        return response()->json($user, 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
  
        return $this->respondWithToken($token);
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth("api")->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("api")->logout();
  
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

    /**
     * Refresh token for inactivity system
     * Endpoint específico para el sistema de inactividad del frontend
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        try {
            // Verificar que el usuario esté autenticado
            $user = auth("api")->user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'Token inválido o expirado'
                ], 401);
            }
            
            // Generar nuevo token usando JWTAuth
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            
            // Opcional: Log de actividad
            Log::info("Token renovado para usuario: " . $user->id . " (" . $user->email . ")");
            
            // Obtener permisos del usuario
            $permissions = $user->getAllPermissions()->map(function ($permission) {
                return $permission->name;
            });
            
            return response()->json([
                'token' => $newToken,
                'expires_in' => config('jwt.ttl') * 60,
                'user' => [
                    "id" => $user->id,
                    "full_name" => $user->name,
                    "email" => $user->email,
                    "role" => [
                        'id' => $user->role->id,
                        'name' => $user->role->name,
                    ],
                    "permissions" => $permissions,
                    "token" => $newToken, // Incluir token en user para compatibilidad
                ],
                'message' => 'Token renovado exitosamente',
                'renewed_at' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error renovando token: " . $e->getMessage());
            
            return response()->json([
                'error' => 'No se pudo renovar el token',
                'message' => 'Por favor, inicia sesión nuevamente'
            ], 401);
        }
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $permissions = auth("api")->user()->getAllPermissions()->map(function ($permission) {
            return $permission->name;
        });

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => [
                "full_name" => auth("api")->user()->name,
                "email" => auth("api")->user()->email,
                "role" => [
                    'id' => auth("api")->user()->role->id,
                    'name' => auth("api")->user()->role->name,
                ],
                "permissions" => $permissions,
            ],
        ]);
    }
}