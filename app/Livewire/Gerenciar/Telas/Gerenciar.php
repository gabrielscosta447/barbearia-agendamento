<?php

namespace App\Livewire\Gerenciar\Telas;

use App\Models\Barbearia;
use App\Models\Agendamento;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plan;
use Livewire\Attributes\Computed;

class Gerenciar extends Component
{



    public $barbearia;
    public $agendamentos;
    public $showDataLabels = false;
     public $agendamentosPorMes;
     public $type;
     public int $agendamentosHoje = 0;
         


    public function mount($slug) {
     
        $this->barbearia = Barbearia::where('slug', $slug)->firstOrFail();
  
    
    
        
    }



    #[Computed]
    public function usersToday()
    {
       
           $count =0 ;
       foreach( $this->barbearia->barbeiros as $barbeiro){
                      $count +=  $barbeiro->agendamentos()->onlyTrashed()->whereDate("deleted_at", Carbon::now())->count();
       };
        return $count;
    }
    
    #[Computed]
    public function usersPorcentagem()
    {
        $agendamentosSemanaPassada = 0;
    
        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            $agendamentosSemanaPassada += $barbeiro->agendamentos()->onlyTrashed()->whereDate("deleted_at", Carbon::now()->subDays(7)->format('Y-m-d'))->sum('fatura_price');
        }
    
        $diferenca = $this->usersToday - $agendamentosSemanaPassada;
    
        $porcentagemAumento = ($agendamentosSemanaPassada != 0) ? ($diferenca / $agendamentosSemanaPassada) * 100 : 0;
        
        return $porcentagemAumento;
    }
    
   #[Computed]
public function usersLastQuarterComparison()
{
    $dataAtualInicio = Carbon::now()->startOfQuarter();
    $dataAtualFim = Carbon::now();

    $dataPassadaInicio = $dataAtualInicio->copy()->subQuarter();
    $dataPassadaFim = $dataAtualFim->copy()->subQuarter();

    $clientesAtual = 0;
    $clientesPassado = 0;

    foreach ($this->barbearia->barbeiros as $barbeiro) {

        // Clientes únicos do trimestre atual
        $clientesAtual += $barbeiro->agendamentos()
            ->onlyTrashed()
            ->whereBetween("deleted_at", [$dataAtualInicio, $dataAtualFim])
            ->distinct("owner_id")
            ->count("owner_id");

        // Clientes únicos do trimestre passado
        $clientesPassado += $barbeiro->agendamentos()
            ->onlyTrashed()
            ->whereBetween("deleted_at", [$dataPassadaInicio, $dataPassadaFim])
            ->distinct("owner_id")
            ->count("owner_id");
    }

    $diferenca = $clientesAtual - $clientesPassado;

    $porcentagemAumento = ($clientesPassado != 0)
        ? ($diferenca / $clientesPassado) * 100
        : 0;

    return [
        'usuarios_ultimo_trimestre_atual' => $clientesAtual,
        'porcentagem_aumento' => $porcentagemAumento
    ];
}

    
    private function getTotalFaturaPrice($inicio, $fim)
    {
        $totalFaturaPrice = 0;
    
        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            $totalFaturaPrice += $barbeiro->agendamentos()
                ->onlyTrashed()
                ->where('deleted_at', '>=', $inicio)
                ->where('deleted_at', '<=', $fim)
                ->sum('fatura_price');
        }
    
        return $totalFaturaPrice;
    }
    
    #[Computed]
    public function totalhoje()
    {
        $totalHoje = 0;
    
        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            $agendamentos = $barbeiro->agendamentos()->onlyTrashed()->whereDate('deleted_at', today())->get();
    
            foreach ($agendamentos as $agendamento) {
             
                    $totalHoje += $agendamento->fatura_price;
             
            }
        }
    
        return $totalHoje;
    }
    
    #[Computed]
    public function diferencapercentual()
    {
        $ontem = Carbon::yesterday();
        $totalOntem = 0;
    
        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            $agendamentos = $barbeiro->agendamentos()->onlyTrashed()->whereDate('deleted_at', $ontem)->get();
    
            foreach ($agendamentos as $agendamento) {
                $totalOntem += $agendamento->fatura_price;
            }
        }
    
        $diferenca = $this->totalhoje - $totalOntem;
    
        if ($this->totalhoje == 0 || $totalOntem == 0) {
            return "0";  // Ou outra mensagem ou valor que faça sentido para a sua aplicação
        }
    
        $aumentoPercentual = ($diferenca / $totalOntem) * 100;
    
        return number_format($aumentoPercentual, 2);
    }
    #[Computed]
    public function totalMes()
    {
        $primeiroDiaDoMes = now()->startOfMonth();
        $ultimoDiaDoMes = now()->endOfMonth();
    
        $primeiroDiaMesAnterior = now()->subMonth()->startOfMonth();
        $ultimoDiaMesAnterior = now()->subMonth()->endOfMonth();
    
        $totalMesAtual = $this->calcularTotalMes($primeiroDiaDoMes, $ultimoDiaDoMes);
        $totalMesAnterior = $this->calcularTotalMes($primeiroDiaMesAnterior, $ultimoDiaMesAnterior);
    
        $diferenca = $totalMesAtual - $totalMesAnterior;
        $percentualDiferenca = ($totalMesAnterior != 0) ? ($diferenca / $totalMesAnterior) * 100 : 0;
    
        return [
            'total_mes_atual' => $totalMesAtual,
            'total_mes_anterior' => $totalMesAnterior,
            'diferenca' => $percentualDiferenca
        ];
    }
    
    private function calcularTotalMes($primeiroDia, $ultimoDia)
    {
        $totalMes = 0;
    
        foreach ($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro) {
            $totalMes += $barbeiro->agendamentos()
                ->onlyTrashed()
                ->whereBetween('deleted_at', [$primeiroDia, $ultimoDia])
                ->sum("fatura_price");
        }
    
        return $totalMes;
    }

    public function render()

    {  
      
    
        return view('livewire.gerenciar.telas.gerenciar')
        ->layout('components.layouts.barbearia', [
                'barbearia' => $this->barbearia,
            ]);
    }
}