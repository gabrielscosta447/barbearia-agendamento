<?php

namespace App\Livewire\Gerenciar\Contratos;

use App\Models\BarbeariaUser;
use Livewire\Component;
use App\Enums\PaymentMethods;
use Illuminate\Support\Facades\Http;
use MercadoPago\Client\Customer\CustomerClient;
use MercadoPago\Client\Customer\CustomerCardClient;
use MercadoPago\MercadoPagoConfig;
class EditContrato extends Component
{
    public BarbeariaUser $barbeiro;

       public function mount(){
                $this->barbeiro = BarbeariaUser::where("id",$this->barbeiro->id)->withTrashed()->first();
       }
    public function editarAssinatura($formData,$paymentMethod){
      $accessToken = "APP_USR-3577992641079180-011721-ff207db72804f196d2066d2931ed850c-1644143944";
      MercadoPagoConfig::setAccessToken("APP_USR-3577992641079180-011721-ff207db72804f196d2066d2931ed850c-1644143944");
         
          
             if(($this->barbeiro->payment_method->value == "Cartão de Crédito" || $this->barbeiro->payment_method->value == "Cartão de Débito") && ($paymentMethod == "debit_card" || $paymentMethod == "credit_card") ) {
                if (defined(PaymentMethods::class . '::' . $formData['payment_method_id'])) {
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $formData['payment_method_id']);
                } else {
               
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $paymentMethod);
                }
                $response =  Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->put('https://api.mercadopago.com/preapproval/' . $this->barbeiro->assinatura_id, [
                    
                        "card_token_id"=> $formData['token']
                    
                ]); 

                $customerResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get('https://api.mercadopago.com/v1/customers/search', [
                    'email' => auth()->user()->email
                ]);
            
            
            $customer = json_decode($customerResponse->body());
            
                // Verifica se o cliente foi encontrado
                if (empty($customer->results)) {
                    $client_customer = new CustomerClient();
                    $customer = $client_customer->create(["email" => auth()->user()->email]);
            
                
                    auth()->user()->payer_id = $customer->id;
                    auth()->user()->save();
                } else {
                   
                    auth()->user()->payer_id = $customer->results[0]->id;
                    auth()->user()->save();
                }
            
                $client_card = new CustomerCardClient();
                try {
               $response = $client_card->create(auth()->user()->payer_id, ["token" => $formData['token']]);
            } catch (\Exception $e) {
                dd($e);
            }    
            $this->barbeiro->card_id = $response->id ?? null;
                 
                $this->barbeiro->save();
             } elseif(($this->barbeiro->payment_method->value == "PIX" || $this->barbeiro->payment_method->value == "Boleto") && ($paymentMethod == "debit_card" || $paymentMethod == "credit_card")) {
                if (defined(PaymentMethods::class . '::' . $formData['payment_method_id'])) {
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $formData['payment_method_id']);
                } else {
               
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $paymentMethod);
                }
              $this->barbeiro->payment_id = null;
              $customerResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://api.mercadopago.com/v1/customers/search', [
                'email' => auth()->user()->email
            ]);
        
        
            $customer = json_decode($customerResponse->body());
        
            // Verifica se o cliente foi encontrado
            if (empty($customer->results)) {
                $client_customer = new CustomerClient();
                $customer = $client_customer->create(["email" => auth()->user()->email]);
        
            
                auth()->user()->payer_id = $customer->id;
                auth()->user()->save();
            } else {
               
                auth()->user()->payer_id = $customer->results[0]->id;
                auth()->user()->save();
            }
        
            $client_card = new CustomerCardClient();
            try {
           $response = $client_card->create(auth()->user()->payer_id, ["token" => $formData['token']]);
        } catch (\Exception $e) {
            dd($e);
        }   
        $this->barbeiro->card_id = $response->id ?? null; 
            
            $this->barbeiro->save();
             } elseif(($this->barbeiro->payment_method->value == "Cartão de Crédito" || $this->barbeiro->payment_method->value == "Cartão de Débito")  && ($paymentMethod == "ticket" || $paymentMethod == "bank_transfer")) {
                if (defined(PaymentMethods::class . '::' . $formData['payment_method_id'])) {
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $formData['payment_method_id']);
                } else {
               
        
                    $this->barbeiro->payment_method = constant(PaymentMethods::class . '::' . $paymentMethod);
                }
                $assinatura = $this->barbeiro->assinatura_id;
                  $this->barbeiro->assinatura_id = null;
                  $this->barbeiro->save();
                  $response =  Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->put('https://api.mercadopago.com/preapproval/' .  $assinatura, [
                    
                        'status' => "cancelled"
                    
                ]);

                 $customerResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get('https://api.mercadopago.com/v1/customers/search', [
                    'email' => auth()->user()->email
                ]);
            
                $customer = json_decode($customerResponse->body());
                $client_customer = new CustomerClient();
                if (empty($customer->results)) {
                   
                    $customer = $client_customer->create(["email" => auth()->user()->email]);
                   
                
                    auth()->user()->payer_id = $customer->id;
                    auth()->user()->save();
                } else {
                   
                    auth()->user()->payer_id = $customer->results[0]->id;
                    auth()->user()->save();
                } 
               if($paymentMethod == "ticket") {
               $client_customer->update(auth()->user()->payer_id, [
         
                    "first_name" => $formData['payer']['first_name'],
                    "last_name" => $formData['payer']['last_name'],
                   
                    "identification" => array(
                      "type" => "CPF",
                      "number" => "12345678909"
                    ),
                    "default_address" => $formData['payer']['address']['neighborhood'],
                    "address" => array(
                      "city" => $formData['payer']['address']['city'],
                      "zip_code" => $formData['payer']['address']['zip_code'],
                      "street_name" => $formData['payer']['address']['street_name'],
                      "street_number" => $formData['payer']['address']['street_name']
                    )
                  ]); 
                }
                      
             
             }

             $this->barbeiro->save();
             $this->dispatch('cancelEditMode');

    }


    public function render()
    {
        return view('livewire.gerenciar.contratos.edit-contrato');
    }
}
