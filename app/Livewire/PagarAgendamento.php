<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agendamento;
use App\Http\Service\BancoDoBrasilService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PagarAgendamento extends Component
{
    public $agendamento;
    public $qrCodePix;
    public $pixCopiaECola;
    public $copiado = false;
    public $preco;
    public $expiraEm;

    public function mount($id) {
        $this->agendamento = Agendamento::find($id);
  
        $this->qrCodePix = $this->agendamento->id_pix;
        $this->pixCopiaECola = $this->agendamento->payload;
        $this->preco = $this->agendamento->total_price;
        $this->expiraEm = now()->parse($this->agendamento->created_at)->addHour()->timestamp * 1000;
    
    }
   
    public function voltar() {
        return $this->redirect('/home?tab=pills-contact8', navigate: true);
    }
    public function render()
    {
        return view('livewire.pagar-agendamento');
    }
}
