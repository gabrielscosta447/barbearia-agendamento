<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\PreApprovalPlan\PreApprovalPlanClient;
use MercadoPago\Client\Customer\CustomerClient;
use MercadoPago\Client\Customer\CustomerCardClient;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use MercadoPago\Client\CardToken\CardTokenClient;
use MercadoPago\Exceptions\MpApiException;
use MercadoPago\Client\PreApproval\PreApprovalClient;
use Illuminate\Support\Facades\Cache;
use MercadoPago\Resources\PreApprovalPlan;
use App\Models\User;

use MercadoPago\Resources\PreApproval as ResourcesPreApproval;

class MercadoPago extends Controller
{

   /*  function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1,

            "installments" => 1, // Número padrão de parcelas
            "default_payment_method_id" => null, // Método de pagamento padrão
        ];



        $backUrls = array(
            'success' => route('dashboard'),
            'failure' => route('home')
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    } */

   /*  public function index()
    {
      $accessToken = 'TEST-3045657775074783-011813-d80b74d2be425de8d9abc56e759d6f7b-1642165427';



      $user = Auth::user();

      $userData = [
        'email' => 'test_user_1498281909@testuser.com',
        'first_name' => 'Primeiro',
        'last_name' => 'Último',
        'phone' => [
            'area_code' => '55',
            'number' => '1123456789',
        ],
    ];

    $searchCustomerUrl = 'https://api.mercadopago.com/v1/customers/search';
    $searchResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->get($searchCustomerUrl, ['email' => $userData['email']]);

    $existingCustomers = $searchResponse->json()['results'];





    if (!empty($existingCustomers)) {

        $customer_email = $existingCustomers[0]['email'];
    } else {

        $createUserUrl = 'https://api.mercadopago.com/v1/customers';
        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($createUserUrl, $userData);

        $customer_email = $userResponse->json()['email'];
    }


    $planData = [


        'reason' => 'Barbearia',

        'description' => 'Assinatura Mensal',
        'external_reference' => 'PLANO_001',
        'auto_recurring' => [
            'frequency' => 1,
            'frequency_type' => 'months',
            'transaction_amount' => 19.99,

            'currency_id' => 'BRL',
            'free_trial' => [
                'frequency' => 1,
                'frequency_type' => 'months',
            ],

        ],
        'application_fee' => 0.99,


        'payer_email' => $customer_email,

     'back_url' =>  'http://jp2xhh0o8r.laravel-sail.site:8080/dashboard',


    ];

    // Criação do plano
    $createPlanUrl = 'https://api.mercadopago.com/preapproval';
    $planResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->post($createPlanUrl, $planData);

dd($planResponse->json());
    $initPoint = $planResponse->json()['init_point'];


    return redirect()->away($initPoint);

    }
 */

     protected function authenticate()
    {
        // Getting the access token from .env file (create your own function)
        $mpAccessToken = 'TEST-3045657775074783-011813-d80b74d2be425de8d9abc56e759d6f7b-1642165427';
        // Set the token the SDK's config
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        // (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
        // Default value is set to SERVER
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }
/*   function createPreferenceRequest($items, $payer): array
{
    $paymentMethods = [
        "excluded_payment_methods" => [],
        "installments" => 12,
        "default_installments" => 1
    ];

    $backUrls = array(
        'success' => route('dashboard'),
        'failure' => route('home')
    );

    $request = [
        "items" => $items,
        "payer" => $payer,
        "payment_methods" => $paymentMethods,
        "back_urls" => $backUrls,
        "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
        "external_reference" => "1234567890",
        "expires" => false,
        "auto_return" => 'approved',
    ];

    return $request;
}

public function index()
{
    $this->authenticate();
    // Fill the data about the product(s) being pruchased
    $product1 = array(
        "id" => "1234567890",
        "title" => "Product 1 Title",
        "description" => "Product 1 Description",
        "currency_id" => "BRL",
        "quantity" => 12,
        "unit_price" => 9.90
    );

    $product2 = array(
        "id" => "9012345678",
        "title" => "Product 2 Title",
        "description" => "Product 2 Description",
        "currency_id" => "BRL",
        "quantity" => 5,
        "unit_price" => 19.90
    );

    // Mount the array of products that will integrate the purchase amount
    $items = array($product1, $product2);

    // Retrieve information about the user (use your own function)
    $user = auth()->user();

    $payer = array(
        "name" => $user->name,
        "surname" => $user->surname,
        "email" => $user->email,
    );

    // Create the request object to be sent to the API when the preference is created
    $request = $this->createPreferenceRequest($items, $payer);

    // Instantiate a new Preference Client
    $client = new PreferenceClient();

    try {
        // Send the request that will create the new preference for user's checkout flow
        $preference = $client->create($request);

        // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
        return   redirect($preference->sandbox_init_point);
    } catch (MPApiException $error) {

        return null;
    }
} */

public function pagar(Request $request) {
    $accessToken = 'TEST-3045657775074783-011813-d80b74d2be425de8d9abc56e759d6f7b-1642165427';


    MercadoPagoConfig::setAccessToken($accessToken);











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


        'application_fee' => 0.99,

        'back_url' => 'https://mercadopago.com.br',

    ];
    $responsePlan = $client->create($planData);





