<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AvillFareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * AVILL override de PackageOrderController.
 *
 * Solo sobrescribe summary() para consultar AvillFareService primero.
 *
 * INSTRUCCIÓN DE MERGE:
 * Copiar el método summary() de este archivo al PackageOrderController
 * original de Glover instalado en el servidor, reemplazando el método existente.
 * Inyectar AvillFareService en el constructor del controller original.
 */
class PackageOrderController extends Controller
{
    protected AvillFareService $avillFare;

    public function __construct(AvillFareService $avillFare)
    {
        $this->avillFare = $avillFare;
    }

    /**
     * POST /api/package/order/summary
     *
     * Calcula el resumen de costo para una encomienda.
     * Consulta AvillFareService primero. Si el origen está en zona AVILL,
     * retorna tarifa fija. Si no, cae al cálculo estándar de Glover.
     */
    public function summary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stops'       => 'required',
            'package_type_id' => 'nullable|exists:package_types,id',
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
                    'total'             => 0,
                    'avill_fixed_fare'  => false,
                    'avill_fare_pending'=> true,
                    'avill_quote'       => $avillQuote,
                    'message'           => $avillQuote['message'],
                ]);
            }

            return response()->json([
                'total'             => $avillQuote['total'],
                'delivery_fee'      => $avillQuote['base_amount'],
                'management_fee'    => $avillQuote['management_surcharge_amount'],
                'surcharge_total'   => $avillQuote['surcharge_total'],
                'avill_fixed_fare'  => true,
                'avill_fare_pending'=> false,
                'avill_quote'       => $avillQuote,
            ]);
        }

        // Fallback: lógica estándar de Glover
        return $this->gloverSummary($request);
    }

    /**
     * Placeholder para la lógica estándar de Glover.
     * Al hacer el merge en el servidor, reemplazar este método por la lógica
     * original de summary() del PackageOrderController de Glover.
     */
    protected function gloverSummary(Request $request)
    {
        // MERGE: pegar aquí el cuerpo original del método summary()
        // del PackageOrderController de Glover 1.8.40
        return response()->json(['message' => 'Glover base method not merged yet'], 501);
    }
}
