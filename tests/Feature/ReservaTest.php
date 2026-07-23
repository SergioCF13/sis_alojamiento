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

    public function test_check_out_updates_the_departure_date_and_time_automatically(): void
    {
        $user = User::factory()->create();

        $cliente = Cliente::create([
            'nombre_completo' => 'Luis Pérez',
            'carnet_identidad' => '87654321',
            'celular' => '72222222',
        ]);

        $tipoHabitacion = TipoHabitacion::create([
            'nombre' => 'Simple',
            'descripcion' => 'Habitación sencilla',
            'precio' => 80.00,
        ]);

        $habitacion = Habitacion::create([
            'numero' => '202',
            'piso' => 2,
            'estado' => 'Disponible',
            'tipo_habitacion_id' => $tipoHabitacion->id,
        ]);

        $reserva = $this->actingAs($user)->post(route('reservas.store'), [
            'cliente_id' => $cliente->id,
            'habitacion_id' => $habitacion->id,
            'fecha_ingreso' => '2026-08-05',
            'hora_ingreso' => '10:00',
            'fecha_salida' => '2026-08-06',
            'hora_salida' => '11:00',
            'cantidad_persona' => 1,
            'estado' => 'Check-in',
            'observaciones' => 'Estancia en curso',
        ]);

        $expectedDate = now()->toDateString();
        $expectedTime = now()->format('H:i');

        $response = $this->actingAs($user)->post(route('reservas.cambiarEstado', $this->app->make('db')->table('reservas')->latest('id')->first()->id), [
            'estado' => 'Check-out',
            '_token' => csrf_token(),
        ]);

        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('reservas', [
            'estado' => 'Check-out',
            'fecha_salida' => $expectedDate,
            'hora_salida' => $expectedTime,
        ]);
    }
}
