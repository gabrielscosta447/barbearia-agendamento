<div wire:poll.5s class="max-w-md mx-auto bg-white shadow-lg rounded-2xl p-6 border border-gray-200">

    {{-- PAGAMENTO CONFIRMADO --}}
    @if($agendamento->pago == 1)

        <div class="max-w-md mx-auto bg-green-50 border border-green-300 text-green-800 shadow-lg rounded-2xl p-6">

            <h1 class="text-2xl font-bold text-green-700 mb-4 text-center">
                Pagamento Confirmado! 🎉
            </h1>

            <p class="text-center text-green-600 text-lg font-semibold">
                Sua reserva foi concluída com sucesso.
            </p>

            <div class="flex justify-center mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <button
                type="button"
                wire:click="voltar"
                class="mt-6 w-full rounded bg-green-500 px-7 py-3 text-sm font-medium uppercase leading-normal text-white hover:bg-green-700 transition">
                Continuar
            </button>
        </div>

    {{-- PAGAMENTO EXPIRADO (MAIS DE 1H) --}}
    @elseif(now()->diffInMinutes($agendamento->created_at) >= 60)

        <div class="max-w-md mx-auto bg-red-50 border border-red-300 text-red-800 shadow-lg rounded-2xl p-6">

            <h1 class="text-2xl font-bold text-red-700 mb-4 text-center">
                Pagamento Expirado ⏳
            </h1>

            <p class="text-center text-red-600 text-lg font-semibold">
                O tempo para realizar o pagamento terminou.
            </p>

            <div class="flex justify-center mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <button
                type="button"
                wire:click="voltar"
                class="mt-6 w-full rounded bg-red-500 px-7 py-3 text-sm font-medium uppercase leading-normal text-white hover:bg-red-700 transition">
                VOLTAR
            </button>
        </div>

    {{-- PAGAMENTO AINDA NÃO CONFIRMADO --}}
    @else

        <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Detalhes do Pagamento</h1>

        @php
            $preco = $agendamento->total_price * 0.3;
            $preçoNaBarbearia = $agendamento->total_price - $preco;
        @endphp

        <p class="text-gray-600 mb-6 text-center">
            Total a pagar para o barbeiro para reservar horário:
            <span class="font-semibold text-lg text-green-600">
                R$ {{ number_format($preco, 2, ',', '.') }}
            </span>
        </p>

        <p class="text-gray-600 mb-6 text-center">
            Valor restante para pagar ao barbeiro na barbearia:
            <span class="font-semibold text-lg text-green-600">
                R$ {{ number_format($preçoNaBarbearia, 2, ',', '.') }}
            </span>
        </p>

        {{-- QR CODE --}}
        <div class="flex flex-col items-center text-center">
            <img 
                src="data:image/png;base64,{{ $agendamento->id_pix }}" 
                alt="QR Code Pix"
                style="width: 250px; height: 250px; border-radius: 12px;"
            >

            <h2 class="text-lg font-semibold text-gray-800 mb-2">Pagamento via Pix</h2>
            <div class="text-center mb-4" wire:ignore>
    <p class="text-gray-600">O pagamento expira em:</p>
    <span id="timer" class="text-xl font-bold text-red-600">--:--</span>
</div>
            <p class="text-gray-500 text-sm">Escaneie o QR Code abaixo ou copie o código Pix.</p>
        </div>

        {{-- COPIA E COLA --}}
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Copia e Cola Pix
            </label>

            <div class="flex items-center gap-2">
                <input
                    type="text"
                    readonly
                    value="{{ $pixCopiaECola }}"
                    class="flex-1 text-sm p-2 border border-gray-300 rounded-lg bg-white"
                    id="pixCopiaECola"
                />
                <button
                    type="button"
                    id="btnCopiarPix"
                    class="bg-green-600 hover:bg-green-700 text-black text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                >
                    Copiar
                </button>
            </div>

            <div
                id="msgCopiado"
                class="hidden flex items-center mt-3 text-green-700 bg-green-100 border border-green-200 rounded-lg p-2 text-sm"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Código Pix copiado!
            </div>
        </div>

        <p class="text-xs text-gray-400 text-center mt-6">
            Para reservar horário com o barbeiro, você deve efetuar o pagamento de 30% do valor total do serviço via Pix.
            O pagamento será confirmado automaticamente após a compensação.
        </p>

     <button
    type="button"
    wire:click="voltar"
    class="mt-4 rounded bg-gray-200 px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-black hover:bg-gray-300 flex items-center justify-center gap-2"
>
    <span wire:loading.remove wire:target="voltar">
        VOLTAR
    </span>

    <span wire:loading wire:target="voltar" class="flex items-center gap-2">
        <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        Carregando...
    </span>
</button>

    @endif
</div>


<script>
document.addEventListener('livewire:navigated', () => { 
    const btnCopiar = document.getElementById('btnCopiarPix');
    const inputPix = document.getElementById('pixCopiaECola');
    const msgCopiado = document.getElementById('msgCopiado');

    if(!btnCopiar) return;

    btnCopiar.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(inputPix.value);
            
            msgCopiado.classList.remove('hidden');
            msgCopiado.classList.add('flex');

            setTimeout(() => {
                msgCopiado.classList.add('hidden');
                msgCopiado.classList.remove('flex');
            }, 2500);
        } catch (err) {
            console.error('Erro ao copiar Pix:', err);
        }
    });
     const expirationTime = @js($expiraEm); // timestamp enviado pelo Livewire
    const timerEl = document.getElementById('timer');

    function atualizarTimer() {
        const agora = new Date().getTime();
        const diff = expirationTime - agora;

        if (diff <= 0) {
            timerEl.innerText = "00:00";
        
            return;
        }

        const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diff % (1000 * 60)) / 1000);

        timerEl.innerText =
            String(minutos).padStart(2, '0') + ":" + String(segundos).padStart(2, '0');
    }

    setInterval(atualizarTimer, 1000);
});
</script>
