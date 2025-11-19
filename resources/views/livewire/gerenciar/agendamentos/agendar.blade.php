
<div>
<div class="w-full px-6 py-6 mx-auto">

    
  <!-- table 1 -->
  <x-modal.card title="Adicionar Compra" blur wire:model="editarModal">

    <x-select
    label="Selecionar Barbeiro"
    placeholder="Selecionar Barbeiro"
    wire:model.blur="barbeiroSelecionado"
    
    class="mb-3"
    autocomplete="off"
  >
  
  @foreach($this->barbearia->barbeiros as $barbeiro)
  
    <x-select.user-option src="{{ $barbeiro->user->profile_photo_url }}" label="{{ $barbeiro->user->name }}" value="{{ $barbeiro->id }}" />
  
    @endforeach
  
        
   
      
  </x-select>
           @if($barbeiroSelecionado)

           <x-select
           label="Selecionar Corte"
           multiselect
           placeholder="Selecionar Corte"
           wire:model="cortes"
           
           class="mb-3"
           autocomplete="off"
           >
           @foreach($this->barbeiro->cortes as $corte)
           <x-select.user-option src="{{ $this->barbeiro->user->profile_photo_url }}" label="{{ $corte->nome }} - R${{ $corte->preco }}" value="{{ $corte->id }}" />
           
           @endforeach
           </x-select>
           <x-select
           label="Método de Pagamento"
        
           placeholder="Selecionar Método de Pagamento"
           wire:model="paymentMethod"
           :options="$this->barbeiro->payment_methods_allowed"
           class="mb-3"
           autocomplete="off"
       />
     
      
           
           <livewire:cliente.agendamentos.date-picker  wire:model="date" :formattedDates="$formattedDates"  :barbeiroSelecionado="$barbeiro" :key="$barbeiro->id" />
           @endif
  </x-modal.card>

    

  @if($this->opcao == "cronograma")
      <livewire:gerenciar.barbeiros.calendario  wire:ignore :id="$barbeiros" />
  @else
      <livewire:date-picker-default wire:model.live="date"  :formattedDates="$formattedDates"  :barbeiroSelecionado="$barbeiro"  :key="$barbeiro?->id" />
  @endif
 
             

     

 

  <div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
      <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <h6 class="dark:text-white">Contratados</h6>
        </div>
        <div class="flex-auto px-0 pt-0 pb-2">
          <div class="p-0 overflow-x-auto">
        
         <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-6">

@foreach($barbearia->barbeiros()->withTrashed()->get() as $barbeiro)

<div class="bg-white dark:bg-slate-850 rounded-2xl shadow-md p-4 border dark:border-white/10 transition hover:shadow-lg">

    <div class="flex items-center gap-3">
        <img src="{{ $barbeiro->user->profile_photo_url }}" class="w-12 h-12 rounded-xl shadow" alt="Foto">
        <div>
            <h3 class="text-md font-semibold dark:text-white">
                {{ $barbeiro->user->name }}
                @if(auth()->user()->id == $barbeiro->user->id)
                <span class="text-xs text-emerald-500">(Você)</span>
                @endif
            </h3>
            <p class="text-sm text-slate-500 dark:text-white/70">{{ $barbeiro->user->email }}</p>
        </div>
    </div>

    <div class="mt-4">
        <p class="text-xs uppercase text-slate-400 dark:text-white/70 font-semibold">Métodos de Pagamento</p>
        <p class="text-sm dark:text-white">
            @forelse($barbeiro->payment_methods_allowed ?? [] as $pm)
                {{ $pm }}@if(!$loop->last), @endif
            @empty
                <span class="text-slate-400 text-sm">Nenhum método.</span>
            @endforelse
        </p>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <button wire:click="edit({{ $barbeiro->id }})"
            class="px-3 py-1 rounded-lg text-xs bg-emerald-500 text-white font-bold uppercase">
            Adicionar Método
        </button>

        <label class="flex items-center gap-1 text-sm dark:text-white cursor-pointer">
            <input type="radio" wire:model.change="barbeiros" value="{{ $barbeiro->id }}">
            <span>Selecionar</span>
        </label>
    </div>

</div>

@endforeach

</div>

        
          </div>
        </div>
      </div>
    </div>
  </div>
 
     
  <!-- card 2 -->
  <x-modal.card title="Adicionar métodos" blur wire:model="adicionarMetodos">
    @if($editing)
    <x-select
    label="Selecione os métodos de pagamento"
    wire:model="paymentMethods"
    placeholder="Selecione os métodos de pagamento"
    :options="[
      ['name' => 'PIX',  'id' => 'PIX', 'description' => 'The status is active'],
      ['name' => 'Boleto', 'id' => 'Boleto', 'description' => 'The status is pending'],
      ['name' => 'Cartão de Crédito', 'id' => 'Cartão de Crédito', 'description' => 'The status is stuck'],
      ['name' => 'Cartão de Débito', 'id' => 'Cartão de Débito', 'description' => 'The status is done'],
      ['name' => 'Dinheiro', 'id' => 'Dinheiro', 'description' => 'The status is done'],
  ]"
    option-label="name"
    option-value="id"
    multiselect
  />
 
    <x-slot name="footer">
      
 
                <x-button flat label="Cancelar" x-on:click="close" />
                <x-button primary label="Adicionar" wire:click.prevent="adicionarMetodo({{ $editing->id }})" />
          
        
    </x-slot>
    @endif
