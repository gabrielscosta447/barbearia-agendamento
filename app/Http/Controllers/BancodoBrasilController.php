<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Service\BancoDoBrasilService;


class BancodoBrasilController extends Controller
{

    public function criarPagamentoPix()
    {

        $bancoDoBrasilService = new BancoDoBrasilService();
        $response = $bancoDoBrasilService->criarPagamentoPix();

        return response()->json($response);
    }
  
}