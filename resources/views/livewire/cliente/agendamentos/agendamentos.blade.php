<div >

    
  
    
  
  <div class="mb-6">
    <div class="fixed top-50   w-full bg-[#FBFBFB] shadow-md z-10">
      <div class="flex flex-wrap gap-y-4 justify-between p-3 lg:p-5">
        <div class="text-left">
          <h1 class="mr-6 text-2xl lg:text-4xl font-bold tracking-tight text-gray-900">Agendamentos</h1>
        </div>
    
        <div class="text-right flex flex-wrap items-center gap-4">
          <x-radio id="md" label="Passados" lg wire:model.change="option" value="passado" />
          <x-radio id="md" label="Futuros" lg wire:model.change="option"  value="futuro" />
          <x-radio id="md" label="Concluídos" lg wire:model.change="option" value="concluido" />
        </div>
      </div>
     
    </div>

  
      <div class="grid  pt-[100px] grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 p-5 grid-cols-custom m-auto  ">
        
    
      
      
    @foreach($this->agendamentos as $agendamento )
    


    <div
    
    class="relative flex max-w-[30rem] flex-col overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
    <a href="/{{ $agendamento->colaborador->barbearia->slug }}"    data-te-ripple-init
      data-te-ripple-color="light" wire:navigate class="relative m-0 overflow-hidden text-gray-700 bg-transparent rounded-none shadow-none bg-clip-border">
      <img
        src="http://localhost:8000/storage/{{ $agendamento->colaborador->barbearia->imagem }}"
        class="w-full h-[317px] object-cover"
        alt="ui/ux review check" />
    </a>
    <div class="p-6">
      <h4 class="block font-sans text-2xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
         {{ \Carbon\Carbon::parse($agendamento->start_date)->format('d/m/Y H:i') }}
      </h4>
      <p class="block mt-3 font-sans text-xl antialiased font-normal leading-relaxed text-gray-700">
     Cortes:@foreach($agendamento->cortes()->withTrashed()->get() as $corte) {{ $corte->corte->nome }}@if(!$loop->last),@else.@endif    @endforeach

      </p>
      <p class="block mt-1 font-sans text-xl antialiased font-normal leading-relaxed text-gray-700">
        @php
        // Definir o idioma do Carbon para português
        \Carbon\Carbon::setLocale('pt');
    @endphp
        Duração: {{ Carbon\Carbon::parse($agendamento->start_date)->diffForHumans(Carbon\Carbon::parse($agendamento->end_date), true) }}
  
         </p>
         <p class="block mt-1 font-sans text-xl antialiased font-normal leading-relaxed text-gray-700">
      
          Barbearia: {{ ucfirst($agendamento->colaborador->barbearia->nome) }}
    
           </p>
    </div>
    <div class="flex items-center justify-between p-6">
      <div class="flex items-center -space-x-3">
        
        <img alt="natali craig"
        src="{{ $agendamento->colaborador->user->profile_photo_url }}"
          class="relative inline-block h-9 w-9 !rounded-full  border-2 border-white object-cover object-center hover:z-10" />

      </div>
      @if(!$agendamento->trashed())
  
    
      <x-button   class="rounded bg-black px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-900 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-black active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]" black label="Editar" spinner="edit({{ $agendamento->id }})" 
        spinner="abrirModal({{ $agendamento->id }})" wire:click="abrirModal({{ $agendamento->id }})" />

      @endif
    </div>
    
  </div> 
@endforeach

      </div>
      <div class="flex items-center justify-center">
      {{ $this->agendamentos?->links() }}
    </div>
      <x-modal.card title="Editar Agendamento" blur  wire:model="agendamentoModal"  x-on:agendamento-editado.window="close" x-on:close="$wire.limpar()">
        @if($selectedAgendamento)
        <div class="flex flex-col gap-4 mb-3" wire:key="view-{{ $selectedAgendamento->id }}">
       
       
      
      @foreach($selectedAgendamento->colaborador->cortes()->withTrashed()->get() as $corte)
     
          <x-checkbox
              md
              id="color-secondary"
              secondary
              label="{{ $corte->corte->nome }} - R${{ $corte->corte->preco }}"
              wire:model="cortes.{{ $this->selectedAgendamento->id }}"
              class="mb-3"
              value="{{ $corte->id }}"
              autocomplete="off"
          />
         
      @endforeach
        </div>


      
        <livewire:cliente.agendamentos.date-picker  wire:model="date" :barbeiroSelecionado="$selectedAgendamento->colaborador" :key="$selectedAgendamento->id" :selectedAgendamento="$selectedAgendamento" />
        @if(session('error'))
        <div x-data="{ isOpen: true }" x-on:mostrar.window="isOpen = true" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-5" role="alert">
            <strong class="font-bold">Erro</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <span @click="isOpen = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>
        @endif
           
              <x-slot name="footer">
                  <div class="flex justify-between gap-x-4">
                      <x-button flat negative label="Deletar" spinner="delete({{ $selectedAgendamento->id }})" wire:click="delete({{ $selectedAgendamento->id }})" />
           
                      <div class="flex">
                          <x-button flat label="Cancelar" x-on:click="close" />
                          <x-button primary label="Editar"  spinner="editar({{ $selectedAgendamento->id }})" x-on:click="$wire.editar({{ $selectedAgendamento->id }})"  />
                      </div>
                  </div>
              </x-slot>
              @endif
          </x-modal.card>

  </div>