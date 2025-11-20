<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PaymentMethods;
use App\Enums\PlanTypes;
use Carbon\Carbon;


class BarbeariaUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'barbearia_id',
        'payment_method',
        'card_id',
        'price',
        'plan_ends_at',
        'user_id',
    ];

    protected $appends = [
        'max_date'

     ];

    protected $casts = [
     'price' => PlanTypes::class,
        'payment_method' => PaymentMethods::class,
        'plan_ends_at' => 'datetime',
        'payment_methods_allowed' => 'array',
        'max_date' => 'datetime'


    ];



    public function workingHours()
    {
        return $this->hasMany(UserWorkingHours::class, "barbearia_user_id");
    }
public function agendamentos() {
    return $this->hasMany(Agendamento::class,"barbearia_user_id");

}

public function user() {
    return $this->belongsTo(User::class, "user_id");
}

public function getPayerInformations() {

}

public function getMaxDateAttribute() {
    switch ($this->optionMax) {
        case '15 Dias':
            return Carbon::now()->addDays(15);
            break;
        case '1 Mês':
            return Carbon::now()->addMonths(1);
            break;
        case '3 Meses':
            return Carbon::now()->addMonths(3);
            break;
        case '6 Meses':
            return Carbon::now()->addMonths(6);
            break;
        default:
            return null;
            break;
    }
}


public function barbearia() {
    return $this->belongsTo(Barbearia::class, "barbearia_id");
}
public function specificDates() {
    return $this->hasMany(SpecificDate::class, "barbearia_user_id");
}
public function cortes(){
    return $this->hasMany(UserCorte::class,"barbearia_user_id");
}

public function cortesBarbearia()
{
    return $this->belongsToMany(Cortes::class, 'user_corte', 'barbearia_user_id', 'corte_id');
}

