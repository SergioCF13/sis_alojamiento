<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\TipoHabitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagoTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_a_payment_for_a_reservation(): void
    {
        $user = User::factory()->create();

        $cliente = Cliente::create([
            'nombre_completo' => 'Carlos Perez',
            'carnet_identidad' => '87654321',
            'celular' => '70000000',
        ]);

        $tipo = TipoHabitacion::create([
            'nombre' => 'Matrimonial',
            'descripcion' => 'Habitación matrimonial',
            'precio' => 150.00,
        ]);

        $habitacion = Habitacion::create([
            'numero' => '202',
            'piso' => 2,
            'estado' => 'Disponible',
            'tipo_habitacion_id' => $tipo->id,
        ]);

        $reserva = Reserva::create([
            'cliente_id' => $cliente->id,
            'habitacion_id' => $habitacion->id,
            'fecha_ingreso' => '2026-08-10',
            'hora_ingreso' => '14:00',
            'fecha_salida' => '2026-08-12',
            'hora_salida' => '12:00',
            'cantidad_persona' => 2,
            'estado' => 'Reserva',
            'observaciones' => 'Reserva con pago inicial',
        ]);

        $response = $this->actingAs($user)->post(route('pagos.store'), [
            'reserva_id' => $reserva->id,
            'monto' => 300.00,
            'metodo_pago' => 'Efectivo',
            'estado' => 'Pagado',
            'fecha_pago' => '2026-08-10',
            'observaciones' => 'Pago inicial',
        ]);

        $response->assertRedirect(route('pagos.index'));
        $this->assertDatabaseHas('pagos', [
            'reserva_id' => $reserva->id,
            'monto' => 300.00,
            'estado' => 'Pagado',
        ]);
    }
}
