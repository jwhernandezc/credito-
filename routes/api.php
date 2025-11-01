<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::post('/metamap/webhook', function (Request $request) {
    $payload = $request->all();
    Log::info('Webhook MetaMap recibido:', $payload);

    $eventName = $payload['eventName'] ?? null;
    $status = $payload['status'] ?? null;
    $metadata = $payload['metadata'] ?? [];

    if (!empty($metadata['userId'])) {
        $userId = (int) $metadata['userId'];

        // Inicializa los valores por defecto
        $updateData = [
            'metamap_status' => $eventName,
        ];

        // Si fue aprobado -> marcar como verificado
        if ($eventName === 'verification_completed' && $status === 'verified') {
            $updateData['metamap_verified'] = 1;
            Log::info("✅ Usuario #{$userId} verificado con éxito.");
        }

        // Si fue bloqueado o expiró -> mantener como no verificado
        if (in_array($eventName, ['verification_blocked', 'verification_expired'])) {
            $updateData['metamap_verified'] = 0;
            Log::warning("⚠️ Verificación de usuario #{$userId} {$eventName}.");
        }

        // Actualiza el usuario
        DB::table('users')->where('id', $userId)->update($updateData);
    }

    return response()->json(['message' => 'Webhook recibido correctamente']);
});



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
