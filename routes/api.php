<?php

use App\Http\Controllers\BancodoBrasilController;
use App\Models\Barbearia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/criar-pagamento-pix', [BancodoBrasilController::class, 'criarPagamentoPix']);

Route::get('/{barbearia}/clientes', function (Barbearia $barbearia) {
    $clientes = $barbearia->clientes;

 
    $clientesSemUsuario = $clientes->whereNull('user_id')->toArray();


    $usuariosClientes = $clientes->whereNotNull('user_id')->pluck('user')->toArray();

 
    $mergedClientes = $usuariosClientes;
    foreach ($clientesSemUsuario as $cliente) {
        $mergedClientes[] = $cliente;
    }

    return response()->json($mergedClientes);
})->name('api.clientes.index');
Route::get('/{barbearia}/produtos', function (Barbearia $barbearia) {
           $produtos = [];
         $estoques = $barbearia->estoques;
          foreach($estoques as $estoque){
                 foreach($estoque->produtos as $produto){
                        $produtos[] = $produto;
                 }
          }
   

    return response()->json($produtos);
})->name('api.produtos.index');