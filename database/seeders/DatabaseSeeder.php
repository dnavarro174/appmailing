<?php

namespace Database\Seeders;

use App\Models\MAttr;
use App\Models\MCategory;
use App\Models\MCategoryField;
use App\Models\MField;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\Traits\ManageModules;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    use ManageModules;

    public function run()
    {
        //\App\Models\User::factory(1)->create(["email"=>"dnavarro174@gmail.com"]);
        //\App\Models\User::factory(9)->create();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        MCategory::truncate();
        MField::truncate();
        MAttr::truncate();
        MCategoryField::truncate();
        MProduct::truncate();
        MProductIns::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->call(MCategorySeeder::class);
        $this->call(MFieldSeeder::class);
        $this->call(MAttrSeeder::class);
        $this->call(MCategoryFieldSeeder::class);
        $this->call(MProductSeeder::class);
        $this->call(MProductInsSeeder::class);
        $df = $this->getDefaultFields();
        \DB::statement('UPDATE m_product_ins SET data = JSON_SET(data, "$.'.$df["dni"].'", (SELECT dni_doc FROM estudiantes WHERE id = m_product_ins.m_est_id))');
        // \App\Models\User::factory(10)->create();
    }
}
