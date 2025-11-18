<?php

namespace App\Livewire\Gerenciar\Agendamentos;

use App\Models\Agendamento;
use Livewire\Component;
use App\Models\Barbearia;
use Carbon\Carbon;
use Livewire\Attributes\{Computed,Url};
use Livewire\WithPagination;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Support\Facades\Http;
use App\Models\Faturas;
use App\Models\BarbeariaUser;
use App\Models\Cliente;
use App\Models\Compras;
use App\Models\Produto;

class Agendar extends Component
{

    use WithPagination;

    public $editarModal;
    public $barbearia;
    public $simpleModal;
    public $barbeiros;
    public $metodoPagamento;
    public $servicosAdd = [];
    public ?BarbeariaUser $editing;
    public $adicionarMetodos;
    public $paymentMethods = [];
    public $produtos = [];
     public ?int $barbeiroSelecionado;
    public $agendamentoSelecionado;
    public ?BarbeariaUser $barbeiro = null;
    public array $formattedDates = [];
    public string $date = '';
  public $opcao;
  public $opcaoCrono;
  public $livewireKey = 'calendario_default';

    public function mount($slug) {
        $this->barbearia = Barbearia::where('slug', $slug)->firstOrFail();


        $this->barbeiros = $this->barbearia->barbeiros()->withTrashed()->first()->id;

       $this->barbeiro =$this->barbearia->barbeiros()->where("id",$this->barbeiros)->first();

       $this->date = Carbon::parse($this->barbearia->barbeiros->where("id", $this->barbeiros)->first()?->agendamentos()->where('start_date', '>=', Carbon::now())->orderBy("start_date", "asc")->first()?->start_date)->format('d-m-Y H:i');



}


 public function updatedBarbeiroSelecionado($value){

    $this->barbeiro =  $this->barbearia->barbeiros()->where("id",$value)->first();

 }

public function selectedAgendamento(Agendamento $agendamento){
    $this->editarModal = true;
    $this->agendamentoSelecionado =$agendamento;

     $this->barbeiroSelecionado =  $this->agendamentoSelecionado->colaborador->id;
     $this->barbeiro = $this->agendamentoSelecionado->colaborador;
}

#[Computed]
public function agendamentosFiltrados()
{


    return $this->barbearia->barbeiros->where("id", $this->barbeiros)->first()?->agendamentos()->whereDate("start_date",Carbon::parse($this->date))->where("pago", 1)->withTrashed()->paginate(10);
}

#[Computed]
public function maquininhasFiltradas()
{
    return $this->barbearia->barbeiros->where("id", $this->barbeiros)->first()?->maquininhas()->get();
}


#[Computed]
public function faturas()
{
    return $this->barbearia->barbeiros->where("id", $this->barbeiros)->first()?->agendamentos()->onlyTrashed()->get();
}




public function edit(BarbeariaUser $barbeiro) {
    $this->editing = $barbeiro;
$this->adicionarMetodos = true;
}






 public function concluir(Agendamento $agendamento) {
    $agendamento->delete();


    $agendamento = Agendamento::withTrashed()->find($agendamento->id);

    $firebaseToken = $agendamento->owner?->token;
   $path = base_path(env('FIREBASE_CREDENTIALS'));

$credentialData = json_decode(file_get_contents($path), true);

$credential = new ServiceAccountCredentials(
    "https://www.googleapis.com/auth/firebase.messaging",
    $credentialData
);
 
 
 
   $token = $credential->fetchAuthToken(HttpHandlerFactory::build());


   try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '. $token['access_token']
    ])->post('https://fcm.googleapis.com/v1/projects/barbearia-agendamento-7fe43/messages:send', [
        "message" => [
            "token" => $firebaseToken,
            "notification" => [
                "title" => "Seu agendamento para " . $agendamento->start_date->format('d/m/Y H:i') . ", foi concluído com sucesso.",
                "body" => "Concluído às: ". ($agendamento->deleted_at ? $agendamento->deleted_at->format('d/m/Y H:i') : 'Não disponível'),
                "image" => env("APP_URL") . "/storage/" . $agendamento->colaborador->barbearia->imagem
            ],
            "webpush" => [
                "fcm_options" => [
                    "link" => env("APP_URL") . "/home?tab=pills-contact8"
                ]
            ]
        ]
    ]);
} catch (\Exception $e) {
  
}




    if ($agendamento->payment_method == 'Cartão de Crédito' && isset($agendamento->maquininha->taxa_credito)) {
        $agendamento->fatura_price = $agendamento->total_price - ($agendamento->maquininha->taxa_credito/100 * $agendamento->total_price);

    } elseif($agendamento->payment_method == 'Cartão de Débito' && isset($agendamento->maquininha->taxa_debito)) {
        $agendamento->fatura_price = $agendamento->total_price - ($agendamento->maquininha->taxa_debito/100 * $agendamento->total_price);
    } else {
       $agendamento->fatura_price = $agendamento->total_price;
    }


               $agendamento->save();
      $cliente = Cliente::where('user_id', $agendamento->owner_id)->first();
      if(!$cliente) {
        $cliente = new Cliente();
        $cliente->user_id = $agendamento->owner_id;
        $cliente->barbearia_id = $this->barbearia->id;
         $cliente->save();
      }




}

public function cancelar($id) {
    $agendamento =  Agendamento::withTrashed()->findOrFail($id);
    $agendamento->restore();
}


public function deletar($id){
    $agendamento =  Agendamento::withTrashed()->findOrFail($id);

    $agendamento->forceDelete();
}

public function editar(Agendamento $agendamento){
    $agendamento = Agendamento::withTrashed()->find($agendamento->id);


        $agendamento->cortes->sync($this->servicosAdd);

         if($this->produtos){
             foreach($this->produtos as $produto){
                  $produto = Produto::where("id",$produto->id);
                   $compra = new Compras();
                   $compra->produto_id = $produto->id;
                   $compra->barbearia_id = $this->barbearia->id;
                    $compra->user_id = $agendamento->user->id;
                    $produto->quantidade -=$this->quantidade;

                    $compra->valor = $produto->preco*$this->quantidade;
             }

         }


         $produto->save();
         $compra->save();




 }
#[Computed]
public function agendamentos()
{
    $barbeiros = $this->barbearia->barbeiros;



 return  \App\Models\Agendamento::query()
        ->whereIn('barbearia_user_id', $barbeiros->pluck('id'))
        ->when($this->option === 'Em breve', function ($query) {
            return $query

                        ->where('status',0);


        })
        ->when($this->option === 'Em atraso', function ($query) {
            return $query

                ->where('end_date', '<', Carbon::now())
                ->where('status', 0);
        })
        ->when($this->option === 'Concluída', function ($query) {
            return $query

                ->where('status', 1);
        })

        ->get();


}

public function adicionarMetodo(BarbeariaUser $barbeiro) {
$barbeiro->payment_methods_allowed = $this->paymentMethods;
$barbeiro->save();
}

public function EventoConcluido($id){
       $evento = Agendamento::findOrFail($id);

       $evento->status = 1;

       $evento->save();


}
    public function render()
    {


        // Converter o array de horários para JSON


        return view('livewire.gerenciar.agendamentos.agendar')->layout('components.layouts.barbearia', [
            'barbearia' => $this->barbearia
        ]);
    }
}
