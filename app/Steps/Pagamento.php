<?php

namespace App\Steps;

use App\Enums\DaysOfWeek;
use App\Enums\PaymentMethods;
use Vildanbina\LivewireWizard\Components\Step;

use Illuminate\Validation\Rule;
use App\Models\Barbearia;
use App\Models\Barbeiros;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\UserWorkingHours;
use App\Models\Plan;
use MercadoPago\Client\PreApproval\PreApprovalClient;
use MercadoPago\Client\PreApprovalPlan\PreApprovalPlanClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Redirect;
use MercadoPago\Client\Customer\CustomerClient;

use MercadoPago\Client\Customer\CustomerCardClient;
use MercadoPago\Client\Preference\PreferenceClient;
use Illuminate\Support\Facades\Cache;
use App\Models\BarbeariaUser;
use MercadoPago\Net\MPSearchRequest;
use App\Enums\PlanTypes;
use App\Livewire\Plano;
use App\Models\Maquininha;

class Pagamento extends Step
{
    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'steps.pagamento';
    public $qrCode;
    public $cardIds = [];



    /*
     * Initialize step fields
     */
    public function mount()
    {


    }


        /*
     * When Wizard Form has submitted
     */
    public function save($state, $formData, $paymentMethod,$selectedPlan)
    {



        $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");


        MercadoPagoConfig::setAccessToken($accessToken);
   

        $barbearia = new Barbearia;
        $barbearia->nome = $state['name'];
        $barbearia->cep = $state['cep'];
        $path = $state['imagem']->store('/', 'public');
        $barbearia->imagem =  $path;
        $barbearia->rua = $state['rua'];
        $barbearia->cidade = $state['cidade'];

        



        $barbearia_user = new BarbeariaUser;
        $barbearia_user->payment_methods_allowed = $state['payments'];


        $barbearia_user->user_id = auth()->user()->id;


        if (defined(PaymentMethods::class . '::' . $formData['payment_method_id'])) {

        $barbearia_user->payment_method = constant(PaymentMethods::class . '::' . $formData['payment_method_id']);

        } else {


            $barbearia_user->payment_method = constant(PaymentMethods::class . '::' . $paymentMethod);
        }
        $barbearia_user->price = constant(PlanTypes::class . '::' . 'mensal');
        $barbearia->estado = $state['estado'];
        $barbearia->complemento = $state['complemento'];
        $barbearia->owner_id = auth()->user()->id;
        $barbearia->slug = $state['slug'];
        $barbearia->cpf = $state['cpf'];
        $barbearia->bairro = $state['bairro'];
        $cep = preg_replace('/[^0-9]/', '', $state['cep']); // limpa o CEP
    
        $endereco = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();


        if (!isset($endereco['erro'])) {
          // Função auxiliar para remover acentos
      // Remove acentos e caracteres especiais
    $removeAcentos = function ($str) {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace('/[^A-Za-z0-9\s]/', '', $str); // remove apóstrofos e caracteres inválidos
        return trim($str);
    };

    $logradouro  = $removeAcentos($endereco['logradouro'] ?? '');
    $bairro      = $removeAcentos($endereco['bairro'] ?? '');
    $localidade  = $removeAcentos($endereco['localidade'] ?? '');
    $uf          = $removeAcentos($endereco['uf'] ?? '');

    // Monta a query e faz o encode no formato de URL
    $partes = array_filter([$logradouro, $bairro, $localidade, $uf, 'Brasil']);
    $query = implode(', ', $partes);
    $query = str_replace(' ', '+', $query); // substitui espaços por '+'

    


          
    try {
    $geo = Http::withHeaders([
        'User-Agent' => 'MeuAppLaravel/1.0 (contato@meusite.com)',
    ])->get('https://nominatim.openstreetmap.org/search', [
        'q' => $query,
        'format' => 'json',
        'limit' => 1,
    ]);

    // Se a resposta não for 200, lança exceção
    $geo->throw();

    $dados = $geo->json(); // converte o conteúdo em array



} catch (\Illuminate\Http\Client\RequestException $e) {
    // Mostra detalhes do erro HTTP
    dd([
        'message' => $e->getMessage(),
        'status' => optional($e->response)->status(),
        'body' => optional($e->response)->body(),
    ]);
} catch (\Exception $e) {
    // Erros de rede ou outros
    dd([
        'message' => $e->getMessage(),
    ]);
}
            if (!empty($dados)) {
                $barbearia->latitude = $dados[0]['lat'];
                $barbearia->longitude = $dados[0]['lon'];
            }
        }


        $barbearia->save();
        $barbearia_user->barbearia_id = $barbearia->id;
        $barbearia_user->save();
        $maquininha =  new Maquininha;
        $maquininha->name = $state['maquininhaname'];
        $maquininha->barbearia_user_id = $barbearia_user->id;
        $maquininha->taxa_debito = $state['maquininhadebito'];
        $maquininha->taxa_credito = $state['maquininhacredito'];
        $maquininha->save();
        $barbearia_user->delete();





        $dia = null;

        foreach ($state['dias'] as $index => $ativo) {
            if ($ativo) {

                $indexInt = $index;


                $dia = DaysOfWeek::from($indexInt);



                if ($dia !== null) {
                    UserWorkingHours::create([


                 'barbearia_user_id' => $barbearia_user->id,
                        'day_of_week' => $dia->value,
                        'start_hour' => $state['horariosIniciais'][$index],
                        'end_hour' => $state['horariosFinais'][$index],
                        'intervals' => [
                            'interval' => [
                                'start' => $state['intervaloInicial'][$index],
                                'end' => $state['intervaloFinal'][$index]
                            ]
                        ]
                    ]);
                }
            }
        }

if($paymentMethod === 'debit_card' || $paymentMethod === 'credit_card' ) {




    $client = new PreApprovalClient();


    $preapprovalData = [





   'preapproval_plan_id' => '2c9380849075726001907b4af1280234',



         'payer_email'=> auth()->user()->email,

           'card_token_id' => $formData['token'], 


        'external_reference' => $barbearia_user->id



    ];




    try {
        $assinatura = $client->create($preapprovalData);
      
        // Faça algo com a assinatura criada, como salvar no banco de dados ou retornar uma resposta para o usuário
    } catch (\Exception $e) {
           dd($e);
    }


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
    Redirect::route('barbearia.billing', ['slug' => $barbearia->slug]);
}
     $barbearia_user->card_id = $response->id;
    $barbearia_user->save();
 Redirect::route('barbearia.billing', ['slug' => $barbearia->slug]);







} else {

    $preferenceID = '1644143944-27896130-0574-4b9f-a031-653b4a6349ff';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->get("https://api.mercadopago.com/checkout/preferences/{$preferenceID}");


    $preco = $response->json();


    $idempotencyKey = uniqid();

    $paymentData = [
         'transaction_amount'=> $preco['items'][0]['unit_price'] ,

        'description' => 'Pague o plano do barbeiro',

        'payer' => [

            'email' => $formData['payer']['email'],

        ],



        'external_reference' => $barbearia_user->id
    ];


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



    if ($formData['payment_method_id'] === 'bolbradesco') {

        $paymentData['payer']['identification']['type'] = $formData['payer']['identification']['type'];
$paymentData['payer']['first_name'] = $formData['payer']['first_name'];
$paymentData['payer']['last_name'] = $formData['payer']['last_name'];
           $paymentData['payer']['identification']['number'] = $formData['payer']['identification']['number'];
           $paymentData['payer']['address']['zip_code'] = $formData['payer']['address']['zip_code'];
           $paymentData['payer']['address']['street_name'] = $formData['payer']['address']['street_name'];
           $paymentData['payer']['address']['street_number'] = $formData['payer']['address']['street_number'];
           $paymentData['payer']['address']['neighborhood'] = $formData['payer']['address']['neighborhood'];
           $paymentData['payer']['address']['city'] = $formData['payer']['address']['city'];
           $paymentData['payer']['address']['federal_unit'] = $formData['payer']['address']['federal_unit'];
           $paymentData['payment_method_id'] = 'bolbradesco';

            $customer = $client_customer->update(auth()->user()->payer_id, [

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



    } elseif($formData['payment_method_id'] === 'pec') {
        $paymentData['payer']['identification']['type'] = 'CPF';
        $paymentData['payer']['first_name'] = $formData['payer']['first_name'];
        $paymentData['payer']['last_name'] = $formData['payer']['last_name'];
           $paymentData['payer']['identification']['number'] = '12345678909';
           $paymentData['payment_method_id'] = 'pec';
    } else {

        $paymentData['payment_method_id'] = 'pix';

    }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'X-Idempotency-Key' => $idempotencyKey,
        ])->post("https://api.mercadopago.com/v1/payments?access_token={$accessToken}&preference_id={$preferenceID}", $paymentData);




    $barbearia_user->payment_id = $response->json()['id'];
    $barbearia_user->plan_ends_at = $response->json()['date_of_expiration'];

   $barbearia_user->save();

   Redirect::route('barbearia.billing', ['slug' => $barbearia->slug]);

}












if($paymentMethod === 'debit_card' || $paymentMethod === 'credit_card' ) {
    $barbearia_user->assinatura_id = $assinatura->id;

  $barbearia_user->plan_ends_at = Carbon::parse($assinatura->next_payment_date);

  $barbearia_user->save();

}












    }



    private function limparCacheBarbearias()
    {

        $cacheKey = "homepage-barbearias";


        Cache::forget($cacheKey);
    }

    /*
    * Step icon
    */
    public function icon(): string
    {
        return 'credit-card';
    }







    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Pagamento');
    }
}
