<?php

namespace Database\Seeders;

use App\Emails;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\TipoDoc;
use App\Traits\ManageSeeder;
use Illuminate\Database\Seeder;

class MProductInsSeeder extends Seeder
{
    use ManageSeeder;

    public $doctypes, $groups, $countries, $domains;
    public $estudiantes = [];

    public function __construct()
    {
        $this->doctypes = TipoDoc::select(["id", "tipo_doc as name"])->get();
        $this->groups = \DB::table('est_grupos')->select(["id", "codigo as code", "nombre as name"])->get();
        $this->countries = \DB::table('country')->select('name','phonecode','nicename')->get();
        $this->domains = \DB::table('tb_email_permitos')->select(["id", "nombre as name", "dominio as domain"])->get();
        $this->getDefaultFields();
    }

    public function loadData($product_id, $index)
    {
        $est = $this->estudiantes[$index];
        $m_product = MProduct::find($product_id);
        $faker = \Faker\Factory::create();
        //$attrs = MCategory_Field::whereMCategoryId($category_id)->whereIsDetail(0)->get();
        $df = $this->getDefaultFields();
        $doctype = $this->doctypes->random();
        $grados = [1=>"TÍTULO","MAESTRÍA","DOCTORADO"];
        $grado1 = $faker->randomElement([1, 2, 3]);
        $grado2 = $grados[$grado1];
        $data = [
            $df["tipo_doc"] => $doctype->id,
            $df["dni"]  => $est["dni_doc"],//$faker->numberBetween(11111111,88888888),
            $df["grupo"] => $this->groups->random()->code,
            $df["nom"] => $est["nombres"],//$faker->firstName(),
            $df["apepat"] => $est["ap_paterno"],// $faker->lastName(),
            $df["apemat"] => $est["ap_materno"],// $faker->lastName(),
            $df["pais"] => $this->countries->random()->name,
            $df["departamento"] => "LIMA",
            $df["grado-profesion"] => $grado1,
            $df["entidad"] => "Entidad ".$faker->numberBetween(1,10),
            $df["cargo"] => "Cargo ".$faker->numberBetween(1,10),
            $df["email"] => $faker->email(),
            $df["celular"] => $faker->phoneNumber(),
            $df["tipo_doc"]."_t" => $doctype->name,
            $df["grado-profesion"]."_t" => $grado2,
        ];
        MProductIns::factory(1)->create([
            'm_product_id' => $product_id,
            'm_category_id' => $m_product->m_category_id,
            'data' => json_encode($data),
            'm_est_id' => $est["id"]//$index
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $product_count = 30;
        $index = 0;
        for($i=1; $i<=$product_count;$i++){
            $count = $faker->numberBetween(10,20)*100;
            $this->estudiantes = \App\Estudiante::select("id", "dni_doc")->where("email","!=","")->inRandomOrder()->limit($count)->get();

            for($j=0; $j<$count;$j++){
                $index++;
                //$this->loadData($i, $index);
                $this->loadData($i, $j);
            }
            \DB::table('m_products')->where('m_category_id', 1)->where('id', $i)->update(['inscritos'=> $count]);
        }
    }
}
