<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $agendamentos = Agendamento::whereHas('owner', function ($query) {
                $query->whereNotNull('token');
            })->get();

            foreach ($agendamentos as $agendamento) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->start_date);
                $now = Carbon::now();

                if ($start_date->diffInMinutes($now) == 60) {
                    $firebaseToken = $agendamento->owner->token;

                    $pvKeyPath = public_path('pvKey.json');
                    $credential = new ServiceAccountCredentials(
                        "https://www.googleapis.com/auth/firebase.messaging",
                        json_decode(file_get_contents($pvKeyPath), true)
                    );

                    $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

                    $start_date_formatted = $start_date->format('d/m/Y H:i');

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token['access_token']
                    ])->post('https://fcm.googleapis.com/v1/projects/barbearia-agendamento-7fe43/messages:send', [
                        "message" => [
                            "token" => $firebaseToken,
                            "notification" => [
                                "title" => "Falta uma Hora para o seu agendamento!",
                                "body" => "Data: " . $start_date_formatted,
                                "image" => "http://localhost:8000/" . $agendamento->colaborador->barbearia->imagem
                            ],
                            "webpush" => [
                                "fcm_options" => [
                                    "link" => "http://localhost:8000/home?tab=pills-contact8"
                                ]
                            ]
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in NotificationJob: ' . $e->getMessage(), [
                'exception' => $e,
                'stack' => $e->getTraceAsString()
            ]);
        }
    }
}
