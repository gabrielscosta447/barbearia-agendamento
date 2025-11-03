<div class="max-w-md mx-auto bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
    {{-- QR Code --}}
    <div class="flex flex-col items-center text-center">
        <img
            src="data:image/png;base64,{{ $qrCodePix }}"
            alt="QR Code Pix"
            class="w-48 h-48 object-contain mb-4"
        />

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Pagamento via Pix</h2>
        <p class="text-gray-500 text-sm">Escaneie o QR Code abaixo ou copie o código Pix.</p>
    </div>

    {{-- Copia e Cola Pix --}}
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Copia e Cola Pix
        </label>

        <div class="flex items-center gap-2">
            <input
                type="text"
                readonly
                value="{{ $pixCopiaECola }}"
                class="flex-1 text-sm p-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
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

        {{-- Feedback visual --}}
        <div
            id="msgCopiado"
            class="hidden flex items-center mt-3 text-green-700 bg-green-100 border border-green-200 rounded-lg p-2 text-sm"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Código Pix copiado!
        </div>
    </div>

    {{-- Rodapé opcional --}}
    <p class="text-xs text-gray-400 text-center mt-6">
        O pagamento será confirmado automaticamente após a compensação.
    </p>

    <button
        type="button"
        data-te-ripple-init
        data-te-ripple-color="light"
        wire:click="voltar"
        class="mt-4 rounded bg-gray-200 px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-black shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-400 active:bg-gray-500"
    >
        VOLTAR
    </button>
</div>

<script>
document.addEventListener('livewire:navigated', () => { 
        const btnCopiar = document.getElementById('btnCopiarPix');
        const inputPix = document.getElementById('pixCopiaECola');
        const msgCopiado = document.getElementById('msgCopiado');

        btnCopiar.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(inputPix.value);
                
                // Mostrar feedback
                msgCopiado.classList.remove('hidden');
                msgCopiado.classList.add('flex');

                // Esconder após 2.5 segundos
                setTimeout(() => {
                    msgCopiado.classList.add('hidden');
                    msgCopiado.classList.remove('flex');
                }, 2500);
            } catch (err) {
                console.error('Erro ao copiar Pix:', err);
            }
        });
    });
</script>
