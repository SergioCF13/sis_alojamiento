<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_direct_check_in_without_a_reserved_room(): void
    {
        $user = User::factory()->create();

        $cliente = Cliente::create([
            'nombre_completo' => 'Ana López',
            'carnet_identidad' => '12345678',
            'celular' => '71111111',
        ]);

        $tipoHabitacion = TipoHabitacion::create([
            'nombre' => 'Doble',
            'descripcion' => 'Habitación para dos personas',
            'precio' => 120.00,
        ]);

        Habitacion::create([
            'numero' => '101',
            'piso' => 1,
            'estado' => 'Disponible',
            'tipo_habitacion_id' => $tipoHabitacion->id,
        ]);

        $response = $this->actingAs($user)->post(route('reservas.store'), [
            'cliente_id' => $cliente->id,
            'habitacion_id' => null,
            'fecha_ingreso' => '2026-08-01',
            'hora_ingreso' => '14:00',
            'fecha_salida' => '2026-08-03',
            'hora_salida' => '12:00',
            'cantidad_persona' => 2,
            'estado' => 'Check-in',
            'observaciones' => 'Llegada directa al alojamiento',
        ]);

        $response->assertRedirect(route('reservas.index'));
        $this->assertDatabaseHas('reservas', [
            'cliente_id' => $cliente->id,
            'habitacion_id' => null,
            'estado' => 'Check-in',
        ]);
    }
}
