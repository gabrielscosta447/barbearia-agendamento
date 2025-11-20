<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $casts = [
           'start_date' => 'datetime',
           'end_date' => 'datetime'
    ];
    protected $fillable = [
        
        'id_pix',
        'qrcode',
        'payload'
    
        
    ];

    public function user(){
         return $this->belongsTo(BarbeariaUser::class,"barbearia_user_id");
    }

    public function colaborador()
    {
        return $this->belongsTo(BarbeariaUser::class, "barbearia_user_id")->withTrashed();
    }
    public function cortes() {
        return $this->belongsToMany(UserCorte::class, 'agendamentos_cortes', 'agendamento_id', 'user_corte_id');
    }

    public function owner(){
         return $this->belongsTo(User::class,"owner_id");
    }

    public function maquininha() {
        return $this->belongsTo(Maquininha::class, "maquininha_id");
    }

   protected function pagamentoExpirado(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Exemplo: pagamento expira 1 hora após o created_at
                return Carbon::now()->greaterThan(
                    $this->created_at->addHour()
                );
            }
        );
    }



}
