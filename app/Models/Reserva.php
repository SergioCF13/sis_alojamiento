<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'cliente_id',
        'habitacion_id',
        'fecha_ingreso',
        'hora_ingreso',
        'fecha_salida',
        'hora_salida',
        'cantidad_persona',
        'estado',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
