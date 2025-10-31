<div>






<x-modal.card title="Editar Dia" blur wire:model="cardModal" x-on:open-modal.window="open">
{{--     @if($this->date)
    @php
        $carbonDate = \Carbon\Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->format('N');

        $diasDaSemana = [
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado',
            '7' => 'Domingo',
        ];

        $trabalhaNesseDia = in_array($diasDaSemana[$dayOfWeek], $barbeiro->workingHours->pluck("day_of_week")->toArray());

        $diaAdicionado = $barbeiro->specificDates()
                                ->where('status', 'adicionar')->get()->isNotEmpty();
                                
             
        $diaRemovido = $barbeiro->specificDates()
                                ->where('status', 'remover')->where('start_date',$this->date)->get()->isNotEmpty();
                              
            $diaAdicional = $barbeiro->specificDates()
                                ->where('start_date',$this->date)->get()->isEmpty();
             
            $diaIgual = $barbeiro->specificDates()
                                ->where('start_date',$this->date )->get()->isNotEmpty();
                            
                                
    @endphp

    @if(  $diaAdicional || $diaRemovido )
        <button
            type="button"
            wire:click="add"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Adicionar dia de Trabalho
        </button>
    @elseif($diaIgual  )
        <button
            type="button"
            wire:click="remover"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Remover dia de trabalho
        </button>
    @endif
@endif --}}

@if($this->date)
    @php


        $carbonDate = \Carbon\Carbon::parse($this->date);
        $carbonDateFinal = \Carbon\Carbon::parse($this->dateFinal);
        $dayOfWeek = ((int)$carbonDate->format('N')) % 7;


        $diaAdicionado = $barbeiro->specificDates()
                                ->where('status', 'adicionar')
                                ->where(function ($query) use ($carbonDate, $carbonDateFinal) {
                                    $query->where('start_date', '>=', $carbonDate)
                                          ->where('end_date', '<=',  $carbonDateFinal);
                                })
                                ->exists();

                            

        // Verifica se existe uma data específica removida para esse dia
        $diaRemovido = $barbeiro->specificDates()
                                ->where('status', 'remover')
                                ->where(function ($query) use ($carbonDate, $carbonDateFinal) {
                                    $query->where('start_date', '>=', $carbonDate)
                                          ->where('end_date', '<=', $carbonDateFinal);
                                })
                                ->exists();

        // Verifica se o horário atual está dentro dos horários de trabalho específicos
        $horaAtual = \Carbon\Carbon::parse($this->date)->format('H:i:s');
        $horaFinal = \Carbon\Carbon::parse($this->dateFinal)->format('H:i:s');
       
    
   
        $dentroDosHorarios = $barbeiro->workingHours()
                                      ->where('day_of_week', $dayOfWeek)
                                      ->where('start_hour', '<=', $horaAtual)
                                      ->where('end_hour', '>=', $horaFinal)
                                      ->exists();
                $horarios =  $barbeiro->workingHours()->get();
    @endphp
 @if($this->viewType == 'dayGridDay' || $this->viewType == 'timeGridDay' || $this->viewType == 'timeGridWeek')
    @if($dentroDosHorarios == false )
        
         @if($diaAdicionado == true)
         <button
            type="button"
            wire:click="remover"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Remover dia de trabalho
        </button>
        @endif

        <button
        type="button"
        wire:click="add"
        class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
        Adicionar dia de Trabalho
        </button>
    @elseif($dentroDosHorarios == true )
            @if($diaRemovido == true)
            <button
            type="button"
            wire:click="add"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Adicionar dia de Trabalho
            </button>
            @endif
        <button
            type="button"
            wire:click="remover"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Remover dia de trabalho
        </button>
    @endif
    @elseif($this->viewType === 'dayGridMonth')
    <p class="text-gray-600">Mude para a visualização diária ou semanal para editar dias específicos.</p>
@else
    <p class="text-gray-600">Mude para a visualização diária ou semanal para editar dias específicos.</p>   
@endif
@endif

</x-modal.card>


<div  x-data="bob" x-init="initCalendar($refs.calendar)" wire:ignore  >
    <div x-ref="calendar"  class="w-full mx-auto"></div>
</div>
@assets
<!-- 1️⃣ Carrega Moment e Moment-Timezone primeiro -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.43/builds/moment-timezone-with-data.min.js"></script>

<!-- 2️⃣ Agora sim o FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- 3️⃣ Plugins do FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/moment@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/moment-timezone@6.1.9/index.global.min.js"></script>

<!-- 4️⃣ Todas as traduções -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/locales/pt-br.global.min.js"></script>
 @endassets
@script
    <script>
 Alpine.data('bob', () => ({
    date: " ",
    calendar: null, // guarda o calendário para acessar depois

    initCalendar(calendarEl) {
        if (typeof moment !== 'undefined' && moment.tz) {
            moment.tz.setDefault('America/Fortaleza');
        } else {
            console.error("Moment Timezone is not available.");
            return;
        }

        // Cria o calendário e guarda em this.calendar
        this.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            timeZone: 'America/Fortaleza',
            editable: true,
            events: @json($agendamentos),
            selectable: true,
            locale: 'pt-br',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },

            select: (data) => {
               const start = moment.tz(data.start, 'America/Fortaleza').format('YYYY-MM-DD HH:mm:ss');
    const end = moment.tz(data.end, 'America/Fortaleza').format('YYYY-MM-DD HH:mm:ss');
    
    Livewire.dispatch('open-modal', { date: start, dateFinal: end });
            },
            datesSet: (info) => {
                @this.updateViewType(info.view.type);
            },
            eventDrop: (data) => {
                @this.updateEvent(
                    data.event.id,
                    data.event.name,
                    data.event.start.toISOString(),
                    data.event.end.toISOString(),
                );
            },
            eventClick: (data) => {
                @this.aparecerAgendamento(data.event.id);
            },
            businessHours: @json($jsonHorarios)
        });

        this.calendar.render();

      Livewire.on('refreshCalendarEvents', (eventos) => {
       console.log("Atualizando eventos do calendário:", eventos);
    this.calendar.removeAllEvents();
   this.calendar.addEventSource(eventos[0]);
this.calendar.refetchEvents();
    
});

    }
}));

    </script>
  @endscript
  <x-modal wire:model.defer="modalAparecer">
    @if($this->selectedAgendamento)
    <x-card title="{{ $this->selectedAgendamento?->start_date }}">
       
        <p class="text-gray-600">
        Usuário: {{ $this->selectedAgendamento->owner->name }}
    </p>
    <p class="text-gray-600">
        Telefone: 
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '',55 . $this->selectedAgendamento->owner->phone) }}" target="_blank" class="text-blue-500">
            {{ $this->selectedAgendamento->owner->phone }}
        </a>
    </p>
     
 
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="I Agree" />
            </div>
        </x-slot>
    </x-card>
    @endif
</x-modal>

</div>
