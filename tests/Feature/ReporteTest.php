<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\TipoHabitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReporteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_generate_a_pdf_report_for_a_date_range(): void
    {
        $user = User::factory()->create();

        $cliente = Cliente::create([
            'nombre_completo' => 'Juan Pérez',
            'carnet_identidad' => '1111111',
            'celular' => '70000000',
        ]);

        $tipo = TipoHabitacion::create([
            'nombre' => 'Suite',
            'descripcion' => 'Habitación de lujo',
            'precio' => 200.00,
        ]);

        $habitacion = Habitacion::create([
            'numero' => '301',
            'piso' => 3,
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
            'estado' => 'Confirmada',
            'observaciones' => 'Estancia confirmada',
        ]);

        Pago::create([
            'reserva_id' => $reserva->id,
            'monto' => 500.00,
            'metodo_pago' => 'Transferencia',
            'estado' => 'Pagado',
            'fecha_pago' => '2026-08-10',
            'observaciones' => 'Pago principal',
        ]);

        $response = $this->actingAs($user)->get(route('reportes.pdf', [
            'fecha_inicio' => '2026-08-01',
            'fecha_fin' => '2026-08-30',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
