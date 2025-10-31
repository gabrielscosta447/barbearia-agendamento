<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Service\BancoDoBrasilService;


class BancodoBrasilController extends Controller
{

    public function criarPagamentoPix(Request $request)
    {

        $valor = $request->input('valor');

        $bancoDoBrasilService = new BancoDoBrasilService();
        $response = $bancoDoBrasilService->criarPagamentoPix($valor);

        return $response->body();
    }
  
}