<?php
namespace App\Http\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class BancoDoBrasilService
{

    public $accessToken;
    public function __construct()
    {
       $this->access_token = env('PIX_ACCESS_TOKEN');
    }
  


   public function criarPagamentoPix($valor, $agendamentoId)
    {
     

        $chavePix = "42cc282f-bc71-43b6-b00f-9b29361afb54";

        $formData = [
            "addressKey" => $chavePix,
            "value" => number_format($valor, 2, '.', ''),
            "externalReference" => $agendamentoId
        ];

       
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' =>  $this->access_token,
            'content-type' => 'application/json',
        ])->post( env('PIX_BASE_URL') . 'pix/qrCodes/static/' , $formData);

         
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