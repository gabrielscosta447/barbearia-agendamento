<?php

namespace App\Livewire;

use App\Models\Barbearia;
use App\Steps\General;
use App\Steps\BarbeariaPayments;
use Vildanbina\LivewireWizard\WizardComponent;
use App\Models\User;
use App\Steps\Horario;
use App\Steps\Imagem;
use Livewire\WithFileUploads;
use App\Steps\Pagamento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\PreApproval\PreApprovalClient;
use MercadoPago\Client\PreApprovalPlan\PreApprovalPlanClient;
use MercadoPago\Client\Customer\CustomerClient;
use MercadoPago\Client\Customer\CustomerCardClient;
use MercadoPago\Client\PreApproval\createPreApproval;
use MercadoPago\Client\CardToken\CardTokenClient;



class UserWizard extends WizardComponent
{
 
    use WithFileUploads;
     // My custom class property
     public $userId;
  
 
     public array $steps = [

     
        General::class,
       
   
        Horario::class,
        BarbeariaPayments::class,
        Imagem::class,
   Pagamento::class,
 
    ];

    public function pagar($cardFormData, $additionalData)
{



    $accessToken = 'APP_USR-3045657775074783-011813-596cca2fb4fa464e0da2cd74abe69972-1642165427'; 


    MercadoPagoConfig::setAccessToken($accessToken);
    MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

    $client = new PreApprovalPlanClient();
    $planData = [
        'reason' => 'Barbearia John',
        'description' => 'Assinatura Mensal',
        'external_reference' => auth()->user()->id,
        'auto_recurring' => [
            'frequency' => 1,
            'frequency_type' => 'months',
            'transaction_amount' => 15,
            'currency_id' => 'BRL',
            'free_trial' => [
                'frequency' => 1,
                'frequency_type' => 'months',
            ],
        ],

        "payment_methods_allowed" => [
            "payment_methods" => [
              [
                "id" => $cardFormData['payment_method_id']
              ]
            ],
          
        ],
        'application_fee' => 0.99,
   
        'back_url' => 'https://mercadopago.com.br',
    
    ];
    $responsePlan = $client->create($planData);

    
    try {
      
        
      
       $client = new PreApprovalClient();
  

        $preapprovalData = [

            
            
            'preapproval_plan_id' => $responsePlan->id,
         
       
            'card_token_id' => $cardFormData['token'],  
 
            
             'payer_email'=>$cardFormData['payer']['email'],

             "status" => "authorized",
             'auto_recurring' => [
                'frequency' => 1,
                'frequency_type' => 'months',
                'transaction_amount' => 15,
                'currency_id' => 'BRL',
                'free_trial' => [
                    'frequency' => 1,
                    'frequency_type' => 'months',
                ],
            ],
         
        ];

       
        $responsePreapproval = $client->create($preapprovalData);

        dd($responsePreapproval);
        
    } catch (\Exception $e) {
        // Captura a exceção
        $error = $e->getMessage();
        // Faça o tratamento do erro conforme necessário
        dd($e);
    }
}
     /*
      * Will return App\Models\User instance or will create empty User (based on $userId parameter) 
      */

      
     public function model()
     {
         return User::findOrNew($this->userId);
     }
}
