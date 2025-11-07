<?php

namespace App\Livewire\Cliente\Agendamentos;

use Livewire\Component;
use App\Models\Agendamento;
use Livewire\Attributes\{On, Computed, Validate, Url};
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\UserCorte;
use App\Models\Cortes;
use Livewire\WithPagination;


class Agendamentos extends Component
{

    use WithPagination;

    public ?Agendamento $editing = null;
    public string $date = '';

  
public $agendamentoModal;
public ?Agendamento $selectedAgendamento = null;
public $allAgendamentos;
public $cortes = [];
public $options = [];
public $barbeiroSelecionado;
public $formattedDates;
public string $option = "futuro";


public function mount() {


 
}

   
    public function edit(Agendamento $agendamento): void
     {
        $this->editing = $agendamento;


      

    

      
      
    }

    public function delete($id) {
  

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->forceDelete();
        
        $this->dispatch('refresh');
      
    }

     public function pay($id) {
       
        return $this->redirect('/pagar/'.$id, navigate: true);
    }

    public function editar($id)
    {
      
        if($this->selectedAgendamento) {

       
        $this->validate([
            'cortes.' . $id => 'filled',
        ], [
            'cortes.' . $id . '.filled' => 'É necessário preencher os cortes desse agendamento.',
        ]);
        
        $evento = Agendamento::findOrFail($id);
        $this->authorize('update', $evento);

        $startDate = Carbon::parse($this->date);
        if ($startDate->isPast()) {
            session()->flash('error', 'Não é possível agendar um horário no passado.');
            $this->dispatch('mostrar');
            return;
        }
        
        $intervalInMinutesTotal = 0; 
      
        foreach ($this->cortes[$id] as $corteId) {
            $corteSelecionado = UserCorte::
            findOrFail($corteId)
            ->corte;
            $intervalInMinutesTotal += $this->convertTimeToMinutes($corteSelecionado->intervalo);
        }
    
        $end_date_clone = Carbon::parse($this->date)->clone()->addMinutes($intervalInMinutesTotal);
    
        $this->barbeiroSelecionado = $this->selectedAgendamento->colaborador;
        $eventosAgendados = $this->barbeiroSelecionado->agendamentos;
  
   
        foreach ($eventosAgendados as $appointment) {
            if ($this->selectedAgendamento && $appointment->id === $this->selectedAgendamento->id) {
                 continue;
            }
    
            $existingStartTime = Carbon::parse($appointment->start_date);
            $existingEndTime = Carbon::parse($appointment->end_date);
            
            if (Carbon::parse($this->date) < $existingEndTime && $end_date_clone > $existingStartTime) {
                session()->flash('error', 'Tente diminuir o número de cortes, pois o seu agendamento esta sobrepondo horários já agendados.');
                $this->dispatch('mostrar');
                return false;
            }
        }
        $removedDates = $this->barbeiroSelecionado->specificDates()
        ->where('status', 'remover')
       ->whereDate('start_date', Carbon::parse($this->date)->format('Y-m-d'))
        ->get();
   
        foreach ($removedDates as $removedDate) {
           $startHorarioRemovido = Carbon::parse($removedDate->start_date);
           $endHorarioRemovido = Carbon::parse($removedDate->end_date);
   
           if (Carbon::parse($this->date) < $endHorarioRemovido && $end_date_clone > $startHorarioRemovido) {
          
               session()->flash('error', 'Tente diminuir o número de cortes, pois o seu agendamento esta sobrepondo a horários removidos pelo barbeiro.');
               $this->dispatch('mostrar');
               return false;
           }
       }
    
       $availableTimes = $this->barbeiroSelecionado->getAllAvailableTimes($this->date, $this->selectedAgendamento);

       // Filtrar apenas os horários disponíveis sem cor atribuída
       $availableTimesWithoutColor = array_filter($availableTimes, function($availableTime) {
        return $availableTime['color'] === '' || $availableTime['color'] === 'black';
    });

       
       $selectedDateTime = Carbon::createFromFormat('d-m-Y H:i', $this->date);
       $isTimeAvailable = false;
   
       foreach ($availableTimesWithoutColor as $availableTime) {
           $availableDateTime = $availableTime['time'];
   
           // Verifica se o horário selecionado corresponde a um dos horários disponíveis
           if ($selectedDateTime->eq($availableDateTime)) {
               $isTimeAvailable = true;
               break;
           }
       }
   
       if (!$isTimeAvailable) {
           session()->flash('error', 'O horário selecionado não está disponível.');
           $this->dispatch('mostrar');
           return false;
       }
   
       if ($this->barbeiroSelecionado->isEndTimeExceeded($this->date, $end_date_clone)) {
           session()->flash('error', 'O horário final selecionado ultrapassa o término do expediente da barbearia.');
           $this->dispatch('mostrar');
           return false;
       }
       
   
    
       $total = 0;
       foreach($this->cortes[$id] as $corte){
                  
                  $corteSelecionado = Cortes::findOrfail($corte);
                  $total += $corteSelecionado->preco;

       }
           $evento->total_price = $total;
        // Atualizar o agendamento
        $evento->cortes()->sync($this->cortes[$id]);
        $evento->start_date = Carbon::createFromFormat('d-m-Y H:i', $this->date);
        $evento->end_date = $end_date_clone;
        $evento->save();
    
        $this->limparCacheAgendamentos();
        $this->dispatch('agendamento-editado');
    }
    }

  

    public function abrirModal($agendamentoId) {

   
        
    
        
        $this->selectedAgendamento = Agendamento::findOrFail($agendamentoId);

    
   
        $this->date = \Carbon\Carbon::parse($this->selectedAgendamento->start_date)->format('d-m-Y H:i');
    
       
        $this->cortes[$agendamentoId] = $this->selectedAgendamento->cortes()->withTrashed()->pluck('user_corte.id')->toArray();
    
      
           
        $this->agendamentoModal = true;
        
         

    }
   
 

 
    
  #[Computed]
public function agendamentos() {
    $now = now();

    //Lógica para não aparecer os eventos cancelados para o usuário
    $query = auth()->user()->eventos()->orderBy('created_at', 'desc');
 

    switch ($this->option) {
        case 'concluido':
            $query->onlyTrashed()->where('pago', 1);
            break;
        case 'passado':
            $query->where('start_date', '<', $now)->where('pago', 1);
            break;
        case 'futuro':
            $query->where('start_date', '>', $now)->where('pago', 1);
            break;
         case 'pendente':
                $query->where('pago', 0);
                break;
        default:
            return collect(); // Retorna uma coleção vazia se a opção for desconhecida
    }

    return $query->get();
}

    public function limpar() {
   
        $this->selectedAgendamento = null;
 
        
    }

    private function convertTimeToMinutes($time)
    {
        list($hours, $minutes, $seconds) = explode(':', $time);
    
        return $hours * 60 + $minutes + $seconds / 60;
    }
   
#[On('agendamento-edit-canceled')]
  public function disableEditing() {
    $this->editing = null;
  }

  private function limparCacheAgendamentos()
  {
      $userId = auth()->id();
      $cacheKey = "agendamentos_{$userId}";
  
 
      Cache::forget($cacheKey);
  }
    public function render()
    {
      
        return view('livewire.cliente.agendamentos.agendamentos');
    }
}
