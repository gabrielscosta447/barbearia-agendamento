<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Customer\CustomerCardClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Cashier\Billable;

class User extends Authenticatable /* implements MustVerifyEmail */
{
    use Billable;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
        'provider_avatar', 'provider_id',
        'provider_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
       
    ];


    public function barbeariasOwned(){
         return $this->hasMany(Barbearia::class,"owner_id");
    }

    public function barbeariasWorking() {
        return $this->belongsToMany(Barbearia::class, "barbearia_users", "user_id", "barbearia_id");
    }


    public function workingHours() {
        return $this->hasMany(UserWorkingHours::class, "user_id");
    }
   public function cortes(){
        return $this->belongsToMany(Cortes::class,"user_corte","user_id","corte_id");
   }

    public function specificDates() {
        return $this->hasMany(SpecificDate::class, "user_id");
    }

    public function getMercadoPagoCards()
    {
        try {
            // Defina seu token de acesso do MercadoPago
            MercadoPagoConfig::setAccessToken(env("MERCADO_PAGO_ACCESS_TOKEN"));
    
            // Verifique se os cartões estão armazenados em cache
            $cachedCards = Cache::remember('mercado_pago_cards_' . $this->payer_id, now()->addHours(6), function () {
                // Se não estiverem em cache, busque do MercadoPago
                $client = new CustomerCardClient();
                if ($this->payer_id) {
                    $resposta = $client->list($this->payer_id);
                    return $resposta->data;
                } else {
                    return [];
                }
            });
    
            return $cachedCards;
        } catch (\Exception $e) {
            // Se ocorrer uma exceção, capture-a e retorne null
            dd($e);
            return null;
        }
    }


   
     

    public function eventos(){
         return $this->hasMany(Agendamento::class,"owner_id");
    }


 

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class);
    }

    public function avaliacoes(){
        return   $this->hasMany(Avaliacao::class,"user_id");
    }
    public function   clientes(){
        return   $this->hasMany(Cliente::class,"user_id");
    }

    public function promocoes(){
         return $this->belongsToMany(Promocao::class, "user_promocoes","user_id","promocao_id");
    }
}
