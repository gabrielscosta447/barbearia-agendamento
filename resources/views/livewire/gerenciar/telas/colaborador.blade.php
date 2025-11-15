<div class="w-full px-6 py-6 mx-auto">
  <div class="flex flex-wrap -mx-3">
    <div class="max-w-full px-3 lg:w-2/3 lg:flex-none">
      <div class="flex flex-wrap -mx-3">

        <!-- Primeira coluna: Assinaturas / Cartões (substituí Bricks) -->
        <div class="max-w-full px-3 mb-6 lg:mb-0 lg:w-full lg:flex-none">
          <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white shadow-xl rounded-2xl">
            <div class="p-4 pb-0 mb-0 border-b-transparent">
              <div class="flex justify-between items-center">
                <h6 class="mb-0">Assinaturas (Asaas)</h6>
                <div>
                  <button wire:click.prevent="carregarAssinaturas" class="px-4 py-2 bg-gray-200 rounded">Recarregar</button>
                 
                 
                </div>
              </div>
            </div>

            <div class="p-4">
              @if(count($assinaturas) === 0)
                <p class="text-sm text-gray-500">Nenhuma assinatura encontrada para os barbeiros desta barbearia.</p>
              @endif

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro)
                  <div class="p-4 rounded-lg border">
                    <div class="flex items-start">
                      <div class="flex-1">
          
                        @php $sub = $assinaturas[$barbeiro->id] ?? null; @endphp
             @if(isset($sub['creditCard']))
   
          <div class="w-full max-w-full px-3 mb-6  xl:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 border-transparent border-solid shadow-xl rounded-2xl bg-clip-border">
              <div class="relative overflow-hidden rounded-2xl" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/card-visa.jpg')">
                <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 opacity-80"></span>
                <div class="relative z-10 flex-auto p-4">
                  <i class="p-2 text-white fas fa-wifi"></i>
                  <h5 class="pb-2 mt-6 mb-12 text-white">&nbsp;&nbsp;&nbsp;******&nbsp;&nbsp;&nbsp;*****&nbsp;&nbsp;&nbsp;*****&nbsp;&nbsp;&nbsp;{{$sub['creditCard']['creditCardNumber'] }}</h5>
                  <div class="flex">
                    <div class="flex">
                     
                    
                    </div>
                    <div class="flex items-end justify-end w-1/5 ml-auto">
                          @php
      
            $brand = $sub['creditCard']['creditCardBrand'];
            $brandImage = match(strtoupper($brand)) {
            
                'VISA' => 'https://seeklogo.com/images/V/visa-logo-B997CBEBF0-seeklogo.com.png',
                'MASTERCARD' => ' https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1200px-Mastercard-logo.svg.png',
                'ELO' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Elo_logo.png'
            };
          
        @endphp
                      <img class="w-3/5 object-cover mt-2" src="{{ $brandImage }}" alt="logo" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
                        <h6 class="font-semibold">{{ $barbeiro->user->name }}</h6>
                        <p class="text-sm text-gray-500">Método: {{ $sub['billingType'] ?? '—' }}</p>
                  

                        @if($barbeiro->assinatura_id)
                  
                          <p class="text-sm"><strong>Subscription ID:</strong> {{ $barbeiro->assinatura_id }}</p>
                          @if($sub)
                            <p class="text-sm text-gray-500">Status: {{ $sub['status'] ?? '—' }}</p>
                            <p class="text-sm text-gray-500">Próximo venc.: {{ isset($sub['nextDueDate']) ? \Carbon\Carbon::parse($sub['nextDueDate'])->format('d/m/Y') : '—' }}</p>
                          @endif

                          <div class="mt-3 flex gap-2">
                            <button wire:click="verCobrancas('{{ $barbeiro->assinatura_id }}')" class="px-3 py-1 bg-blue-600 text-black rounded">Ver Cobranças</button>
                            @if($sub && ($sub['status'] ?? '') === 'INACTIVE')
                                   <button
    wire:click.prevent="reativarAssinatura({{ $barbeiro->id }})"
    wire:loading.attr="disabled"
    wire:target="reativarAssinatura"
    class="px-4 py-2 bg-gray-200 rounded flex items-center gap-2"
>
    <span wire:loading.remove wire:target="reativarAssinatura">
        Assinar novamente
    </span>

    <span wire:loading wire:target="reativarAssinatura">
   <div class="h-5 w-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
    
    </span>
</button>

                                    @endif
             @if($sub && ($sub['status'] ?? '') === 'ACTIVE')                       
                           <button
    wire:click="abrirModal({{ $barbeiro->id }})"
    wire:loading.attr="disabled"
    wire:target="abrirModal"
    class="px-3 py-1 bg-red-600 text-black rounded flex items-center gap-2"
