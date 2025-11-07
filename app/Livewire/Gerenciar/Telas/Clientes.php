<?php

namespace App\Livewire\Gerenciar\Telas;

use App\Models\Agendamento;
use App\Models\Barbearia;
use App\Models\Cliente;
use App\Models\UserCorte;
use Carbon\Carbon;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Clientes extends Component
{
        public $barbearia;

        public $nome;

        public $cliente;

        public $clienteSelecionado;

        public ?Cliente $editing;

    public function mount($slug) {
        $this->barbearia = Barbearia::where('slug', $slug)->first();
        $this->clienteSelecionado = $this->barbearia->clientes->first()?->id;

  }

  public function edit($id) {

    $this->dispatch('abrir-modal', $id);
  }




       public function adicionarCliente(){
                        $cliente = new Cliente();

                        $cliente->name = $this->nome;
                       $cliente->barbearia_id =  $this->barbearia->id;

                       $cliente->save();
       }

       public function delete(Cliente $cliente) {
        $cliente->delete();
       }

       #[Computed()]
  public function clientes(){
           return  $this->barbearia->clientes;
  }

       public function deletarCliente($id){
                       $cliente =  Cliente::findOrFail($id);

                       $cliente->delete();
       }







#[Computed]
#[On('refrigerar')]
public function agendamentosFiltrados()
{
    return $this->barbearia->clientes->where("id", $this->clienteSelecionado)->first()?->agendamentos()->withTrashed()->paginate(10);
}



       private function saveAgendamento($agendamento, $end_date_clone)
 {
     $agendamento->end_date = $end_date_clone;
     $agendamento->save();
     $agendamento->cortes()->attach($this->cortes);

     $userId = auth()->id();
     $cacheKey = "agendamentos_{$userId}";

     // Limpar o cache para a chave específica
     Cache::forget($cacheKey);
     $this->dispatch('agendamento-salvo');
     session()->flash('status', 'Post successfully updated.');
 }

 public function concluir(Agendamento $agendamento) {
    $agendamento->delete();


    $agendamento = Agendamento::withTrashed()->find($agendamento->id);

    $firebaseToken = $agendamento->owner?->token;
    $pvKeyPath = env('FIREBASE_CREDENTIALS_JSON');
    $credential = new ServiceAccountCredentials(
       "https://www.googleapis.com/auth/firebase.messaging",
       json_decode(env('FIREBASE_CREDENTIALS_JSON'), true)
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
                "image" => "http://localhost:8000/storage/" . $agendamento->colaborador->barbearia->imagem
            ],
            "webpush" => [
                "fcm_options" => [
                    "link" => "http://localhost:8000/home?tab=pills-contact8"
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
    public function render()
    {
        return view('livewire.gerenciar.telas.clientes')->layout('components.layouts.barbearia', [
            'barbearia' => $this->barbearia,

        ]);
    }




}
