<?php
namespace App\Http\Service;

use Illuminate\Support\Facades\Http;


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
        $basic = base64_encode($clientId . ':' . $clientSecret);

        $form = [
            'grant_type' => 'client_credentials',
        ];

        if ($scope !== null) {
            $form['scope'] = $scope;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $basic,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://oauth.bb.com.br/oauth/token', $form);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['access_token'])) {
                $this->accessToken = $data['access_token'];
                return $this->accessToken;
            }
            throw new \Exception('Token não encontrado na resposta do Banco do Brasil');
        }

        throw new \Exception('Erro ao autenticar com o Banco do Brasil: ' . $response->body());
    }


   public function criarPagamentoPix()
    {
       $form = [
            'calendario' => [
                'expiracao' => 3600
            ],

            "devedor" => [
                "nome" => "Nome do Devedor",
                "cpf" => "12345678909",
                "cnpj" => "12345678000195"
            ],

         "valor" => [
            "original" => "100.00"
         ],
         "chave" => "seu-pix-chave-aqui"

        ];


        $accessToken = $this->authenticate($this->clientId, $this->clientSecret, 'cob.read cob.write pix.read pix.write');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api-pix.hm.bb.com.br/pix/v2/cob', $form);


        return $response;
    }
       
 
}