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

    public function webhookBB(Request $request){

       $agendamentoModel = new Agendamento();

         $agendamento = $agendamentoModel->where('id_pix', $request->input('txid'))->first();

         if($request->input('status') === 'CONCLUIDA') {
            if($agendamento) {
                $agendamento->pago = 1;
                $agendamento->save();
            }
         }
         if($request->input('status') === 'VENCIDA' || $request->input('status') === 'EXPIRADA') {
            if($agendamento) {
                $agendamento->delete();
            }
         }

    }

    
}
