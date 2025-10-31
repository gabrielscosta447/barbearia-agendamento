<?php
namespace App\Http\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class BancoDoBrasilService
{
    public $clientId;
    public $clientSecret;
    public $accessToken;
    public function __construct()
    {
        $this->clientId = env('BANCO_DO_BRASIL_CLIENT_ID');
        $this->clientSecret = env('BANCO_DO_BRASIL_CLIENT_SECRET');
    }
  

    /**
     * Request OAuth token from Banco do Brasil using client_credentials grant.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string|null $scope
     * @return string
     * @throws \Exception
     */
    public function authenticate(string $clientId, string $clientSecret, ?string $scope = null): string
    {

        $form = [
            'grant_type' => 'client_credentials',
        ];

        if ($scope !== null) {
            $form['scope'] = $scope;
        }
      if(Cache::has('banco_do_brasil_access_token')  ) {
           return Cache::get('banco_do_brasil_access_token');
       }else{
        $response =  Http::asForm() // igual ao "x-www-form-urlencoded" do Postman
            ->withBasicAuth($clientId, $clientSecret)
            ->post(env('BANCO_DO_BRASIL_BASE_URL'), [
                'grant_type' => 'client_credentials',
                'scope' => 'cob.read cob.write pix.read pix.write',
            ]);
    
       
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['access_token'])) {
                $this->accessToken = $data['access_token'];
                Cache::put('banco_do_brasil_access_token', $this->accessToken, now()->addMinutes(10));
                return $this->accessToken;
            }
            throw new \Exception('Token não encontrado na resposta do Banco do Brasil');
        }
    }

        throw new \Exception('Erro ao autenticar com o Banco do Brasil: ' . $response->body());
    }


   public function criarPagamentoPix($valor)
    {
       $form = [
            'calendario' => [
                'expiracao' => 3600
            ],

            

         "valor" => [
            "original" => $valor
         ],
         "chave" => "hmtestes2@bb.com.br"

        ];


        $accessToken = $this->authenticate($this->clientId, $this->clientSecret);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.hm.bb.com.br/pix/v2/cob?gw-dev-app-key=' . env('BANCO_DO_BRASIL_APP_KEY'), $form);


        return $response->json();
    }

    public function obterPix($txid) {
        $accessToken = $this->authenticate($this->clientId, $this->clientSecret);

        $response = Http::withHeaders([
             'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get('https://api.hm.bb.com.br/pix/v2/cob/' . $txid . '?gw-dev-app-key=' . env('BANCO_DO_BRASIL_APP_KEY'));

        return $response->json();
    }
       
 
}