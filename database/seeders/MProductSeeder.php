<?php

namespace Database\Seeders;

use App\Emails;
use App\Models\MCategoryField;
use App\Models\MProduct;
use App\Traits\ManageSeeder;
use Illuminate\Database\Seeder;

class MProductSeeder extends Seeder
{
    use ManageSeeder;
    public $emails;

    public function __construct()
    {
        $this->emails = Emails::select(["id", "nombre as name", "email"])->orderBy("nombre",'asc')->get();
        $this->getDefaultFields();
    }
    public function loadData($category_id)
    {
        $faker = \Faker\Factory::create();
        $visible = MCategoryField::whereMCategoryId($category_id)->whereIsDetail(1)->pluck('id')->toArray();
        $df = $this->getDefaultFields();
        $data = [
            $df["_nombre"] => $faker->streetName(),
            $df["_description"] => $faker->text(50),
            //"_3" => $faker->sentence(3),
            $df["_fecha_ini"] => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '-1 day', $timezone = null)->format("Ymd"),
            $df["_hora_ini"] => $faker->time("H:i"),
            $df["_fecha_fin"] => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 year', $timezone = null)->format("Ymd"),
            $df["_hora_fin"] => $faker->time("H:i"),
            $df["_lugar"] => $faker->city(),
            $df["_vacantes"] => $faker->numberBetween(10,50)*10,
            //"_7" => [0, 1][$faker->numberBetween(0,1)],
            $df["_confirmacion"] => $faker->randomElement(["SI", "NO"]),
            $df["_asunto"] => $faker->text(50),
            $df["_email_desde"] => $this->emails->random()->id,
            $df["_confirmacion_por"] => $faker->randomElement([["1"], ["2"], ["1", "2"]]),
            $df["_confirmacion_reg"] => "",
            $df["_evento_h"] => "",
            $df["_recordatorio_h"] => "",
            $df["_cerrado_reg"] => "",
            $df["_gafete"] => $faker->randomElement(["SI", "NO"]),
            $df["_plantilla"] => "",
            $df["_im_header"] => "",
            $df["_im_footer"] => "",
        ];
        MProduct::factory(1)->create([
            'm_category_id' => $category_id,
            'data' => json_encode($data),
            'visible' => json_encode($visible),
        ]);



    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<30;$i++)
            $this->loadData(1);
//        MProduct::factory(10)->create([
//            'm_category_id' => 1,
//            'data' => "{}",
//        ]);
    }
}
