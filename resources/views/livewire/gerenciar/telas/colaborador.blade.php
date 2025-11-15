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
                  <button wire:click.prevent="carregarAssinaturas" class="px-4 py-2 bg-gray-200 rounded">Atualizar</button>
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
                        <h6 class="font-semibold">{{ $barbeiro->user->name }}</h6>
                        <p class="text-sm text-gray-500">Método: {{ $sub['billingType'] ?? '—' }}</p>
                  

                        @if($barbeiro->assinatura_id)
                  
                          <p class="text-sm"><strong>Subscription ID:</strong> {{ $barbeiro->assinatura_id }}</p>
                          @if($sub)
                            <p class="text-sm text-gray-500">Status: {{ $sub['status'] ?? '—' }}</p>
                            <p class="text-sm text-gray-500">Próximo venc.: {{ isset($sub['nextDueDate']) ? \Carbon\Carbon::parse($sub['nextDueDate'])->format('d/m/Y') : '—' }}</p>
                          @endif

                          <div class="mt-3 flex gap-2">
                            <button wire:click="verCobrancas('{{ $barbeiro->assinatura_id }}')" class="px-3 py-1 bg-blue-600 text-white rounded">Ver Cobranças</button>
                            <button wire:click="abrirModal({{ $barbeiro->id }})" class="px-3 py-1 bg-red-600 text-white rounded">Cancelar</button>
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

    <!-- Segunda coluna: Faturas / Cobranças -->
    <div class="w-full max-w-full px-3 lg:w-1/3 lg:flex-none">
      <div class="relative flex flex-col h-full min-w-0 break-words bg-white shadow-xl rounded-2xl">
        <div class="p-4 pb-0 mb-0">
          <div class="flex justify-between items-center">
            <h6 class="mb-0">Faturas</h6>
            <button wire:click.prevent="$refresh" class="px-4 py-2 bg-gray-200 rounded">Atualizar</button>
          </div>
        </div>

        <div class="flex-auto p-4 pb-0">
          <ul class="flex flex-col pl-0 mb-0 rounded-lg max-h-[350px] overflow-auto">
            @foreach($faturas as $fatura)
              <li class="relative flex justify-between px-4 py-2 mb-2 rounded-xl">
                <div class="flex flex-col">
                  <h6 class="mb-1 text-sm font-semibold">{{ isset($fatura['dateCreated']) ? \Carbon\Carbon::parse($fatura['dateCreated'])->format('d/m/Y H:i') : (isset($fatura['debitDate']) ? \Carbon\Carbon::parse($fatura['debitDate'])->format('d/m/Y H:i') : '') }}</h6>
                  <span class="text-xs">#{{ $fatura['id'] ?? '-' }}</span>
                </div>
                <div class="flex items-center text-sm">
                  <span class="mr-4">R$ {{ number_format($fatura['value'] ?? ($fatura['transaction_amount'] ?? 0), 2, ',', '.') }}</span>
                  @if(!empty($fatura['invoiceUrl']))
                    <a href="{{ $fatura['invoiceUrl'] }}" target="_blank" class="px-2 py-1 bg-gray-100 rounded">Ver</a>
                  @endif
                </div>
              </li>
            @endforeach
            @if(count($faturas) === 0)
              <li class="p-4 text-sm text-gray-500">Nenhuma fatura encontrada.</li>
            @endif
          </ul>
        </div>
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
            <x-button red label="Confirmar" wire:click="cancelar({{ $selectedBarbeiro->id }})" />
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

</div>
