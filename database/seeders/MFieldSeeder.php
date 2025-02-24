<?php

namespace Database\Seeders;

use App\Models\MField;
use Illuminate\Database\Seeder;

class MFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        MField::factory(1)->create([
            'name' => 'Texto',
        ]);
        MField::factory(1)->create([
            'name' => 'Area de Texto',
        ]);
        MField::factory(1)->create([
            'name' => 'Numerico',
        ]);
        MField::factory(1)->create([
            'name' => 'Correo',
        ]);

        MField::factory(1)->create([
            'name' => 'Radio',
        ]);
        MField::factory(1)->create([
            'name' => 'Checkbox',
        ]);

        MField::factory(1)->create([
            'name' => 'Fecha',
        ]);
        MField::factory(1)->create([
            'name' => 'Hora',
        ]);
        MField::factory(1)->create([
            'name' => 'Fecha Hora',
        ]);
        MField::factory(1)->create([
            'name' => 'Geoposicion',
        ]);
        MField::factory(1)->create([
            'name' => 'Lista desplegable',
        ]);
        MField::factory(1)->create([
            'name' => 'Lista',
        ]);

        MField::factory(1)->create([
            'name' => 'Grupo de radio',
        ]);
        MField::factory(1)->create([
            'name' => 'Grupo de check',
        ]);

        MField::factory(1)->create([
            'name' => 'Fichero',
        ]);

        MField::factory(1)->create([
            'name' => 'Etiqueta',
        ]);
        MField::factory(1)->create([
            'name' => 'Preguntas SI/NO',
        ]);
        MField::factory(1)->create([
            'name' => 'Experiencia Lab',
        ]);
        MField::factory(1)->create([
            'name' => 'Experiencia Doc',
        ]);
        MField::factory(1)->create([
            'name' => 'Boton',
        ]);
    }
}
