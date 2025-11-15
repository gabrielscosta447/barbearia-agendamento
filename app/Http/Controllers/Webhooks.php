<?php

namespace App\Http\Controllers;


use App\Models\Plan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use App\Models\BarbeariaUser;
use App\Models\Agendamento;


class Webhooks extends Controller
{
    public function webhook(Request $request){
   
      $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");

     
    


        if($request->input("type") === 'subscription_preapproval' ) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->get("https://api.mercadopago.com/preapproval/{$request->input("data.id")}");
            
            $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");
            $barbearia = BarbeariaUser::withTrashed()->where('id', $response->json()['external_reference'])->first();




     if($response->json()['status'] === 'authorized') {
       
        $barbearia->assinatura_id = $response->json()['id'];
        $barbearia->plan_ends_at = $response->json()['next_payment_date'];
        $barbearia->save();
        $barbearia->restore();
     }
     if($response->json()['status'] === 'cancelled' || $response->json()['status'] === 'paused' ){
        if($barbearia->assinatura_id !=null){
        $barbearia->payment_method = null;  
        $barbearia->assinatura_id = null;
        
        $barbearia->delete();
        }
     }


  }

  if($request->input("type") === 'payment') {
    Log::info('Webhook recebido: pagamento detectado', ['data' => $request->all()]);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Accept' => 'application/json',
    ])->get("https://api.mercadopago.com/v1/payments/{$request->input("data.id")}");

    Log::info('Resposta do MercadoPago', ['response' => $response->json()]);

    $barbearia = BarbeariaUser::withTrashed()->where('id', $response->json()['external_reference'])->first();

    if ($barbearia) {
        Log::info('Resposta do MercadoPago Status', ['response' => $response->json()['status']]);

        if ($response->json()['status'] === 'approved') {
            Log::info("Pagamento autorizado para Barbearia ID: {$barbearia->id}");
            $barbearia->plan_ends_at = Carbon::now()->addMonth();
            $barbearia->restore(); 
        }

        if ($response->json()['status'] === 'cancelled') {
            Log::info("Pagamento cancelado para Barbearia ID: {$barbearia->id}");
            $barbearia->payment_method = null;  
            $barbearia->payment_id = null;
            $barbearia->delete();
        }
    } else {
        Log::warning('Barbearia não encontrada para o pagamento recebido.', ['external_reference' => $response->json()['external_reference']]);
    }
}

    }



public function webhookASAAS(Request $request)
{
    Log::info('Webhook ASAAS recebido', ['payload' => $request->all()]);

    $payment = $request->input('payment');

    if (!$payment) {
        Log::error('Webhook ASAAS error: payment object missing');
        return response()->json(['error' => 'Invalid payload'], 400);
    }

    $externalReference = $payment['externalReference'] ?? null;

    if (!$externalReference) {
        Log::error('Webhook ASAAS error: externalReference missing');
        return response()->json(['error' => 'Missing externalReference'], 400);
    }

    $event = $request->input('event');
    $status = $payment['status'] ?? null;

    /**
     * ============================================
     * 1) 🔵 TRATAMENTO PARA ASSINATURA ASAAS
     *    (quando existe subscription no pagamento)
     * ============================================
     */
    if (!empty($payment['subscription'])) {

        Log::info("Pagamento ASAAS é de ASSINATURA", [
            'subscription_id' => $payment['subscription'],
            'externalReference' => $externalReference
        ]);

        $barbearia = BarbeariaUser::withTrashed()->find($externalReference);

        if (!$barbearia) {
            Log::error("BarbeariaUser não encontrada", ['id' => $externalReference]);
            return response()->json(['error' => 'BarbeariaUser not found'], 404);
        }

        // 🔵 Assinatura paga
        if ($event === "PAYMENT_RECEIVED" && $status === "RECEIVED") {
            $barbearia->assinatura_id = $payment['subscription'];
            $barbearia->plan_ends_at = now()->addMonth();
            $barbearoa->asaas_payment_url = null;
            $barbearia->restore();
            $barbearia->save();

            Log::info("Assinatura ASAAS renovada", ['id' => $barbearia->id]);

            return response()->json(['success' => true]);
        }

        // 🔴 Assinatura VENCIDA ou CANCELADA
        if ($status === "OVERDUE" || $status === "CANCELLED") {

            $barbearia->assinatura_id = null;
            $barbearia->payment_method = null;
            $barbearia->delete();

            Log::info("Assinatura ASAAS cancelada", ['id' => $barbearia->id]);

            return response()->json(['deleted' => true]);
        }

        return response()->json(['ignored' => true]);
    }

    /**
     * ============================================
     * 2) 🟢 TRATAMENTO PARA PAGAMENTO AVULSO (AGENDAMENTO)
     *    (quando subscription é null)
     * ============================================
     */

    Log::info("Pagamento ASAAS é de AGENDAMENTO");

    $agendamento = Agendamento::withTrashed()->find($externalReference);

    if (!$agendamento) {
        Log::error("Agendamento não encontrado", ['id' => $externalReference]);
        return response()->json(['error' => 'Agendamento not found'], 404);
    }

    // 🟢 pagamento recebido
    if ($event === 'PAYMENT_RECEIVED' && $status === 'RECEIVED') {

        $agendamento->pago = 1;
        $agendamento->save();

        $valorRecebido = $payment['value'];
        $pixDestino = '12991732260'; // <<< personalize aqui

        Log::info("Pagamento confirmado. Iniciando transferência PIX...");

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => env('PIX_ACCESS_TOKEN'),
        ])->post('https://api.asaas.com/v3/transfers', [
            'value' => $valorRecebido,
            'operationType' => 'PIX',
            'pixAddressKey' => $pixDestino,
            'pixAddressKeyType' => 'PHONE',
        ]);

        Log::info("Transfer ASAAS Response", ['body' => $response->json()]);

        return response()->json([
            'success' => true,
            'payment_updated' => true,
            'transfer' => $response->json()
        ]);
    }

    // 🟡 pagamento vencido/cancelado
    if ($status === 'OVERDUE' || $status === 'CANCELLED') {
        $agendamento->delete();
        Log::info("Agendamento cancelado/vencido apagado");
        return response()->json(['deleted' => true]);
    }

    return response()->json(['ignored' => true]);
}



    
}
