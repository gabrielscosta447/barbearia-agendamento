<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Barbearia;
use App\Models\BarbeariaUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\PaymentMethods;
use App\Livewire\Colaborador;
use MercadoPago\Client\PreApproval\PreApprovalClient;
class VerificarPagamento implements ShouldQueue
{


    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
 * Execute the job.
 */
public function handle(): void
{
    $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");
    $barbeariasPix = BarbeariaUser::whereIn("payment_method", [PaymentMethods::pix, PaymentMethods::bolbradesco])->where("price", 15)->get();
    $barbeariaAssinatura = BarbeariaUser::whereIn("payment_method", [PaymentMethods::credit_card, PaymentMethods::debit_card])->where("price", 15)->get();

    // Process Pix and Boleto payments
    foreach ($barbeariasPix as $barbearia) {
        $horarioBrasilia = Carbon::now();

        if ($barbearia->payment_id != null) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->get("https://api.mercadopago.com/v1/payments/{$barbearia->payment_id}");

            if ($response['status'] !== "cancelled" && ($response['payment_method_id'] === "pix" || $response['payment_method_id'] === "bolbradesco")) {
                if ($horarioBrasilia > Carbon::parse($barbearia->plan_ends_at)) {
                    $preferenceID = '1644143944-a0ead566-60cf-4dd3-a40d-a57dc92ba2a3';
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                    ])->get("https://api.mercadopago.com/checkout/preferences/{$preferenceID}");

                    $preco = $response->json();
                    $idempotencyKey = uniqid();
                    $paymentMethod = strtolower($barbearia->payment_method->value);

                    $paymentData = [
                        'transaction_amount' => $preco['items'][0]['unit_price'],
                        'description' => 'Pague o plano do barbeiro',
                        'payer' => [
                            'email' => $barbearia->user->email,
                        ],
                        'external_reference' => $barbearia->user_id,
                    ];

                    if ($paymentMethod === 'bolbradesco') {
                        $paymentData['payer'] += [
                            'first_name' => 'Test',
                            'last_name' => 'User',
                            'identification' => [
                                'type' => 'CPF',
                                'number' => '12345678909',
                            ],
                        ];
                        $paymentData['payment_method_id'] = 'bolbradesco';
                    } else {
                        $paymentData['payment_method_id'] = 'pix';
                    }

                    $responsePayment = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                        'X-Idempotency-Key' => $idempotencyKey,
                    ])->post("https://api.mercadopago.com/v1/payments", $paymentData);

                    $barbearia->payment_id = $responsePayment->json()['id'];
                    $barbearia->save();
                }
            }
        } else {
            $this->createNewPixOrBoletoPayment($barbearia, $accessToken);
        }
    }

   /*  // Process subscriptions for credit card and debit card payments
    foreach ($barbeariaAssinatura as $barbearia) {
        if ($barbearia->assinatura_id == null && Carbon::now() > Carbon::parse($barbearia->plan_ends_at)) {
            $client = new PreApprovalClient();
            $preapprovalData = [
                'preapproval_plan_id' => '2c9380848fa6c953018fb793b05702e7',
                'payer_email' => $barbearia->user->email,
                'card_token_id' => 'your_card_token_id', // Replace with actual token
                'external_reference' => $barbearia->id,
            ];

            try {
                $assinatura = $client->create($preapprovalData);
                $barbearia->assinatura_id = $assinatura->id;
                $barbearia->save();
            } catch (\Exception $e) {
                dd($e);
            }
        }
    } */
}

/**
 * Create a new Pix or Boleto payment for the given user.
 */
private function createNewPixOrBoletoPayment($barbearia, $accessToken): void
{
    $preferenceID = '1644143944-a0ead566-60cf-4dd3-a40d-a57dc92ba2a3';
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->get("https://api.mercadopago.com/checkout/preferences/{$preferenceID}");

    $preco = $response->json();
    $idempotencyKey = uniqid();
    $paymentMethod = strtolower($barbearia->payment_method->value);

    $paymentData = [
        'transaction_amount' => $preco['items'][0]['unit_price'],
        'description' => 'Pague o plano do barbeiro',
        'payer' => [
            'email' => 'test_user_1498281909@testuser.com',
        ],
        'external_reference' => $barbearia->id,
    ];

    if ($paymentMethod === 'bolbradesco') {
        $paymentData['payer'] += [
            'first_name' => 'Test',
            'last_name' => 'User',
            'identification' => [
                'type' => 'CPF',
                'number' => '12345678909',
            ],
        ];
        $paymentData['payment_method_id'] = 'bolbradesco';
    } else {
        $paymentData['payment_method_id'] = 'pix';
    }

    $responsePayment = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
        'X-Idempotency-Key' => $idempotencyKey,
    ])->post("https://api.mercadopago.com/v1/payments", $paymentData);

    $barbearia->payment_id = $responsePayment->json()['id'];
    $barbearia->save();
}

}
