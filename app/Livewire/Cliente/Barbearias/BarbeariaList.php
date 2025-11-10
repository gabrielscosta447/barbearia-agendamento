<?php


namespace App\Livewire\Cliente\Barbearias;

use Livewire\Component;
use Livewire\Attributes\{Computed, On, Url, Session};
use App\Models\Barbearia;
use App\Models\Favorito;
class BarbeariaList extends Component
{

    public $selectedBarbearia;
    public $favoritoExistente;
    #[Url]
    public $search = '';
    public $latitude;
    public $longitude;
    public $distancia;
    public $opcoesDistancia = [
    ['id' => 10, 'name' => 'Até 10 km'],
    ['id' => 20, 'name' => 'Até 20 km'],
    ['id' => 50, 'name' => 'Até 50 km'],
];
    #[On('setLocation')]
     public function updatedLocation($lat, $lng) {
        $this->latitude = $lat;
        $this->longitude = $lng;
  
    }
    public function compartilhar($barbeariaId) {
        
        $this->selectedBarbearia = Barbearia::findOrFail($barbeariaId);
    }
public function updatedDistancia($value)
{  
     $this->distancia = $value;
     
    if(empty($this->distancia)){
        return Barbearia::all();
    }
        // Pega todas as barbearias
        $barbearias = Barbearia::all();
        // Filtra pela distância em PHP
        $barbearias = $barbearias->filter(function($barbearia) {
            $dist = $this->calcularDistancia(
                $this->latitude,
                $this->longitude,
                $barbearia->latitude,
                $barbearia->longitude
            );
            return $dist <= $this->distancia;
        });

        

        // Ordena pelo valor da distância
        $barbearias = $barbearias->sortBy(function($barbearia) {
            return $this->calcularDistancia(
                $this->latitude,
                $this->longitude,
                $barbearia->latitude,
                $barbearia->longitude
            );
        })->values();
   
     $this->barbeariasordenadas = $barbearias;
    
}
   
  #[Computed]
public function barbeariasordenadas($localizacaoAtual = false)
{
   
   if($this->search) {
    return Barbearia::where('nome', 'like', '%' . $this->search . '%')
        ->orWhere('cidade', 'like', '%' . $this->search . '%')
        ->get();
   } else {
    return Barbearia::all();
   }
}

private function calcularDistancia($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6371; // km

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $earthRadius * $c;
}

    public function render()
    {
        return view('livewire.cliente.barbearias.barbearia-list');
    }
}
