<?php

use App\Http\Controllers\InstagramController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Cliente\Telas\Teste;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Livewire\Gerenciar\Agendamentos\Agendar;
use App\Livewire\Cliente\Telas\BarbeariaView;
use App\Livewire\Gerenciar\Telas\Gerenciar;
use App\Livewire\Gerenciar\Barbeiros\Horarios;
use App\Livewire\Agendamentos;
use App\Livewire\LandingPage;
use App\Livewire\Gerenciar\Telas\Clientes;
use App\Livewire\Gerenciar\Telas\Colaborador;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BarbeariaController;
use App\Http\Controllers\MercadoPago;
use App\Http\Controllers\Webhooks;
use App\Livewire\Gerenciar\Barbeiros\Calendario;
use App\Livewire\Subscribe;
use App\Livewire\PagarAgendamento;

use App\Livewire\Plano;
use App\Livewire\Gerenciar\Telas\Services;
use App\Jobs\VerificarPagamento;
use App\Livewire\ComprasUsuario;
use App\Livewire\EstoqueProduto;
use App\Livewire\Gerenciar\Telas\Promocoes;
use App\Http\Controllers\TokenController;

use App\Http\Controllers\BancodoBrasilController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('/webhooks',[Webhooks::class, 'webhook'])->name('webhook');


Route::view('view', 'view');
Route::get('/barbearia/{nome}', [BarbeariaController::class, 'buscarPorNome']);



Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::get('/byebye', [MercadoPago::class, 'index']);




Route::get('/criar-plano', [MercadoPago::class, 'criar']);
Route::get('/pagar/{id}', PagarAgendamento::class);

Route::get('/instagram', [InstagramController::class, 'instagram']);
Route::get('/mercadopagoprecos',[MercadoPago::class, 'atualizarPrecos']);




Route::post('/nova', [TokenController::class, 'store'])->middleware('auth');
Route::get('/auth/{provider}/redirect', function(string $provider) {
      return Socialite::driver($provider)->redirect();
})->middleware("guest");

Route::get('/auth/{provider}/callback', function(string $provider) {
       $providerUser = Socialite::driver($provider)->user();

       $user = User::updateOrCreate([
        'provider_id' => $providerUser->id,
        
       ], [
        'name' => $providerUser->name,
        'email' => $providerUser->email,
        'provider_avatar' => $providerUser->avatar,
        'provider_name' => $provider,
       ]);

 

       Auth::login($user);

       return redirect('/home');

})->middleware("guest");




Route::prefix('gerenciar/{slug}')->group(function () {
    Route::middleware("admin")->group(function () {

  
    Route::get('/', Gerenciar::class)->name('gerenciar');
    Route::get('/agendamentos', Agendar::class)->name('barbearia.agendamentos');
    Route::get('/horarios', Horarios::class)->name('horarios');
    Route::get('/horarios/calendario/{id}', Calendario::class)->name('barbeiro.calendario'); 
    Route::get('/billing', Colaborador::class)->name('barbearia.billing');

    Route::get('/services', Services::class)->name('barbearia.services');
    Route::get('/clientes', Clientes::class)->name('barbearia.clientes');
    Route::get('/promocoes', Promocoes::class)->name('barbearia.promocoes');
    Route::get('/estoqueCompras', EstoqueProduto::class)->name('barbearia.estoqueCompras');
    Route::get('/compras', ComprasUsuario::class)->name('barbearia.compras');
});
 
})->middleware("auth");

Route::get('landing', LandingPage::class)->name('landing');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', Teste::class)->name('home');
    Route::get('/{slug}',BarbeariaView::class);
 
});








Route::post('/processar-pagamento/{orderId}/{barbeiroId}',[OrderController::class,'webhook']);