</x-modal.card>

  <div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
      <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <h6 class="dark:text-white">Agendamentos do Contratado</h6>
        </div>
        <div class="flex-auto px-0 pt-0 pb-2">
          <div class="p-0 overflow-x-auto">
         
         <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-6">

@forelse($this->agendamentosFiltrados ?? [] as $agendamento)

<div class="bg-white dark:bg-slate-850 rounded-2xl shadow-md p-5 border dark:border-white/10 hover:shadow-lg transition">

    <div class="flex items-center gap-3">
        <div class="text-blue-500 text-xl">
            <i class="ni ni-calendar-grid-58"></i>
        </div>
        <div>
            <p class="text-sm font-semibold dark:text-white">
                {{ $agendamento->start_date->format('d/m/Y H:i') }}
            </p>
            <p class="text-xs text-slate-500 dark:text-white/70">{{ $agendamento->owner->name }}</p>
        </div>
    </div>

    <div class="mt-3">
        <p class="text-xs uppercase text-slate-400 dark:text-white/70">Preço Total</p>
        <p class="font-bold text-slate-700 dark:text-white">R$ {{ $agendamento->total_price }}</p>
    </div>

    <div class="mt-3">
        <p class="text-xs uppercase text-slate-400 dark:text-white/70">Concluído em</p>
        <p class="text-sm dark:text-white">
            {{ $agendamento->deleted_at ? $agendamento->deleted_at->format('d/m/Y H:i') : 'Não concluído' }}
        </p>
    </div>

    <div class="flex flex-wrap justify-between items-center gap-2 mt-4">

       
     @if($agendamento->deleted_at)
    <button
        wire:click="cancelar({{ $agendamento->id }})"
        wire:loading.attr="disabled"
        wire:target="cancelar({{ $agendamento->id }})"
        class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg font-bold uppercase flex items-center justify-center gap-2 disabled:opacity-60"
        aria-live="polite"
    >
        <!-- Spinner exibido só enquanto a ação 'cancelar(id)' está em andamento -->
        <span wire:loading wire:target="cancelar({{ $agendamento->id }})" class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            Cancelando...
        </span>

        <!-- Texto normal quando não está carregando -->
        <span wire:loading.remove wire:target="cancelar({{ $agendamento->id }})">
            Cancelar
        </span>
    </button>
@else
    <button
        wire:click="concluir({{ $agendamento->id }})"
        wire:loading.attr="disabled"
        wire:target="concluir({{ $agendamento->id }})"
        class="px-3 py-1 bg-emerald-500 text-white text-xs rounded-lg font-bold uppercase flex items-center justify-center gap-2 disabled:opacity-60"
        aria-live="polite"
    >
        <span wire:loading wire:target="concluir({{ $agendamento->id }})" class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            Processando...
        </span>

        <span wire:loading.remove wire:target="concluir({{ $agendamento->id }})">
            Concluir
        </span>
    </button>
@endif

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '',55 . $agendamento->owner->phone) }}"
           target="_blank" class="text-blue-500 text-sm underline">
            {{ $agendamento->owner->phone }}
        </a>

    </div>

</div>

@empty

<div class="text-center py-10 dark:text-white">Nenhum agendamento encontrado.</div>

@endforelse

</div>

            
          
          </div>

          
         
        </div>
      </div>

      <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <h6 class="dark:text-white">Maquininhas</h6>
        </div>
        <div class="flex-auto px-0 pt-0 pb-2">
          <div class="p-0 overflow-x-auto">
         
            <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
              <thead class="align-bottom">
                <tr>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nome</th>
                  <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Taxa de Débito</th>
                  <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Taxa de Crédito</th>
                  <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Ações</th>
                  <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap"></th>
                </tr>
              </thead>
              <tbody class="border-t">
                @forelse($this->maquininhasFiltradas ?? [] as $maquininha)
                <tr>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <div class="flex px-2">
                         
                          <div class="my-auto">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">{{$maquininha->name }}</h6>
                          </div>
                      </div>
                  </td>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <p class="mb-0 text-sm font-semibold leading-normal dark:text-white dark:opacity-60">{{ $maquininha->taxa_debito }}%</p>
                  </td>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                    <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                     {{ $maquininha->taxa_credito }}%
                    </span>
                </td>
                  <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                 
                       

                     
                    
                  </td>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <button class="inline-block px-5 py-2.5 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none leading-normal text-sm ease-in bg-150 tracking-tight-rem bg-x-25 text-slate-400">
                          <i class="text-xs leading-tight fa fa-ellipsis-v dark:text-white dark:opacity-60"></i>
                      </button>
                  </td>
              </tr>

              @empty 

                <tr >
                  <td class="p-2 align-center bg-transparent whitespace-nowrap shadow-transparent">
                      <div class="flex px-2">
                          <div>
                            <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58 mr-2"></i>
                          </div>
                          <div class="my-auto">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nenhuma maquininha encontrada.</h6>
                          </div>
                      </div>
                  </td>
                  </tr>
                @endforelse

              </tbody>
              <div class="flex justify-center pb-5">
             

