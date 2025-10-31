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
    public function mount($id) {
        $this->agendamento = Agendamento::find($id);
        $bancoDoBrasilService = new BancoDoBrasilService();
        $response = $bancoDoBrasilService->obterPix($this->agendamento->id_pix);
        $pixCopiaECola = $response['pixCopiaECola'];
        $this->qrCodePix = base64_encode(QrCode::format('png')->size(250)->generate($pixCopiaECola));
        $this->pixCopiaECola = $pixCopiaECola;
    }
    public function copiarPix() {
        $this->dispatch('copiarPix', $this->pixCopiaECola);
        $this->copiado = true;
    }
    public function render()
    {
        return view('livewire.pagar-agendamento');
    }
}
