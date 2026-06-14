<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AvillFareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * AVILL override de RegularOrderController.
 *
 * Solo sobrescribe deliveryFeeSummary() para consultar AvillFareService primero.
 * Todos los demás métodos deben existir en el RegularOrderController base de Glover —
 * este archivo extiende ese comportamiento sin duplicar lógica.
 *
 * INSTRUCCIÓN DE MERGE:
 * Copiar el método deliveryFeeSummary() de este archivo al RegularOrderController
 * original de Glover instalado en el servidor, reemplazando el método existente.
 */
class RegularOrderController extends Controller
{
    protected AvillFareService $avillFare;

    public function __construct(AvillFareService $avillFare)
    {
        $this->avillFare = $avillFare;
    }

    /**
     * GET /api/delivery/fee/summary
     *
     * Calcula el costo de domicilio. Consulta AvillFareService primero.
     * Si la dirección de origen está en una zona AVILL de Quibdó, retorna tarifa fija.
     * Si no, cae al cálculo estándar de Glover (distancia + tiempo).
     */
    public function deliveryFeeSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id'    => 'required|exists:vendors,id',
            'stops'        => 'required',
            'delivery_address_id' => 'nullable|exists:delivery_addresses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $stops = $request->input('stops');

        // Intentar tarifa AVILL primero
        $avillQuote = $this->avillFare->quoteForDelivery($stops);

        if ($avillQuote) {
            if ($avillQuote['pending']) {
                return response()->json([
                    'delivery_fee'      => 0,
                    'avill_fixed_fare'  => false,
                    'avill_fare_pending'=> true,
                    'avill_quote'       => $avillQuote,
                    'message'           => $avillQuote['message'],
                ]);
            }

            return response()->json([
                'delivery_fee'      => $avillQuote['total'],
                'avill_fixed_fare'  => true,
                'avill_fare_pending'=> false,
                'avill_quote'       => $avillQuote,
            ]);
        }

        // Fallback: lógica estándar de Glover
        // El RegularOrderController base de Glover debe tener este método.
        // Llamar parent::deliveryFeeSummary($request) si se hace extend,
        // o copiar aquí la lógica original del método de Glover.
        return $this->gloverDeliveryFeeSummary($request);
    }

    /**
     * Placeholder para la lógica estándar de Glover.
     * Al hacer el merge en el servidor, reemplazar este método por la lógica
     * original de deliveryFeeSummary() del RegularOrderController de Glover.
     */
    protected function gloverDeliveryFeeSummary(Request $request)
    {
        // MERGE: pegar aquí el cuerpo original del método deliveryFeeSummary()
        // del RegularOrderController de Glover 1.8.40
        return response()->json(['message' => 'Glover base method not merged yet'], 501);
    }
}
