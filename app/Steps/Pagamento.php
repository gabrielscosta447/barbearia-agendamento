<?php

namespace App\Steps;

use App\Enums\DaysOfWeek;
use App\Enums\PaymentMethods;
use Vildanbina\LivewireWizard\Components\Step;

use Illuminate\Validation\Rule;
use App\Models\Barbearia;
use App\Models\Barbeiros;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\UserWorkingHours;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use App\Models\BarbeariaUser;
use App\Models\Maquininha;

class Pagamento extends Step
{
    protected string $view = 'steps.pagamento';
    public $qrCode;
    public $cardIds = [];

    public function mount()
    {
    }

    public function save($state)
    {
        // ===============================
        // 1) CRIA BARBEARIA
        // ===============================
        $barbearia = new Barbearia;
        $barbearia->nome = $state['name'];
        $barbearia->cep = $state['cep'];
        $path = $state['imagem']->store('/', 'public');
        $barbearia->imagem =  $path;
        $barbearia->rua = $state['rua'];
        $barbearia->cidade = $state['cidade'];
        $barbearia->estado = $state['estado'];
        $barbearia->complemento = $state['complemento'];
        $barbearia->owner_id = auth()->user()->id;
        $barbearia->slug = $state['slug'];
        $barbearia->cpf = $state['cpf'];
        $barbearia->bairro = $state['bairro'];
     

        // Busca latitude/longitude via ViaCEP + OSRM
        $cep = preg_replace('/[^0-9]/', '', $state['cep']);
        $endereco = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();

        if (!isset($endereco['erro'])) {
            $removeAcentos = function ($str) {
                $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
                $str = preg_replace('/[^A-Za-z0-9\s]/', '', $str);
                return trim($str);
            };

            $logradouro  = $removeAcentos($endereco['logradouro'] ?? '');
            $bairro      = $removeAcentos($endereco['bairro'] ?? '');
            $localidade  = $removeAcentos($endereco['localidade'] ?? '');
            $uf          = $removeAcentos($endereco['uf'] ?? '');

            $partes = array_filter([$logradouro, $bairro, $localidade, $uf, 'Brasil']);
            $query = implode(', ', $partes);
            $query = str_replace(' ', '+', $query);

            try {
                $geo = Http::withHeaders([
                    'User-Agent' => 'MeuAppLaravel/1.0 (contato@meusite.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]);

                $geo->throw();
                $dados = $geo->json();

                if (!empty($dados)) {
                    $barbearia->latitude = $dados[0]['lat'];
                    $barbearia->longitude = $dados[0]['lon'];
                }
            } catch (\Exception $e) {
                // Não trava cadastro se der erro na geolocalização
            }
        }

        $barbearia->save();

        // ===============================
        // 2) CRIA BARBEARIA_USER
        // ===============================
        $barbearia_user = new BarbeariaUser;
        $barbearia_user->payment_methods_allowed = $state['payments'];
        $barbearia_user->user_id = auth()->user()->id;

      
        $barbearia_user->barbearia_id = $barbearia->id;

        $barbearia_user->chave_pix = $state['chave_pix'];
        $barbearia_user->tipo_chave = $state['tipo_chave'];
        $barbearia_user->save();
    

        // ===============================
        // 3) CRIA MAQUININHA
        // ===============================
        $maquininha =  new Maquininha;
        $maquininha->name = $state['maquininhaname'];
        $maquininha->barbearia_user_id = $barbearia_user->id;
        $maquininha->taxa_debito = $state['maquininhadebito'];
        $maquininha->taxa_credito = $state['maquininhacredito'];
        $maquininha->save();

        // ===============================
        // 4) HORÁRIOS DE FUNCIONAMENTO
        // ===============================
        foreach ($state['dias'] as $index => $ativo) {
            if ($ativo) {
                $dia = DaysOfWeek::from((int)$index);

                if ($dia !== null) {
                    UserWorkingHours::create([
                        'barbearia_user_id' => $barbearia_user->id,
                        'day_of_week' => $dia->value,
                        'start_hour' => $state['horariosIniciais'][$index],
                        'end_hour' => $state['horariosFinais'][$index],
                        'intervals' => [
                            'interval' => [
                                'start' => $state['intervaloInicial'][$index],
                                'end' => $state['intervaloFinal'][$index]
                            ]
                        ]
                    ]);
                }
            }
        }

      // ===============================
// 5) ASAAS - CRIAR CLIENTE
// ===============================
$asaasToken = env('PIX_ACCESS_TOKEN');

$clienteResponse = Http::withHeaders([
    'accept' => 'application/json',
    'access_token' => $asaasToken,
    'content-type' => 'application/json'
])->post(env("PIX_BASE_URL") . "customers", [
    "name" => auth()->user()->name,
    "email" => auth()->user()->email,
   
    "cpfCnpj" => preg_replace('/\D/' , '', $state['cpf']),
    "externalReference" => auth()->user()->id
]);

if ($clienteResponse->failed()) {
    dd("Erro ao criar cliente ASAAS", $clienteResponse->json());
}

$clienteId = $clienteResponse->json()['id'];


// ===============================
// 6) ASAAS - CRIAR ASSINATURA
// ===============================
$assinaturaResponse = Http::withHeaders([
    'accept' => 'application/json',
    'access_token' => $asaasToken,
    'content-type' => 'application/json'
])->post(env("PIX_BASE_URL") . "subscriptions", [
    "customer" => $clienteId,
    "billingType" => "UNDEFINED",
    "value" => 5,
    "cycle" => "MONTHLY",
"nextDueDate" => now()->addDays(90)->format('Y-m-d'),
    "description" => "Assinatura BarberConnect",
    "externalReference" => (string) $barbearia_user->id,
    "callback" =>[
        "successUrl" => env('APP_URL') . "/gerenciar/" . $barbearia->slug. "/billing" ,
    ]

]);

if ($assinaturaResponse->failed()) {
    dd("Erro ao criar assinatura ASAAS", $assinaturaResponse->json());
}

$subscriptionId = $assinaturaResponse->json()['id'];



$barbearia_user->assinatura_id = $subscriptionId;
$barbearia_user->asaas_customer_id = $clienteId;
$barbearia_user->save();


// ❗ Você estava DELETANDO o barbearia_user, removi isso
// $barbearia_user->delete();

// REDIRECIONAR PARA O invoiceUrl
return redirect(env('APP_URL') . "/gerenciar/" . $barbearia->slug. "/billing");

    }

    private function limparCacheBarbearias()
    {
        $cacheKey = "homepage-barbearias";
        Cache::forget($cacheKey);
    }

    public function icon(): string
    {
        return 'credit-card';
    }

    public function title(): string
    {
        return __('Pagamento');
    }
}
