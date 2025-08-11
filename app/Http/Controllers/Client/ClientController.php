<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Client::query();
            
            // Filtros opcionales
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('n_document', 'like', "%{$search}%");
                });
            }

            if ($request->has('type_client') && $request->type_client) {
                $query->where('type_client', $request->type_client);
            }

            if ($request->has('state') && $request->state !== null) {
                $query->where('state', $request->state);
            }

            // Paginación
            $perPage = $request->get('per_page', 25);
            $clients = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'total' => $clients->total(),
                'clients' => [
                    'data' => ClientResource::collection($clients->items())
                ],
                'paginate' => 25
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al obtener los clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $is_exists = Client::where('full_name', $request->full_name)->first();
            if ($is_exists) {
                return response()->json([
                    "code" => 409,
                    "message" => "Un cliente ya existe con ese nombre"
                ]); // Return a 400 Bad Request response
            }
            $exist_documento = Client::where('n_document', $request->n_document)->first();
            if ($exist_documento) {
                return response()->json([
                    "code" => 409,
                    "message" => "Un cliente ya existe con ese número de documento"
                ]);
            }

            $request->request->add(["user_id" => auth('api')->user()->id]);

            $client = Client::create($request->all());

            return response()->json([
                'code' => 201,
                'message' => 'Cliente creado correctamente',
                'client' => ClientResource::make($client)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            
            $client = Client::with('user')->findOrFail($id);

            return response()->json([
                'code' => 200,
                'message' => 'Cliente obtenido correctamente',
                'client' => ClientResource::make($client)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Cliente no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $is_exists = Client::where("id", "<>", $id)->where("full_name", $request->full_name)->first();
            if ($is_exists) {
                return response()->json([
                    "code" => 409,
                    "message" => "Un cliente ya existe con ese nombre"
                ]); // Return a 400 Bad Request response
            }
            $exist_documento = Client::where("id", "<>", $id)->where('n_document', $request->n_document)->first();
            if ($exist_documento) {
                return response()->json([
                    "code" => 409,
                    "message" => "Un cliente ya existe con ese número de documento"
                ]);
            }

            $request->request->add(["user_id" => auth('api')->user()->id]);
            $client = Client::findOrFail($id);
            $client->update($request->all());

            return response()->json([
                'code' => 201,
                'message' => 'Cliente actualizado correctamente',
                'client' => ClientResource::make($client)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            // actualizar el usuario que elimina el cliente y la hora
            $request = request();
            $request->request->add(["user_id" => auth('api')->user()->id]);
            $request->request->add(["deleted_at" => now()]);
            $client = Client::findOrFail($id);
            $client->update($request->all());
            $client->delete();


            return response()->json([
                'code' => 200,
                'message' => 'Cliente eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al eliminar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft deleted client.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $client = Client::withTrashed()->findOrFail($id);
            $client->restore();

            return response()->json([
                'code' => 200,
                'message' => 'Cliente restaurado correctamente',
                'client' => ClientResource::make($client)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al restaurar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get clients by document number.
     */
    public function getByDocument(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'n_document' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Número de documento requerido',
                    'errors' => $validator->errors()
                ], 422);
            }

            $client = Client::where('n_document', $request->n_document)->first();

            if (!$client) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Cliente encontrado',
                'client' => ClientResource::make($client)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al buscar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle client state (active/inactive).
     */
    public function toggleState(string $id): JsonResponse
    {
        try {
            $client = Client::findOrFail($id);
            $client->state = $client->state == 1 ? 0 : 1;
            $client->save();

            $status = $client->state == 1 ? 'activado' : 'desactivado';

            return response()->json([
                'code' => 200,
                'message' => "Cliente {$status} correctamente",
                'client' => ClientResource::make($client)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al cambiar el estado del cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Importación masiva de clientes.
     */
    public function bulkImport(Request $request): JsonResponse
    {
        try {
            // Validar que lleguen los datos de clientes
            $validator = Validator::make($request->all(), [
                'clients' => 'required|array|min:1',
                'clients.*.name' => 'required|string|max:255',
                'clients.*.surname' => 'required|string|max:255',
                'clients.*.email' => 'required|email|max:255',
                'clients.*.type_document' => 'required|string|max:50',
                'clients.*.n_document' => 'required|string|max:50',
                'clients.*.gender' => 'nullable|string|in:M,F',
                'clients.*.phone' => 'nullable|string|max:20',
                'clients.*.birth_date' => 'nullable|date',
                'clients.*.address' => 'nullable|string|max:500',
                'clients.*.state' => 'nullable|integer|in:1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $clients = $request->input('clients', []);
            $results = [
                'success' => 0,
                'errors' => 0,
                'skipped' => 0,
                'details' => [],
                'created_clients' => []
            ];

            $userId = auth('api')->user()->id;

            // Procesar cada cliente
            foreach ($clients as $index => $clientData) {
                $rowNumber = $index + 1;
                
                try {
                    // Verificar si ya existe un cliente con el mismo nombre completo
                    $fullName = trim($clientData['name']) . ' ' . trim($clientData['surname']);
                    $existsByName = Client::where('full_name', $fullName)->first();
                    
                    if ($existsByName) {
                        $results['skipped']++;
                        $results['details'][] = "Fila {$rowNumber}: Cliente ya existe con ese nombre ({$fullName})";
                        continue;
                    }

                    // Verificar si ya existe un cliente con el mismo número de documento
                    $existsByDocument = Client::where('n_document', $clientData['n_document'])->first();
                    
                    if ($existsByDocument) {
                        $results['skipped']++;
                        $results['details'][] = "Fila {$rowNumber}: Cliente ya existe con ese número de documento ({$clientData['n_document']})";
                        continue;
                    }

                    // Verificar email duplicado
                    $existsByEmail = Client::where('email', $clientData['email'])->first();
                    
                    if ($existsByEmail) {
                        $results['skipped']++;
                        $results['details'][] = "Fila {$rowNumber}: Cliente ya existe con ese email ({$clientData['email']})";
                        continue;
                    }

                    // Preparar datos para crear el cliente
                    $newClientData = [
                        'name' => trim($clientData['name']),
                        'surname' => trim($clientData['surname']),
                        'full_name' => $fullName,
                        'email' => strtolower(trim($clientData['email'])),
                        'phone' => $clientData['phone'] ?? null,
                        'type_document' => $clientData['type_document'],
                        'n_document' => trim($clientData['n_document']),
                        'birth_date' => $clientData['birth_date'] ?? null,
                        'gender' => $clientData['gender'] ?? 'M',
                        'address' => $clientData['address'] ?? '',
                        'state' => $clientData['state'] ?? 1,
                        'type_client' => 1, // Cliente final por defecto
                        'ubigeo_region' => null,
                        'ubigeo_provincia' => null,
                        'ubigeo_distrito' => null,
                        'distrito' => '',
                        'provincia' => '',
                        'region' => '',
                        'user_id' => $userId
                    ];

                    // Crear el cliente
                    $client = Client::create($newClientData);
                    
                    $results['success']++;
                    $results['created_clients'][] = ClientResource::make($client);
                    $results['details'][] = "Fila {$rowNumber}: Cliente creado exitosamente ({$fullName})";

                } catch (\Exception $e) {
                    $results['errors']++;
                    $results['details'][] = "Fila {$rowNumber}: Error - " . $e->getMessage();
                    
                    // Log del error para debugging
                    Log::error("Error en importación masiva - Fila {$rowNumber}", [
                        'client_data' => $clientData,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Preparar mensaje de respuesta
            $message = "Importación completada: {$results['success']} creados, {$results['errors']} errores, {$results['skipped']} omitidos";
            
            return response()->json([
                'code' => 200,
                'message' => $message,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error en la importación masiva',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
