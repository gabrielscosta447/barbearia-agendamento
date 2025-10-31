
<div>
    <div class="w-full px-6 py-6 mx-auto">
      <!-- table 1 -->
    
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
              <h6 class="dark:text-white">Clientes</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
            
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Usuário</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Métodos de Pagamento</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Adicionar Métodos</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Contratado</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
               
                    @foreach($this->clientes as $cliente)
    
                  
                    <tr>
                    
                      <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                        <div class="flex px-2 py-1">
                          <div>
                            <img src="{{ $cliente->user->profile_photo_url }}" class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl" alt="user1" />
                          </div>
                          <div class="flex flex-col justify-center">
                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $cliente->user->name }} </h6>
                          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">{{  $cliente->user->email }}</p>
                          </div>
                        </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                     
                        <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">
                       
    
                        </p>
                      </td>
                      <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                        <span wire:click="edit({{ $cliente->id }})" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white cursor-pointer">Agendar</span>
                        <span wire:click="delete({{ $cliente->id }})" class="bg-gradient-to-tl from-red-500 to-red-600 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white cursor-pointer">Deletar</span>
                      </td>
                     
                      <td class="p-2 text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $cliente->created_at->format('d/m/Y') }}</span>
                      </td>
                      <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }}  whitespace-nowrap shadow-transparent">
                        <input type="radio"   wire:model.change="clienteSelecionado" value="{{ $cliente->id }}" id="checkbox_{{$cliente->id}}">
                       
                      </td>
                    </tr>
            

                  
                   <livewire:cliente.agendamentos.agendar-barbearia  :barbearia="$barbearia" :cliente="$cliente" :key="$cliente->id" />
                
           
            
                
                  @endforeach
                   
                  </tbody>
                </table>
            
              </div>
            </div>
          </div>
        </div>
      </div>
    
      <!-- card 2 -->
     
    
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
              <h6 class="dark:text-white">Agendamentos do Cliente</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
             
                <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Data</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total de Serviços</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Concluído as</th>
                      <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Ações</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap"></th>
                    </tr>
                  </thead>
                  <tbody class="border-t">
                    @foreach($this->agendamentosFiltrados ?? [] as $agendamento)
                    <tr>
                      <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                          <div class="flex px-2">
                              <div>
                                <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58 mr-2"></i>
                              </div>
                              <div class="my-auto">
                                  <h6 class="mb-0 text-sm leading-normal dark:text-white">{{$agendamento->start_date->format('d/m/Y H:i') }}</h6>
                              </div>
                          </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-sm font-semibold leading-normal dark:text-white dark:opacity-60">R${{ $agendamento->total_price}}</p>
                      </td>
                      <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                            {{ isset($agendamento->deleted_at) ? $agendamento->deleted_at->format('d/m/Y H:i') : "Não concluído" }}
                        </span>
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
                    
                    @endforeach
    
                  </tbody>
                  <div class="flex justify-center pb-5" wire:ignore>
          
    
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
      id="exampleModalLg43"
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
    
    </div>