    $curl = curl_init();

// Defina as opções da solicitação cURL
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.mercadopago.com/preapproval',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "preapproval_plan_id": "'.$responsePlan->id.'",
        "reason": "'.$responsePlan->reason.'",
        "external_reference": "YG-1234",
        "payer_email": "'.$request->input('email').'",
        "card_token_id": "'.$request->input('token').'",
        "back_url": "https://www.mercadopago.com.br",
        "status": "authorized"
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$accessToken
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$obj = json_decode($response);
dd($obj);





}

public function criar() {

    $response = Http::get('https://graph.instagram.com/me/media', [
        'fields' => 'id,caption,media_url',
        'access_token' => 'IGQWRQaF9QZADVpOU4zd0dPMVBoUTJXTi1NRW9BNXZAQdjh5NlFiSWY4MnhQb052XzFBOEg2YmdEU0REMkZA2ZA0QzMnI0dmNFMHU1TEROWFRTc2NuV3JETWMwZAE81TUtSYjJ6OHlOblZAfSlBsYjg4WG9aVmxmNHY2QjdGQ19OaHJPMXU5ZAwZDZD'
    ]);


    if ($response->successful()) {
        // A solicitação foi bem-sucedida, você pode acessar os dados da resposta
        dd($response->json());
        // Faça o que quiser com os dados aqui...
    } else {
        // A solicitação falhou, você pode lidar com isso aqui
      // Obtenha o código de status HTTP
         dd($response->body()); // Obtenha o corpo da resposta
        // Faça o que quiser com o status e o corpo da resposta...
    }



}

public function atualizarPrecos() {
    $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");
  $preapprovalID = '2c9380849075726001907b4af1280234'; 

/*  $response1 = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'Content-Type'  => 'application/json',
])->put("https://api.mercadopago.com/preapproval_plan/$preapprovalID", [
    'auto_recurring' => [
        'transaction_amount' => 5.00, // Novo preço
        'currency_id' => 'BRL' // Moeda (altere se necessário)
    ]
]); 
dd($response1->json()); 
 */
/* echo "✅ Preapproval Plan atualizado com sucesso!";
$preferenceID = '1644143944-27896130-0574-4b9f-a031-653b4a6349ff';

$response2 = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'Content-Type'  => 'application/json',
])->get("https://api.mercadopago.com/checkout/preferences/$preferenceID", [
    'items' => [
        [
           
            'title'       => 'Barbearia Veia',
            'quantity'    => 1,
            'unit_price'  => 1.00, // Novo preço do produto
            'currency_id' => 'BRL'
        ]
    ]
]);

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'Content-Type' => 'application/json',
])->get("https://api.mercadopago.com/checkout/preferences/{$preferenceID}");


$preco = $response->json();


if($response1->successful() && $response2->successful()) {
    echo "Preço atualizado";
}
else {
    echo "❌ Erro: " . $response1->body() . 'Erro2' . $response2->body();
} */
 $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'Accept' => 'application/json',
])->get("https://api.mercadopago.com/v1/payments/102530924360");
dd($response->json());
}



 public function createPreapprovalRequest(): array
{




    $backUrls = [
        'success' => route('dashboard'),
        'failure' => route('home'),
    ];

    $user = auth()->user();


$accessToken = 'TEST-4528145694266395-011813-76b485df71f80a98e8d91e4c222c02bc-1644184890';
    $createPlanUrl = 'https://api.mercadopago.com/preapproval_plan';

    $planData = [


        'reason' => 'Barbearia',

        'description' => 'Assinatura Mensal',
        'external_reference' => $user->id,
        'auto_recurring' => [
            'frequency' => 1,
            'frequency_type' => 'months',
            'transaction_amount' => 19.99,

            'currency_id' => 'BRL',
            'free_trial' => [
                'frequency' => 1,
                'frequency_type' => 'months',
            ],

        ],


        'application_fee' => 0.99,


        'payer_email' =>    $user->email,

     'back_url' =>  'https://mercadopago.com.br',

     'payment_methods_allowed' => [

        'payment_types' => [
            [
                'id' => 'credit_card',

            ],


        ],

        'payment_methods' => [
            [
                'id' => 'pix',
            ],

        ],
    ],


    ];


$planResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'Content-Type' => 'application/json',
])->post($createPlanUrl, $planData);






$jsonPlanResponse = $planResponse->json();


    return $jsonPlanResponse;
}

public function index()
{
    $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN"); 


   /*  $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->post('https://api.mercadopago.com/checkout/preferences', [
        'items' => [
            [
                'title' => 'Barbearia',
                'description' => 'Barbearia',
                'quantity' => 1,

                'unit_price' => 15,
            ]
        ],

    ]);

    dd($response->json());

    dd($response->json());  */

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Accept' => 'application/json',
    ])->get("https://api.mercadopago.com/preapproval_plan/2c9380849075726001907b4af1280234");

    dd($response->json());

}



  }





