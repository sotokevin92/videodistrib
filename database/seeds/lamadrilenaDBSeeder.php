<?php

use Illuminate\Database\Seeder;
use App\Pantalla;

class PantallasDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pantallas')->delete();

        Pantalla::create([
            'id' => 101,
            'descripcion' => 'LM Rawson',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 102,
            'descripcion' => 'LM Trelew (Centro)',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 103,
            'descripcion' => 'LM Pto. Madryn',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 104,
            'descripcion' => 'LM Comodoro (Centro)',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 105,
            'descripcion' => 'LM Comodoro (Loma)',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 106,
            'descripcion' => 'LM Caleta Olivia',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 107,
            'descripcion' => 'LM Viedma',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 108,
            'descripcion' => 'ESQUEL',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 109,
            'descripcion' => 'LM Trelew (Shopping)',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 201,
            'descripcion' => 'QNM Trelew (Centro)',
            'retrato' => true
        ]);

        Pantalla::create([
            'id' => 202,
            'descripcion' => 'QNM Viedma',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 203,
            'descripcion' => 'QNM Comodoro',
            'retrato' => true
        ]);

        Pantalla::create([
            'id' => 204,
            'descripcion' => 'QNM Rawson',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 205,
            'descripcion' => 'QNM Trelew (Shopping)',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 206,
            'descripcion' => 'QNM Caleta Olivia',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 208,
            'descripcion' => 'QNM Pto. Madryn',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 301,
            'descripcion' => 'Revoltijo',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 401,
            'descripcion' => 'ELOY Comodoro',
            'retrato' => false
        ]);

        Pantalla::create([
            'id' => 402,
            'descripcion' => 'ELOY Trelew',
            'retrato' => false
        ]);
    }
}