</div>
            </table>

            
          
          </div>

          
         
        </div>
      </div>

      <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <h6 class="dark:text-white">Faturas</h6>
        </div>
        <div class="flex-auto px-0 pt-0 pb-2">
          <div class="p-0 overflow-x-auto">
         
            <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
              <thead class="align-bottom">
                <tr>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Data</th>
                  <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Preço Total</th>

                  <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Ações</th>
                  <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap"></th>
                </tr>
              </thead>
              <tbody class="border-t">
                @forelse($this->faturas ?? [] as $agendamento)
                <tr>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <div class="flex px-2">
                          <div>
                            <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58 mr-2"></i>
                          </div>
                          <div class="my-auto">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">{{$agendamento->deleted_at->format('d/m/Y H:i') }}</h6>
                          </div>
                      </div>
                  </td>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <p class="mb-0 text-sm font-semibold leading-normal dark:text-white dark:opacity-60">R${{ $agendamento->fatura_price}}</p>
                  </td>
                 
                  <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                    @if($agendamento->deleted_at)
                    <span class="bg-gradient-to-tl from-red-600 to-orange-600  cursor-pointer 0 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white" wire:click="cancelar({{ $agendamento->id }})" wire:loading.attr="disabled" wire:loading.class="opacity-50" wire:target="cancelar({{ $agendamento->id }})">Cancelar</span>
                    @else
              
               
                  <span class="bg-gradient-to-tl from-emerald-500 to-teal-400  cursor-pointer 0 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white" wire:click="concluir({{ $agendamento->id }})" wire:loading.attr="disabled" wire:loading.class="opacity-50" wire:target="concluir({{ $agendamento->id }})">Concluir</span>
                    @endif
                       
      
                     
                    
                  </td>
                  <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <button class="inline-block px-5 py-2.5 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none leading-normal text-sm ease-in bg-150 tracking-tight-rem bg-x-25 text-slate-400">
                          <i class="text-xs leading-tight fa fa-ellipsis-v dark:text-white dark:opacity-60"></i>
                      </button>
                  </td>
              </tr>
                @empty
                <tr >
                  <td class="p-2 align-center bg-transparent whitespace-nowrap shadow-transparent">
                      <div class="flex px-2">
                          <div>
                            <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58 mr-2"></i>
                          </div>
                          <div class="my-auto">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nenhuma fatura encontrada.</h6>
                          </div>
                      </div>
                  </td>
                  </tr>
                @endforelse

              </tbody>
              <div class="flex justify-center pb-5">
                {{ $this->agendamentosFiltrados?->links() }}

</div>
            </table>

            
          
          </div>

          
         
        </div>
      </div>
    </div>
  </div>

  <div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
  id="exampleModalLg"
  tabindex="-1"
  aria-labelledby="exampleModalLgLabel"
  aria-modal="true"
  wire:ignore.self
  role="dialog">
  <div
    data-te-modal-dialog-ref
    wire:ignore.self
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px] min-[992px]:max-w-[800px]">
    <div
      class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
      <div
        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
        <!--Modal title-->
        <h5
          class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
          id="exampleModalLgLabel">
        Concluir com Fatura
        </h5>
        <!--Close button-->
        <button
          type="button"
          id="fechar"
          class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
          data-te-modal-dismiss
          aria-label="Close">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-6 w-6">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!--Modal body-->
      <div class="relative p-4"><form wire:submit="AgendarHorario">
        <x-select
        label="Selecionar Barbeiro"
        placeholder="Selecionar Barbeiro"
        wire:model.blur="barbeiroModel"
        class="mb-3"
        autocomplete="off"
      />
      

      
   
   
    

    
    </div>


      <div
      class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
      <button
        type="button"
        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-100 focus:bg-primary-accent-100 focus:outline-none focus:ring-0 active:bg-primary-accent-200"
        data-te-modal-dismiss
        data-te-ripple-init
        data-te-ripple-color="light">
        Fechar
      </button>
      <button
        type="submit"
        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
        data-te-ripple-init
        wire:target="AgendarHorario"
     wire:click="AgendarHorario"
        wire:loading.class="opacity-50"
     
      >
     
Agendar
      </button>
    </form>
    </div>
    </div>
  </div>
</div>
@assets
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js' ></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/moment@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/moment-timezone@6.1.10/index.global.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales/pt-br.js" ></script>
 @endassets
</div>