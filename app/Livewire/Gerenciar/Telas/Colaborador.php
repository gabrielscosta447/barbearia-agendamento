<?php

namespace App\Livewire\Gerenciar\Telas;

use App\Models\Barbearia;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\BarbeariaUser;
use Carbon\Carbon;

class Colaborador extends Component
{
    public Barbearia $barbearia;
    public $selectedBarbeiro;
    public $simpleModal = false;
    public $faturas = [];
    public $assinaturas = []; // assinaturas carregadas por barbeiro
    public $cobrancas = []; // cobranças da assinatura selecionada
    public $loading = false;

    protected $listeners = [
        'refreshAssinaturas' => 'carregarAssinaturas'
    ];

    public function mount($slug)
    {
        $this->barbearia = Barbearia::where('slug', $slug)->firstOrFail();
       
        $this->carregarAssinaturas();
        $this->carregarFaturasCacheadas();
    }

    /**
     * Carrega assinaturas (busca subscription pelo campo assinatura_id de cada BarbeariaUser)
     */
    public function carregarAssinaturas()
    {
        $this->assinaturas = [];

        $token = env('PIX_ACCESS_TOKEN');
        $baseUrl = 'https://api.asaas.com/v3';

        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            if ($barbeiro->assinatura_id) {
                try {
                    $resp = Http::withHeaders([
                        'accept' => 'application/json',
                        'access_token' => $token,
                    ])->get("{$baseUrl}/subscriptions/{$barbeiro->assinatura_id}");
             
                    if ($resp->successful()) {
                        $dados = $resp->json();
                        
                        $this->assinaturas[$barbeiro->id] = $dados;
                    } else {
                        // limpar campo se subscription inexistente
                        if ($resp->status() === 404) {
                            $barbeiro->assinatura_id = null;
                            $barbeiro->save();
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao buscar assinatura Asaas: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Carrega faturas (payments) de todas assinaturas e agrupa em $this->faturas
     */
   public function carregarFaturasCacheadas()
{
    // Removido o uso de cache completamente
    $this->faturas = (function () {
        $all = [];
        $token = env('PIX_ACCESS_TOKEN');
        $baseUrl = 'https://api.asaas.com/v3';

        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {

            // 1) Payments da assinatura (se existir)
            if ($barbeiro->assinatura_id) {
                try {
                    $resp = Http::withHeaders([
                        'accept' => 'application/json',
                        'access_token' => $token,
                    ])->get("{$baseUrl}/payments", [
                        'subscription' => $barbeiro->assinatura_id,
                        'limit' => 50
                    ]);

                    if ($resp->successful()) {
                        $data = $resp->json('data') ?? [];

                        foreach ($data as $p) {
                            $p['barbearia_user_id'] = $barbeiro->id;
                            $all[] = $p;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao buscar payments Asaas: ' . $e->getMessage());
                }
            }

            // 2) Payment avulso se houver payment_id
            if ($barbeiro->payment_id) {
                try {
                    $resp = Http::withHeaders([
                        'accept' => 'application/json',
                        'access_token' => $token,
                    ])->get("{$baseUrl}/payments/{$barbeiro->payment_id}");

                    if ($resp->successful()) {
                        $p = $resp->json();
                        $p['barbearia_user_id'] = $barbeiro->id;
                        $all[] = $p;
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao buscar payment id Asaas: ' . $e->getMessage());
                }
            }
        }

        // ordenar por data (decrescente)
        usort($all, function ($a, $b) {
            $da = $a['dateCreated'] ?? ($a['debitDate'] ?? null);
            $db = $b['dateCreated'] ?? ($b['debitDate'] ?? null);
            return strtotime($db) <=> strtotime($da);
        });

        return $all;
    })();
}

    /**
     * Criar assinatura no Asaas para um BarbeariaUser
     * billingType: 'PIX' ou 'BOLETO' ou 'CREDIT_CARD'
     */
    public function criarAssinatura($barbeariaUserId, $billingType = 'PIX')
    {
        $this->loading = true;

        $barbeiro = BarbeariaUser::withTrashed()->find($barbeariaUserId);
        if (!$barbeiro) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Barbeiro não encontrado']);
            $this->loading = false;
            return;
        }

        $token = env('PIX_ACCESS_TOKEN');
        $baseUrl = 'https://api.asaas.com/v3';

        // valor da assinatura (use o campo price ou outro)
        $value = $barbeiro->price ?? 30;

        $payload = [
            'customer' => $barbeiro->user->asaas_customer_id ?? null, // se já tiver customer salvo
            'value' => (float) $value,
            'description' => 'Assinatura ' . ($this->barbearia->nome ?? ''),
            'billingType' => $billingType,
            'cycle' => 'MONTHLY',
            'externalReference' => (string) $barbeiro->id,
            'sendPaymentByPostalService' => false
        ];

        // se não tiver customer, deixe em branco e o Asaas criará e retornará o campo customer
        try {
            $resp = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'access_token' => $token,
            ])->post("{$baseUrl}/subscriptions", $payload);

            if ($resp->successful()) {
                $sub = $resp->json();

                // salva no banco
                $barbeiro->assinatura_id = $sub['id'] ?? null;
                $barbeiro->asaas_customer_id = $sub['customer'] ?? $barbeiro->asaas_customer_id;
                $barbeiro->payment_method = $billingType;
                $barbeiro->save();

                // atualizar assinaturas/faturas
                $this->carregarAssinaturas();
                Cache::forget('faturas_barbearia_' . $this->barbearia->id);
                $this->carregarFaturasCacheadas();

                $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Assinatura criada no Asaas.']);
            } else {
                \Log::error('Erro criar assinatura Asaas', ['body' => $resp->body(), 'status' => $resp->status()]);
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Falha ao criar assinatura.']);
            }
        } catch (\Exception $e) {
            \Log::error('Exception criarAssinatura: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Erro de comunicação Asaas.']);
        }

        $this->loading = false;
    }

    public function reativarAssinatura($barbeariaUserId)
{
    $barbeiro = BarbeariaUser::withTrashed()->find($barbeariaUserId);
    if (!$barbeiro || !$barbeiro->assinatura_id) {
        $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Assinatura não encontrada.']);
        return;
    }

    $token = env('PIX_ACCESS_TOKEN');
    $baseUrl = 'https://api.asaas.com/v3';

   $resp = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => $token,
        ])->get("{$baseUrl}/payments", [
            'subscription' => $barbeiro->assinatura_id,
            'status'       => 'OVERDUE',
            'limit'        => 50
        ]);
 if ($resp->json('totalCount') > 0) {
    session()->flash('error', 'Existem faturas vencidas. Regularize antes de reativar a assinatura.');
    return;
}
    try {

   

        $resp = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => $token,
        ])->put("{$baseUrl}/subscriptions/{$barbeiro->assinatura_id}", [
            'status' => 'ACTIVE',
            
        ]);



        if ($resp->successful()) {

            // restaurar o barbeiro (caso esteja deletado)
            $barbeiro->restore();

        
    
            $barbeiro->save();

   
            $this->carregarAssinaturas();
       

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Assinatura reativada com sucesso.'
            ]);
        } else {

            \Log::error('Erro reativar assinatura Asaas', ['body' => $resp->body()]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Falha ao reativar assinatura.'
            ]);
        }

    } catch (\Exception $e) {

        \Log::error('Exception reativarAssinatura: ' . $e->getMessage());

        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'Erro de comunicação Asaas.'
        ]);
    }
}

    /**
     * Cancelar assinatura (ou pausar) via Asaas
     */
    public function cancelarAssinatura($barbeariaUserId)
    {
        $barbeiro = BarbeariaUser::withTrashed()->find($barbeariaUserId);
        if (!$barbeiro || !$barbeiro->assinatura_id) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Assinatura não encontrada.']);
            return;
        }

        $token = env('PIX_ACCESS_TOKEN');
        $baseUrl = 'https://api.asaas.com/v3';

        try {
        $resp = Http::withHeaders([
        'accept' => 'application/json',
        'content-type' => 'application/json',
        'access_token' => $token,
    ])->put("{$baseUrl}/subscriptions/{$barbeiro->assinatura_id}", [
        'status' => 'INACTIVE'
    ]);


            if ($resp->successful()) {
               $barbeiro->delete();
                $barbeiro->save();
               
                Cache::forget('faturas_barbearia_' . $this->barbearia->id);
                $this->carregarAssinaturas();
                $this->carregarFaturasCacheadas();
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Assinatura cancelada.']);
            } else {
                \Log::error('Erro cancelar assinatura Asaas', ['body' => $resp->body()]);
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Falha ao cancelar assinatura.']);
            }
        } catch (\Exception $e) {
            \Log::error('Exception cancelarAssinatura: ' . $e->getMessage());
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Erro de comunicação Asaas.']);
        }
    }

    /**
     * Buscar cobranças (payments) de uma assinatura e popular $this->cobrancas
     */
    public function verCobrancas($subscriptionId)
    {
        $this->cobrancas = [];
        $token = env('ASAAS_ACCESS_TOKEN');
        $baseUrl = 'https://api.asaas.com/v3';

        try {
            $resp = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => $token
            ])->get("{$baseUrl}/payments", [
                'subscription' => $subscriptionId,
                'limit' => 50
            ]);

            if ($resp->successful()) {
                $this->cobrancas = $resp->json('data') ?? [];
            } else {
                \Log::error('Erro verCobrancas Asaas', ['body' => $resp->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('Exception verCobrancas: ' . $e->getMessage());
        }
    }

    /**
     * Exibir modal de confirmação (usado no layout)
     */
    public function abrirModal($id)
    {
        $this->selectedBarbeiro = BarbeariaUser::withTrashed()->find($id);
        $this->simpleModal = true;
    }

    public function cancelar($id)
    {
        // ação confirmada do modal: cancelar assinatura
        $this->cancelarAssinatura($id);
        $this->simpleModal = false;
    }

    public function render()
    {
        // garantir faturas atualizadas
        $this->carregarAssinaturas();
        $this->carregarFaturasCacheadas();

        return view('livewire.gerenciar.telas.colaborador', [
            'faturas' => $this->faturas,
            'assinaturas' => $this->assinaturas,
            'cobrancas' => $this->cobrancas
        ])->layout('components.layouts.barbearia', [
            'barbearia' => $this->barbearia
        ]);
    }
}
