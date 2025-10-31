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
                wire:click="copiarPix"
                type="button"
                class="bg-green-600 hover:bg-green-700 text-black text-sm font-medium px-4 py-2 rounded-lg transition-colors"
            >
                Copiar
            </button>
        </div>

        {{-- Feedback visual --}}
        @if ($copiado)
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="flex items-center mt-3 text-green-700 bg-green-100 border border-green-200 rounded-lg p-2 text-sm"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Código Pix copiado!
            </div>
        @endif
    </div>

    {{-- Rodapé opcional --}}
    <p class="text-xs text-gray-400 text-center mt-6">
        O pagamento será confirmado automaticamente após a compensação.
    </p>
</div>

@script
<script>
    Livewire.on('copiarPix', (data) => {
        navigator.clipboard.writeText(data)
            .then(() => console.log('Pix copiado!'))
            .catch(err => console.error('Erro ao copiar Pix:', err));
    });
</script>
@endscript
