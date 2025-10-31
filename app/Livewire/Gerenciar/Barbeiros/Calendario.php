<?php

namespace App\Livewire\Gerenciar\Barbeiros;

use App\Models\AddDays;
use Livewire\Component;
use App\Models\Barbeiros;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Agendamento;
use App\Models\BarbeariaUser;
use App\Models\SpecificDate;

class Calendario extends Component
{

    public $barbeiro;
    public $date;
    public $cardModal;
    public $jsonHorarios;
    public $dataDeInicio;
    public $dateFinal;
    public $carbonDate;
    public $carbonDateFinal;
    public $dayOfWeek;
    public $diaAdicionado;
    public $diaRemovido;
    public $horaAtual;
    public $horaFinal;
    public $dentroDosHorarios;
    public $horarios;
    public  $selectedAgendamento;
    public $modalAparecer;
    public $agendamentos = [];
    public $viewType;

 
    protected $listeners = ['opcaoAtualizada' => 'handleOpcaoAtualizada'];

    public function mount($id) {
        $this->barbeiro = BarbeariaUser::whereNull("deleted_at")->where("id", $id)->first();
      

        // Inicializar um array vazio para armazenar os horários de trabalho
        $horarios = [];
        
        // Loop sobre os horários de trabalho do barbeiro
        foreach ($this->barbeiro->workingHours ?? [] as $workingHour) {
            // Adicionar o horário atual ao array de horários
            $horarios[] = [
                'daysOfWeek' => [$workingHour->day_of_week->value],
                'startTime' => $workingHour->start_hour,
                'endTime' => $workingHour->end_hour
            ];
        }
        
        // Converter o array de horários para JSON
        $this->jsonHorarios = $horarios;

    
    }

    public function handleOpcaoAtualizada($value) {
        $this->barbeiro = BarbeariaUser::findOrFail($value);
    }
       
    public function updateEvent($id, $name, $startDate, $endDate){
        $validated = Validator::make(
            [
                'StartDate' => $startDate,
                'EndDate' => $endDate,
            ],
            [
                'StartDate' => 'required',
                'EndDate' => 'required',
            ]
        )->validate();
    
        // Converta as datas para o formato desejado
        $startDate = Carbon::parse($validated['StartDate'])->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($validated['EndDate'])->format('Y-m-d H:i:s');
  
       $agendamento = Agendamento::findOrFail($id);

       $agendamento->start_date = $startDate;
       $agendamento->end_date = $endDate;

       $agendamento->save();
    }
#[On('open-modal')]
    public function abirModal($date, $dateFinal) {
          
        $this->date  =  Carbon::parse($date)->format('Y-m-d H:i:s');
     $this->dateFinal = Carbon::parse($dateFinal)->format('Y-m-d H:i:s');

  
     

    }

    public function aparecerAgendamento(Agendamento $agendamento) {
        $this->selectedAgendamento = $agendamento;
        $this->modalAparecer = true;
    }

    public function add(){
  
        $existingSpecificDate = SpecificDate::where('start_date', Carbon::parse($this->date))
                                             ->where('end_date', Carbon::parse($this->dateFinal))
                                             ->where('barbearia_user_id', $this->barbeiro->id)
                                             ->first();
    
        if($existingSpecificDate) {
            // Se já existir uma entrada, apenas atualize o status para 'adicionar'
            $existingSpecificDate->status = 'adicionar';
            $existingSpecificDate->save();
        } else {
            // Se não existir, crie uma nova entrada
            $specificDate = new SpecificDate;
            $specificDate->start_date = Carbon::parse($this->date);
            $specificDate->barbearia_user_id = $this->barbeiro->id;
            $specificDate->end_date = Carbon::parse($this->dateFinal);
            $specificDate->status = 'adicionar';
            $specificDate->save();
        }
       

 // 🔁 Atualiza os eventos
    $this->atualizarEventos();

    // 🔥 Envia para o front atualizar FullCalendar
    $this->dispatch('refreshCalendarEvents', $this->agendamentos);
     

    }
    public function updateViewType($viewType) {
        $this->viewType = $viewType;
    
    }
  
    private function atualizarEventos()
{
    $this->agendamentos = [];

    // Agendamentos normais
    foreach ($this->barbeiro->agendamentos->where('pago', 1) ?? [] as $agendamento) {
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->end_date);

        $this->agendamentos[] = [
            'id' => $agendamento->id,
            'title' => 'Agendamento',
            'start' => $startDate->toIso8601String(),
            'end' => $endDate->toIso8601String(),
        ];
    }

    // Dias adicionais
    foreach ($this->barbeiro->specificDates()->where('status', 'adicionar')->get() as $specific) {
        $this->agendamentos[] = [
            'start' => Carbon::parse($specific->start_date)->toIso8601String(),
            'end' => Carbon::parse($specific->end_date)->toIso8601String(),
            'display' => 'background',
            'color' => '#c6f6d5', // verde claro opcional
        ];
    }

    // Dias removidos
    foreach ($this->barbeiro->specificDates()->where('status', 'remover')->get() as $specific) {
        $this->agendamentos[] = [
            'start' => Carbon::parse($specific->start_date)->toIso8601String(),
            'end' => Carbon::parse($specific->end_date)->toIso8601String(),
            'display' => 'background',
            'color' => 'lightgrey',
        ];
    }
}

    public function remover() {

        $existingSpecificDate = SpecificDate::where('start_date', Carbon::parse($this->date))
        ->where('end_date', Carbon::parse($this->dateFinal))
        ->where('barbearia_user_id', $this->barbeiro->id)
        ->first();
    
        if($existingSpecificDate) {
          
            $existingSpecificDate->status = 'remover';
            $existingSpecificDate->save();
        } else {
            // Se não existir, crie uma nova entrada
            $specificDate = new SpecificDate;
            $specificDate->start_date = Carbon::parse($this->date);
            $specificDate->barbearia_user_id = $this->barbeiro->id;
            $specificDate->end_date = Carbon::parse($this->dateFinal);
            $specificDate->status = 'remover';
            $specificDate->save();
        }
   
        // 🔁 Atualiza os eventos
    $this->atualizarEventos();

    // 🔥 Envia para o front atualizar FullCalendar
    $this->dispatch('refreshCalendarEvents', $this->agendamentos);
    }
    public function render()
    {
      

        $user = auth()->user();
  

        foreach ($this->barbeiro->agendamentos->where('pago', 1) ?? [] as $agendamento) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->start_date);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->end_date);

    
            $this->agendamentos[] =  [
             
                'id' => $agendamento->id,
                'title' => 'Agendamento',
                'start' => $startDate->toIso8601String(),
                'end' => $endDate->toIso8601String(),
            ];
        }

        
        foreach($this->barbeiro?->specificDates->where('status', 'adicionar') ?? [] as $specific) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $specific->start_date);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $specific->end_date);

            $this->agendamentos[] = [
                'start' => $startDate->toIso8601String(),
                'end' => $endDate->toIso8601String(),
                'display' => 'background',
      
                
             
            
            ];
        }

        foreach($this->barbeiro?->specificDates->where('status', 'remover') ?? [] as $specific) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $specific->start_date);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $specific->end_date);

            $this->agendamentos[] = [
                'start' => $startDate->toIso8601String(),
                'end' => $endDate->toIso8601String(),
                'display' => 'background',
                'color' => 'lightgrey', 
            
            ];
        }
        return view('livewire.gerenciar.barbeiros.calendario');
    }
}
