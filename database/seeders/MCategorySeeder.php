<?php

namespace Database\Seeders;

use App\Models\MCategory;
use Illuminate\Database\Seeder;

class MCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MCategory::factory(1)->create([
            'slug' => 'eventos-2022',
            'name' => 'Eventos 2022',
            'visible' => true
        ]);
    }
}
