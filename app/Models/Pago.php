<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'reserva_id',
        'monto',
        'metodo_pago',
        'estado',
        'fecha_pago',
        'observaciones',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