public function getAllAvailableTimes($specificDate, $selectedAgendamento = null)
{
    $specificDateFormatted = Carbon::parse($specificDate);
    $specificDayOfWeek = $specificDateFormatted->dayOfWeek;

    $specificDateEntries = $this->specificDates()
        ->where('status', 'adicionar')
        ->whereDate('start_date', $specificDateFormatted->format('Y-m-d'))
        ->get();

    $removedDates = $this->specificDates()
        ->where('status', 'remover')
        ->get();

    $workingHours = $this->workingHours()
        ->where('day_of_week', $specificDayOfWeek)
        ->get();

     $intervalMinutes = $this->getIntervalInMinutes();
    $antecedeTime = $this->antecedence_time;
    list($hours, $minutes, $seconds) = explode(':', $antecedeTime);
    $antecedeTimeInMinutes = ($hours * 60) + $minutes;

    $now = Carbon::now()->addMinutes($antecedeTimeInMinutes);

    $availableTimes = [];

    foreach ($specificDateEntries as $specificDateEntry) {
        $startHour = Carbon::parse($specificDateEntry->start_date);
        $endHour = Carbon::parse($specificDateEntry->end_date);

        //De forma automatica
        $currentHour = clone $startHour;

        while ($currentHour < $endHour) {
            $currentDateTime = Carbon::parse($specificDate)->setTime($currentHour->hour, $currentHour->minute);
            $color = $this->getTimeColor($currentDateTime, $removedDates,  $now, $selectedAgendamento);
            $availableTimes[] = ['time' => $currentDateTime, 'color' => $color];

            $currentHour->addMinutes($intervalMinutes);
        }
    }

    foreach ($workingHours as $workingHour) {
        $startHour = Carbon::parse($workingHour->start_hour);
        $endHour = Carbon::parse($workingHour->end_hour);
        $currentHour = clone $startHour;
             $interval = $workingHour->intervals;
    $hasInterval = isset($interval['interval']);
    $intervalStart = $hasInterval ? Carbon::createFromFormat('H:i', $interval['interval']['start']) : null;
    $intervalEnd   = $hasInterval ? Carbon::createFromFormat('H:i', $interval['interval']['end']) : null;
        
       
        while ($currentHour < $endHour) {
            
            
                    // Pular horário dentro do intervalo de almoço
        if ($hasInterval) {
            $currentOnlyTime = Carbon::parse($currentHour->format('H:i'));

            if ($currentOnlyTime >= $intervalStart && $currentOnlyTime < $intervalEnd) {
                $currentHour->addMinutes($intervalMinutes);
                continue;
            }
        }

            $currentDateTime = Carbon::parse($specificDate)->setTime($currentHour->hour, $currentHour->minute);
            $color = $this->getTimeColor($currentDateTime, $removedDates,  $now, $selectedAgendamento);
            $availableTimes[] = ['time' => $currentDateTime, 'color' => $color];
               


            $currentHour->addMinutes($intervalMinutes);
        }




    }

    $formatadoData = $specificDateFormatted->format('Y-m-d');
$agendamentosFiltrados = $this->agendamentos->filter(function ($agendamento) use ($formatadoData) {

    $dataIgual = Carbon::parse($agendamento->start_date)->format('Y-m-d') === $formatadoData;

    $naoExpirado = $agendamento->created_at > now()->subHour();

    return 
        $dataIgual
        && (
            $agendamento->pago == 1           // pago (expirado ou não)
            || ($agendamento->pago == 0 && $naoExpirado) // não pago mas ainda válido
        );
});




    foreach ($agendamentosFiltrados as $agendamento) {
        $endDateTime = Carbon::parse($agendamento->end_date);
        $startDateTime = Carbon::parse($agendamento->start_date);




        $existingTimes = array_column($availableTimes, 'time');
        $color = $this->getTimeColor($startDateTime, $removedDates,  $now, $selectedAgendamento);




        $formattedExistingTimes = array_map(function($time) {
            return $time->format('Y-m-d H:i:s');
        }, $existingTimes);

        $formattedEndDateTime = $endDateTime->format('Y-m-d H:i:s');
        $formattedStartDateTime = $startDateTime->format('Y-m-d H:i:s');

        $alreadyAdded = false;
        foreach ($formattedExistingTimes as $existingTime) {
            if ($formattedEndDateTime == $existingTime  ) {
                $alreadyAdded = true;
                break;
            }
        }



        $alreadyAddedStart = false;
        foreach ($formattedExistingTimes as $existingTime) {
            if ($formattedStartDateTime === $existingTime  ) {
                $alreadyAddedStart = true;
                break;
            }
        }



        $maxClosingTime = null;

        if (isset($workingHour) && $workingHour->end_hour >= $endDateTime->format('H:i:s')) {
            $closingTime = Carbon::createFromTimeString($workingHour->end_hour)->format('H:i:s');
            if ($maxClosingTime === null || $closingTime > $maxClosingTime) {
                $maxClosingTime = $closingTime;
            }
        }

        // Verificar as datas específicas
        foreach ($specificDateEntries as $specificDate) {
            $endDate = Carbon::parse($specificDate->end_date)->format('H:i:s');
            if ($endDate > $endDateTime->format('H:i:s') && ($maxClosingTime === null || $endDate > $maxClosingTime)) {
                $maxClosingTime = $endDate;
            }
        }

        if (!$alreadyAdded && $maxClosingTime > $endDateTime->format('H:i:s')) {

            $availableTimes[] = ['time' => $endDateTime, 'color' => $color];


        }



        if (!$alreadyAddedStart && $maxClosingTime >= $endDateTime->format('H:i:s')) {
            $availableTimes[] = ['time' => $startDateTime, 'color' => $color];
        }


        foreach ($availableTimes as &$availableTime) {
            $existingTime = $availableTime['time']->format('Y-m-d H:i:s');

            if ($existingTime == $formattedEndDateTime && !$this->isTimeScheduled($formattedEndDateTime)) {
                $availableTime['color'] = ''; // Define a cor como vazia
            }

            if($existingTime == $formattedEndDateTime && $this->isTimeScheduled($formattedEndDateTime)) {

                if(isset($selectedAgendamento)) {
                    if(Carbon::parse(Agendamento::findOrFail($selectedAgendamento->id)->start_date)->format('Y-m-d H:i:s') === $formattedEndDateTime) {
                        $availableTime['color'] = 'black';
                    } else {
                        $availableTime['color'] = 'red';
                    }
                } else {
                    $availableTime['color'] = 'red';
                }


            }




        }

    }


    usort($availableTimes, function ($a, $b) {
        return $a['time'] <=> $b['time'];
    });

    return $availableTimes;
}

 // Função para obter a cor do horário
 public function getTimeColor($currentDateTime, $removedDates,  $now, $selectedAgendamento = null)
 {
     if ($currentDateTime < $now) {
         return 'red';
     }

     foreach ($this->agendamentos->filter(fn ($e) =>
         $e->pago == 1 
    || ($e->pago == 0 && $e->created_at > now()->subHour())               // ainda não foi pago
    ) as $horarioAgendado) {
         $startHorarioAgendado = Carbon::parse($horarioAgendado->start_date);
         $endHorarioAgendado = Carbon::parse($horarioAgendado->end_date);

         if ($currentDateTime >= $startHorarioAgendado && $currentDateTime < $endHorarioAgendado) {
             if ($selectedAgendamento && $horarioAgendado->id === $selectedAgendamento->id) {
                 return 'black'; // Retorna 'black' se for o agendamento selecionado
             } else {
                 return 'red'; // Retorna 'red' se o horário estiver reservado
             }
         }
     }

     foreach ($removedDates as $removedDate) {
         $startHorarioRemovido = Carbon::parse($removedDate->start_date);
         $endHorarioRemovido = Carbon::parse($removedDate->end_date);

         if ($currentDateTime >= $startHorarioRemovido && $currentDateTime < $endHorarioRemovido) {
             return 'red'; // Retorna 'red' se o horário estiver removido
         }
     }

     return ''; // Retorna vazio se o horário estiver disponível
 }


 public function isTimeScheduled($currentDateTime, $selectedAgendamento = null)
{

    foreach ($this->agendamentos->filter(fn ($e) =>
         $e->pago == 1 
    || ($e->pago == 0 && $e->created_at > now()->subHour())              // ainda não foi pago
    ) as $horarioAgendado) {
        $startHorarioAgendado = Carbon::parse($horarioAgendado->start_date)->format('Y-m-d H:i:s');



        if ($currentDateTime === $startHorarioAgendado) {

            return true;
            break;
        }


    }
    return false;
}