>
    <span wire:loading.remove wire:target="abrirModal">Cancelar</span>
    <span wire:loading wire:target="abrirModal">
           <div class="h-5 w-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
    </span>
</button>
@endif

                          </div>
                        @else
                          <div class="mt-3 flex gap-2">
                            <button wire:click="criarAssinatura({{ $barbeiro->id }}, 'PIX')" class="px-3 py-1 bg-green-600 text-white rounded">Criar Assinatura (PIX)</button>
                            <button wire:click="criarAssinatura({{ $barbeiro->id }}, 'BOLETO')" class="px-3 py-1 bg-amber-600 text-white rounded">Criar Assinatura (Boleto)</button>
                          </div>
                        @endif
                      </div>
                      <div class="ml-4 text-right">
                        <p class="text-sm">Próx: {{ $barbeiro->plan_ends_at ? \Carbon\Carbon::parse($barbeiro->plan_ends_at)->format('d/m/Y') : '—' }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

   <!-- PAGAMENTOS -->
<div class="mt-6 w-full">
  <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
    <div class="p-4 pb-0 mb-0"> 
      <div class="flex justify-between items-center">
        <h6 class="mb-0">Pagamentos</h6>
        <button wire:click.prevent="$refresh" class="px-4 py-2 bg-gray-200 rounded">Recarregar</button>
      </div>
    </div>

    <div class="flex-auto p-4 pb-0">
      <ul class="flex flex-col pl-0 mb-0 rounded-lg max-h-[350px] overflow-auto">
        
        @foreach($faturas as $fatura)
          <li class="relative flex flex-col px-4 py-3 mb-3 rounded-xl border">

            <div class="flex justify-between">
              <div class="flex flex-col">
                <h6 class="mb-1 text-sm font-semibold">
                  {{ isset($fatura['dateCreated']) ? \Carbon\Carbon::parse($fatura['dateCreated'])->format('d/m/Y') : '' }}
                </h6>
                <span class="text-xs text-gray-500">#{{ $fatura['id'] ?? '-' }}</span>
              </div>

              <div class="flex items-center text-sm">
                <span class="mr-4 font-semibold">
                  R$ {{ number_format($fatura['value'], 2, ',', '.') }}
                </span>
              </div>
            </div>

            <!-- BOTÃO GIGANTE PAGAR AGORA -->
            @if(($fatura['status'] ?? '') === 'PENDING' && !empty($fatura['invoiceUrl']))
              <a href="{{ $fatura['invoiceUrl'] }}" target="_blank"
                 class="mt-4 w-full text-center block bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-lg">
                PAGAR AGORA
              </a>
            @else
             <a href="{{ $fatura['invoiceUrl'] }}" target="_blank"
                 class="mt-4 w-full text-center block  text-black font-bold py-3 rounded-xl text-lg">
                VER PAGAMENTO
              </a>
              @endif

           

          </li>
        @endforeach

        @if(count($faturas) === 0)
          <li class="p-4 text-sm text-gray-500">Nenhum pagamento encontrado.</li>
        @endif
      </ul>
    </div>
  </div>
</div>


  <!-- Modal de confirmação -->
  <x-modal blur wire:model="simpleModal">
    <x-card title="Confirmar cancelamento">
      <p>Deseja realmente cancelar a assinatura deste barbeiro?</p>
      <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
          <x-button flat label="Cancelar" x-on:click="close" />
          @if($selectedBarbeiro)
        <x-button
    red
    label="Confirmar"
    wire:click="cancelar({{ $selectedBarbeiro->id }})"
    spinner="cancelar"
    wire:target="cancelar"
/>
          @endif
        </div>
      </x-slot>
    </x-card>
  </x-modal>

  <!-- Cobranças da assinatura selecionada -->
  @if(count($cobrancas) > 0)
    <div class="mt-6 p-4 bg-white shadow rounded">
      <h6 class="font-semibold mb-3">Cobranças da assinatura</h6>
      <ul class="space-y-3">
        @foreach($cobrancas as $c)
          <li class="flex justify-between items-center p-2 border rounded">
            <div>
              <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($c['dateCreated'] ?? $c['debitDate'])->format('d/m/Y') }}</div>
              <div class="text-xs text-gray-500">Status: {{ $c['status'] }}</div>
            </div>
            <div>
              <div class="text-sm font-semibold">R$ {{ number_format($c['value'], 2, ',', '.') }}</div>
              @if(!empty($c['invoiceUrl']))
                <a class="text-sm text-blue-600" href="{{ $c['invoiceUrl'] }}" target="_blank">Ver fatura</a>
              @endif
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  @endif
@if (session('error'))
    <div 
        x-data="{ open: true }" 
        x-show="open" 
        x-init="setTimeout(() => open = false, 4000)" 
        x-transition 
        class="bg-red-500 text-white px-4 py-2 rounded mb-3"
    >
        {{ session('error') }}
    </div>
@endif
</div>
