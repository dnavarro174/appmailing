<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

use DB;use Cache;
use App\Estudiante;
use App\Imaddjj;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\CampaniasController;
use App\AccionesRolesPermisos;
use Auth;

class TestController extends Controller
{   
    

    
    public function index(){

        $object = json_decode(json_encode([["id"=>1, "nombre"=>"Diego"]]), FALSE);
        //$this->trunca();

        //return $object;
        $clave = Hash::make("DanyN123");
        $db = DB::connection()->getDatabaseName();
        //$db = Config::get('database.connections.'.Config::get('database.default'));
        //$databaseName = Config::get('database.connections')['mysql']['database'];
        $nombre ="nombre";

        return compact("clave","db","nombre");

    }
    public function trunca(){
        DB::table('campanias')->truncate();
        DB::table('jobs')->truncate();
        DB::table('failed_jobs')->truncate();
        DB::table('history_emails')->truncate();
        DB::table('history_enviados')->truncate();
    }
    public function info(Request $request)
    {
        phpinfo();
        #php artisan about

        //Mismo proceso usando modelo

        //GRABAR
        $nombre = 'Producto2';
        $atributos = [
            'color' => 'azul',
            'size' => 'L'
        ];
        /*$c = Descripcione::create([
            'nombre' => $nombre,
            'atributos' => $atributos,
        ]);*/
        #dd($c);


        // RECUPERAR
        # $c = DB::table('descripciones')->find(1);
        //recuperar los valor del json almacenado
        /*$attr = json_decode($c->atributos, true);
        #dd($attr['color']);

        //GRABAR
        $nombre = 'Producto1';
        $atributos = [
            'color' => 'rojo',
            'size' => 'L'
        ];
        $c = DB::table('descripciones')->insert([
            'nombre' => $nombre,
            'atributos' => json_encode($atributos),
        ]);
        dd($c);

    
        $estudiantes_datos = Estudiante::join('estudiantes_act_detalle as d','d.estudiantes_id','=','estudiantes.dni_doc')->
        limit(10)->orderBy('estudiantes.id','desc')->get();

        //$da = Imaddjj::find(3308);
        $da = Imaddjj::limit(5)->orderBy('id','desc')->get();
        //dd($da);
        //->CursoDJ;

        return "Test";

        /* sql Relaciones con los modelos de Laravel 7 */
        
        //TestJob::dispatch($data)->onConnection('database')->onQueue("pruebas")->delay(Carbon::now()->addMinutes(0.2));;

        // $q = CampaniasController::generateQuery ($this->postData);
        // $estudiantes_count = $q->get()->first()->estudiantes??0;


        /* select simple
        $q = DB::select('select * from estudiantes limit 2');
        $link       = url('').'?id=127&t=obs&d=123';
        dd($link); */

        /* get variables .env
        dd(env('MAIL_FROM_ADDRESS'));
        dd(env('MAIL_FROM_NAME')); */

        // consultas sql:
        /*select `estudiantes`.`dni_doc` as `estudiantes_id`, `estudiantes`.`id`, `estudiantes`.`email`, `estudiantes`.`nombres`, `estudiantes`.`ap_paterno`, `estudiantes`.`ap_materno`, `estudiantes`.`codigo_cel`, `estudiantes`.`celular`, `estudiantes`.`accedio` from `estudiantes` inner join `estudiantes_act_detalle` as `de` on `de`.`estudiantes_id` = `estudiantes`.`dni_doc` where `de`.`eventos_id` = '257' and `estudiantes`.`email` NOT LIKE '%s' and `estudiantes`.`email` NOT LIKE '%notiene@correo.com%' and `estudiantes`.`email` NOT LIKE '%notienecorreo@nocorreo.com%' and `estudiantes`.`email` NOT LIKE '%notiene@correo.com%' and `estudiantes`.`estado` = 1 and (`estudiantes`.`email` is not null and `estudiantes`.`email` != '')  group by `estudiantes`.`email` order by `estudiantes`.`nombres` desc;
        */

        /* select * from history_emails;
        select * from jobs;
        select * from failed_jobs; */
    }
    //
}