public function maquininhas() {
    return $this->hasMany(Maquininha::class);
}

public function isEndTimeExceeded($date, $endDateTime)
{
    $specificDateFormatted = Carbon::parse($date);
    $specificDayOfWeek = $specificDateFormatted->dayOfWeek;

    // Verifica se há horários de trabalho definidos para o dia específico da semana
    $workingHours = $this->workingHours()->where('day_of_week', $specificDayOfWeek)->first();

    // Inicializa a variável para armazenar o maior horário de fechamento
    $maxClosingTime = null;

    if ($workingHours) {
        // Obtém o horário de fechamento dos horários de trabalho
        $closingTime = Carbon::createFromTimeString($workingHours->end_hour);

        // Atualiza o maior horário de fechamento
        if ($maxClosingTime === null || $closingTime->gt($maxClosingTime)) {
            $maxClosingTime = $closingTime;
        }
    }

    // Verifica se existem datas específicas definidas para o dia específico
    $specificDates = $this->specificDates()
                          ->whereDate('start_date', $specificDateFormatted->format('Y-m-d'))
                          ->where('status', 'adicionar')
                          ->get();

    foreach ($specificDates as $specificDate) {
        $endDate = Carbon::parse($specificDate->end_date);

        // Atualiza o maior horário de fechamento
        if ($maxClosingTime === null || $endDate->gt($maxClosingTime)) {
            $maxClosingTime = $endDate;
        }
    }


    if ($maxClosingTime !== null && $endDateTime->format('H:i:s') > $maxClosingTime->format('H:i:s') || Carbon::parse($date)->day !== Carbon::parse($endDateTime)->day) {
        return true;
    }

    return false;
}

 public function getIntervalInMinutes()
 {
     // Assumindo que a propriedade $interval é uma string no formato "HH:mm:ss"
     list($hours, $minutes, $seconds) = explode(':', $this->interval);
     return $hours * 60 + $minutes;
 }


}
