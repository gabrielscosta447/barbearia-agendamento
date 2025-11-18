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
    public function mount($id) {
        $this->agendamento = Agendamento::find($id);
  
        $this->qrCodePix = $this->agendamento->id_pix;
        $this->pixCopiaECola = $this->agendamento->payload;
        $this->preco = $this->agendamento->total_price;
        
    
    }
   
    public function voltar() {
        return $this->redirect('/home?tab=pills-contact8', navigate: true);
    }
    public function render()
    {
        return view('livewire.pagar-agendamento');
    }
}
