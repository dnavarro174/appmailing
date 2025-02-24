<?php

namespace App\Http\Controllers;

use App\Departamento;
use App\Estudiante;
use App\Exports\GeneralExport;
use App\Models\MCategory;
use App\Models\MCategoryField;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\TipoDoc;
use App\Traits\ManageExcel;
use App\Traits\ManageModules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
class ModuloLeadController extends Controller
{
    use ManageModules;
    use ManageExcel;

    public function index(Request $request, $m_category_id, $m_product_id)
    {
        $pref = "_{$m_category_id}";
        if($request->get('pag')){
            \Cache::flush();
            session(["{$pref}_pag"=> $request->get('pag') ]);

        }
        $pag = session("{$pref}_pag")??15;

        $modulo = MCategory::find($m_category_id);
        $campos = MCategoryField::where("m_category_id", $m_category_id)->where("is_detail", 1)->orderBy("position")->get();
        $doctypes = TipoDoc::select(["id", "tipo_doc as name"])->get();

        $s = request()->query("s");
        $g = request()->query("g");
        $export = $request->get('export');
        if(request()->isMethod('get') && !request()->getQueryString()){
            session([
                "{$pref}_s"=> "",
                "{$pref}_g"=> "",
            ]);
        }elseif($s || $g){
            session([
                "{$pref}_s"=> $s,
                "{$pref}_g"=> $g,
            ]);
        }
        $input = [
            "s" => session("{$pref}_s")??"",
            "g" => session("{$pref}_g")??"",
        ];
        $grupos = DB::table('est_grupos')->get();

        $q = $this->inscritosLead($m_category_id, $g, $s, $campos) ;
        $q->where("m_product_id", $m_product_id);


        if($export==1){
            return $this->exportaXLS($q, $campos, $grupos, $doctypes);
        }
        $q->orderBy("id", "desc");
        $inscritos = $q->paginate($pag);

        $product = MProduct::find($m_product_id);

        $tipos  = DB::table('estudiantes_tipo')->get();


        $permisos = [];
        $permisos['nuevo']['permiso'] = 1;
        $permisos['exportar_importar']['permiso'] = 1;
        $permisos['reportes']['permiso'] = 1;
        $permisos['eliminar']['permiso'] = 1;
        $permisos['editar']['permiso'] = 1;
        $vencido = 1;
        $estudiantes_datos = [];
        return view('moduloslead.index', compact( 'input','inscritos', 'campos','permisos', 'product',  'modulo', 'grupos', 'tipos', 'doctypes' , 'vencido'));
    }

    function exportaXLS($q, $campos, $grupos, $doctypes){
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        $path ='reports/';
        $type='xlsx';
        $nom_file="Lista";
        $file = "{$nom_file}.{$type}";
        $filename = "{$path}{$file}";
        $hs = [];
        if(count($campos)>0){
            foreach ($campos as $campo){
                if($campo->visible&&!in_array($campo->m_field_id, [12,14,15, 16, 17, 18, 19,20]))array_push($hs, $campo->title);
            }
        }
        $headers = [ $hs ];
        $colWidths = [
            'A'     =>  12,
            'B'     =>  35,
            'C'     =>  25,
            'D'     =>  25,
            'E'     =>  25,
            'F'     =>  25,
            'G'     =>  15,
            'H'     =>  20,
            'I'     =>  15,
            'J'     =>  15,
            'K'     =>  30,
            'L'     =>  20,
            'M'     =>  15,
        ];
        $colFormats = [
            'A'     =>  "@"
        ];
        $styles = [
            1 =>  [
                'font' => ['bold' => true, 'color' => ['argb' => 'ffffff']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00458B',
                    ]
                ],
            ]
        ];

        $data = [];
        $rows = $q->get();
        if($rows->count()>0){
            foreach($rows as $datos){
                $dd = [];

                if(count($campos)>0) {
                    foreach ($campos as $campo) {
                        if ($campo->visible&&!in_array($campo->m_field_id, [12, 14, 15, 16, 17, 18, 19,20]))
                            array_push($dd,
                                $datos->formatValue($campo, ["groups"=>$grupos, "doctypes"=>$doctypes])
                            );
                    }
                }
                $data[] = $dd;
            }
        }
        $h = date("d-m-Y");
        return \Excel::download( new GeneralExport( compact("rows", "headers", "styles", "data", "colWidths", "colFormats") ), "reporte_{$h}.xlsx");
    }


        public function createEdit($m_category_id, $m_product_id, $id=0)
    {
        $modulo = MCategory::find($m_category_id);
        $product = MProduct::find($m_product_id);

        [$recs, $ins] = $this->getRecIns($m_category_id);
        $ins_chunk = $ins->chunk(3);

        //$estypes  = DB::table('estudiantes_tipo')->get();
        $data = $this->getValuesElements();

        //$product_data = $this->getProductArrayData($m_product_id);
        $product = $this->getProductArray($m_product_id);
        $product_data = $product["data"];
        $visible_data = $product["visible"];

        $df = $this->getDefaultFields();


        $ins_data = $this->getInsArrayData($m_product_id, $id);
        $header_img = $this->getImageData($product_data, $m_category_id, $df["_im_header"]);
        $footer_img = $this->getImageData($product_data, $m_category_id, $df["_im_footer"]);

        $requireds = $this->getRequireds($ins);
        $fields = $requireds->mapWithKeys(function($v, $key) {
            return [$v->field => $v->id];
        });

        $datajs = $this->getJSData($ins, "content-", "inp-");
        $linesjs = $datajs["codes"];
        $eventsjs = $datajs["events"];
        $editors1 = $datajs["editors1"];

        $ins3 = $ins;
        $DF = $this->getDefaultFields();
        $title_attrx = $DF["_nombre"]??"";
        $title_attr_id = str_replace("_","",$title_attrx);
        $title_attr = $recs->where("m_attr_id", $title_attr_id)->first();
        $st = $title_attr->st();
        $title = [
            "text" => $product_data[$title_attrx] ?? "",
            "color" => $st["c"],
            "font" => $st["f"],
            "size" => $st["s"],
        ];

        $selects = $this->getListDependentSelects($ins);

        return compact("m_category_id","id", "recs", "fields", "ins3", "requireds", "linesjs", "eventsjs", "ins_chunk", "data", "m_product_id", "product_data", "visible_data","ins_data", "header_img", "footer_img", "DF", "editors1", "title", "selects");
    }

    public function ubigeo(Request $request)
    {

        //DEPARTAMENTO
        //
        //PROVINCIA
        //DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $id.'%','id2' => $id, 'id3' => 4]);
        $rows = [];
        if($request->ajax()){
            $inputs = $request->input('data');

            $type = $request->input('type');
            $departament_id = 0;
            //$pais = 'PERU';
            if(isset($inputs['data'])&&isset($inputs['data'][4]))$departament_id=$inputs['data'][4];
            //$paises = DB::table('country')->select('id','name')->get();
            //$paises = DB::table('country')->select('id','name')->get();
            if($type==3)$dd = DB::table('ubigeos')->select('ubigeo_id as id', 'nombre')
                ->where('ubigeo_id','<=',25)
                ->whereRaw('CHARACTER_LENGTH(ubigeo_id)= 2')->get();
            if($type==4 || $type==5||$type==8){
                $tt1 = [3, 4, 5, 8];
                $tt2 = ['', 'departamento', 'provincia', 'distrito'];
                $index = array_search($type, $tt1);

                $value = $inputs[$type];
                $select =  $tt2[$index];
                $dependent =  $tt2[$index+1];
                $dd = DB::table('ubigeos_peru')
                    ->select('id', $dependent.' as nombre')
                    ->where($select, $value)
                    ->groupBy($dependent)
                    ->get();
            }
            return response()->json($dd);
        }

        return $rows;
    }

    public function create($m_category_id, $m_product_id)
    {
        $data = $this->createEdit($m_category_id, $m_product_id);

        if($this->isClose($data["product_data"], $m_category_id, $m_product_id))return redirect()->route('mlead.cerrado', [$m_category_id, $m_product_id]);

        return view('moduloslead.form', $data );
    }
    public function edit($m_category_id, $m_product_id, $id=0)
    {
        $data = $this->createEdit($m_category_id, $m_product_id, $id);
        if($this->isClose($data["product_data"], $m_category_id, $m_product_id))return redirect()->route('mlead.cerrado', [$m_category_id, $m_product_id]);
        return view('moduloslead.form', $data );
    }
    public function store(Request $request, $m_category_id, $m_product_id)
    {
        $inputs = $request->input("inputs");
        $ifields = $request->input("f");
        $visibles = $request->input("v");
        $id = $request->input("id") ?? 0;

        [$recs, $ins] = $this->getRecIns($m_category_id);
        //$modulo = MCategory::find($m_category_id);
        $product_data = $this->getProductArrayData($m_product_id);
        $ins_data = $this->getInsArrayData($m_product_id, $id);
        $ddi = $this->inputsForSave($ins, $inputs, $request, $m_category_id, $ins_data, $id);
        $inputsd = $ddi["inputs"];
        $d = [
            'm_product_id' => $m_product_id,
            'm_category_id' => $m_category_id,
            'data' => json_encode($inputsd),
        ];
        //            'm_est_id' => $index
        //REVISAR
        //$ins = MProductIns::updateOrCreate(["id"=>$id, "m_product_id" => $m_product_id, "m_category_id" => $m_category_id],$d);
        //$this->renameFiles($ddi["editors"], "m/ed2/", "{$m_product_id}-{$ins->id}-");

        $this->getDefaultAttrFields($m_category_id);
        $confirmacion = $this->getConfirm($m_product_id);

        $df = $this->getDefaultFields();
        $confirmacion_por_xx = $df["_confirmacion_por"] ?? "";
        $confirmacion_por = array_key_exists($confirmacion_por_xx, $product_data)?$product_data[$confirmacion_por_xx]:[];

        $confirmacion_xx = $df["_confirmacion"] ?? "";
        $confirmacion = array_key_exists($confirmacion_xx, $product_data)?$product_data[$confirmacion_xx]:[];



        $email_x = $df["_email_desde"]??"0";
        $email_id = 0;
        $from_desde = [];
        if(array_key_exists($email_x, $product_data)){
            $email_id = $product_data[$email_x];
            if($email_id>0){
                $emails = \App\Emails::findOrFail($email_id);
                $from_desde = [$emails->email, $emails->nombre];
            }

        }

        $est = $this->getFieldStudent($ddi["inputs"]);

        //$product_data[$DF['_nombre']]
        $es_nuevo = $id == 0;

        $DF = $this->getDefaultFields();
        $q = Estudiante::where("dni_doc", $est["dni_doc"]);
        $data_est = $q->get();
        $check_est = $data_est->count();
        $m_est_id = $check_est ? $data_est->first()->id: 0;
//            if(!is_null($request->check_auto)){
//                // no check
//            }else{
//                // si acepta : Autorizo de manera expresa
//            }
        if($check_est == 0 ){
            DB::table('estudiantes')->insert($est);
            $m_est_id = DB::getPdo()->lastInsertId();
        }else{//->where('de.data->'.$df["dni"],$id)
            $q_check = MProductIns::where('m_product_id', $m_product_id)->where("m_category_id", $m_category_id)
                ->where('data->'.$DF["dni"],$est["dni_doc"]);
            if(!$es_nuevo)$q_check = $q_check->where('id', '!=' , $id);
            $check_est = $q_check->count();
            if($check_est >= 1){//REVISAR CAMPO
                $route_name = "mlead.create";
                $route_params = [$m_category_id, $m_product_id];
                if(!$es_nuevo){
                    $route_name = "mlead.edit";
                    array_push($route_params, $id);
                }
                return redirect(route($route_name, $route_params))->with('dni', 'Sus datos ya se encuentran registrados.');
            }
            DB::table('estudiantes')->where('dni_doc',$est["dni_doc"])->update($est);
        }
        if($es_nuevo){
            DB::table('m_products')->where('m_category_id', $m_category_id)->where('id', $m_product_id)->increment('inscritos', 1);
            $check_new = $this->checkInsertNewsLetter($est["dni_doc"]);
        }
        $d["m_est_id"] = $m_est_id;
        if($id == 0)
            $ins = MProductIns::create($d);
        else{
            unset($d["m_category_id"]);
            unset($d["m_product_id"]);
            $ins = MProductIns::where( compact("id", "m_product_id", "m_category_id"))->update($d);
        }
        //ERROR por key multiple
        //$ins = MProductIns::updateOrCreate($cond,$d);
        $ins_id = $id == 0? DB::getPdo()->lastInsertId(): $id;

        $this->renameFiles($ddi["editors"], "m/ed2/", "{$m_product_id}-{$ins_id}-");
        if($es_nuevo){//NUEVO

        }
        //ENVIAR EMAIL
        $plantilla = $this->getModuloPlantilla($m_category_id);
        $est["nombre"]= $est["nombres"];
        $est["dni_url"]= $est["tipo_documento_documento_id"];
        $est["nombres"]= $est["nombre"]. ' '. $est["ap_paterno"]. ' '. $est["ap_materno"];
        $params = $this->getParamValues($m_category_id, $m_product_id, $ins_id, $est);

        $content = "";
        $gafete_html="";

        $xx = $this->getFieldAttrId("_evento_h", $recs);
        $vv = $recs[$xx["index"]];
        $content = $vv->getFileText($m_product_id, $vv->id);

        $xx = $this->getFieldAttrId("_recordatorio_h", $recs);
        $vv = $recs[$xx["index"]];
        $content2 = $vv->getFileText($m_product_id, $vv->id);

        $xx = $this->getFieldAttrId("_plantilla", $recs);
        $vv = $recs[$xx["index"]];
        $gafete_html = $vv->getFileText($m_product_id, $vv->id);
        #dd($gafete_html,$recs);
       /*
        $content = "";
        if($plantilla["html1"]!="")$content = view("modulos.emails.{$m_category_id}", $params)->render();
        elseif($plantilla["html2"]!="")$content = view("modulos.emails.{$m_category_id}-extra", $params)->render();
        $gafete_html = "";
        if($plantilla["html3"]!="")$gafete_html = view("modulos.emails.{$m_category_id}-gafete", $params)->render();
        */
        $html = $content;



        if(is_array($confirmacion_por)&&in_array(1, $confirmacion_por)&&$confirmacion=="SI"){//confirma por email
            $asunto_xx = $df["_asunto"] ?? "";
            $asunto_x = array_key_exists($asunto_xx, $product_data)?$product_data[$asunto_xx]:"";
            if($asunto_x!="")$plantilla["asunto"] = $asunto_x;

            $file = "";
            if ($gafete_html != ""){
                $file = 'storage/confirmacion/'.$ins_id.'-'.$est["dni_url"].'.pdf';
                $pdf = Pdf::loadHTML($gafete_html )
                    ->setPaper([0, 0, 420, 235], 'landscape')
                    ->save($file);
            }
            $file = "";
            \Mail::send([], [], function ($mensaje) use ($est, $plantilla, $html, $file, $from_desde){
                //$from_desde = ["inscripciones@enc.edu.pe", "inscripciones@enc.edu.pe"];
                //$mensaje->from($datos_email['from_email'], $datos_email['from_name']);
                if(count($from_desde)>0){
                    $mensaje->from($from_desde[0], $from_desde[1]);
                }

                $mensaje->to($est['email'], $est['nombres'])->subject($plantilla["asunto"])->setBody($html, 'text/html');
                if ($file != ""){
                    $mensaje->attach($file);
                }
            });

        }

        // GAFETE
        if($confirmacion!="")return redirect()->route('mlead.confirm', [$m_category_id, $m_product_id]);

        return redirect()->route('mlead.index', [$m_category_id, $m_product_id]);
    }

    public function checkInsertNewsLetter($dni_doc){
        $check_new = DB::table('newsletters')->where('estudiante_id', $dni_doc)->count();
        if($check_new == 0){
            DB::table('newsletters')->insert([
                'estado'=>'1',
                'estudiante_id'=>$dni_doc,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
        }
        return $check_new;
    }

    public function setInputStudent($inputs, $field, $DF, $is_uppercase=0){
        if(isset($DF[$field]) && isset($inputs[$DF[$field]]) ){
            $v = $inputs[$DF[$field]];
            return $is_uppercase == 1 &&!is_array($v) ? mb_strtoupper($v): $v;
        }
        return "";
    }

    public function getFieldStudent($inputs){
        $DF = $this->getDefaultFields();
        $dni_doc = $this->setInputStudent($inputs, "dni", $DF, 1);
        $ap_paterno = $this->setInputStudent($inputs, "apepat", $DF, 1);
        $ap_materno = $this->setInputStudent($inputs, "apemat", $DF, 1);
        $nombres = $this->setInputStudent($inputs, "nom", $DF, 1);
        $grupo = $this->setInputStudent($inputs, "grupo", $DF, 1);
        $cargo = $this->setInputStudent($inputs, "cargo", $DF, 1);
        $organizacion = $this->setInputStudent($inputs, "entidad", $DF, 1);
        $profesion = $this->setInputStudent($inputs, "grado-profesion", $DF, 1);
        $codigo_cel = $this->setInputStudent($inputs, "celular", $DF, 1);
        $celular = $this->setInputStudent($inputs, "celular", $DF);
        $email = $this->setInputStudent($inputs, "email", $DF);
        $accedio = "SI";
        $estado = 1;
        $pais = $this->setInputStudent($inputs, "pais", $DF, 1);
        $region = $this->setInputStudent($inputs, "departamento", $DF, 1);
        $tipo_id = $this->setInputStudent($inputs, "tipo_doc", $DF);
        $tipo_documento_documento_id = $this->setInputStudent($inputs, "dni", $DF);
        $ip = request()->ip();//$_SERVER["REMOTE_ADDR"],
        $navegador = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $created_at =Carbon::now();
        $updated_at =Carbon::now();
        return compact(
            "dni_doc", "ap_paterno", "ap_materno", "nombres", "tipo_documento_documento_id", "tipo_id",
            "grupo", "cargo", "organizacion", "profesion", "codigo_cel", "celular", "email", "pais", "region",
            "accedio", "estado", "ip", "navegador", "created_at", "updated_at"
        );
    }

    function confirm($m_category_id, $m_product_id){
        $this->getDefaultAttrFields($m_category_id);
        $confirmacion = $this->getConfirm($m_product_id);
        return $confirmacion;
    }
    function cerrado($m_category_id, $m_product_id){
        $this->getDefaultAttrFields($m_category_id);
        $cerrado = $this->getCerrado($m_product_id);
        return $cerrado;
    }
    //
    public function registrados($m_category_id, $m_product_id){
        $df = $this->getDefaultFields();

        $modulo = MCategory::find($m_category_id);
        $campos = MCategoryField::where("m_category_id", $m_category_id)->where("is_detail", 1)->orderBy("position")->get();
        $pref = "_{$m_category_id}";

        $s = session("{$pref}_s")??"";
        $g = session("{$pref}_g")??"";
        $q = $this->inscritosLead($m_category_id, $g, $s, $campos) ;
        $q->select("m_est_id");
        $q->groupBy("data->{$df['grupo']}");
        $q->where("m_product_id", $m_product_id);
        $q->select("data->{$df['grupo']} as name", DB::RAW('count(1) as y'));
        $count_registrados = $q->get()->toArray();

//        $q2 = $this->inscritosLead($m_category_id, $input["g"], $input["s"], $campos) ;
//        $q->select("m_est_id");
//        $q2->groupBy("m_est_id");
        $q = $this->inscritosLead($m_category_id, $g, $s, $campos) ;
        $q->where("m_product_id", $m_product_id);
        $q->select("m_est_id");
        $total = $q->count();

//        $total = $q2->get()->count();


        return view("moduloslead.reports.e_registrados", compact("total", "count_registrados", "m_category_id", "m_product_id","g","s"));
    }

    public function eliminarVarios(Request $request, $m_category_id, $m_product_id)
    {
//        $this->actualizarSesion();
//        //VERIFICA SI TIENE EL PERMISO
//        if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["eliminar"])) {
//            Auth::logout();
//            return redirect('/login');
//        }
        $students = $request->students;
        if(count($students)==0){
            alert()->error('Aviso!', 'No se puede eliminar registros, seleccione');
            return redirect()->route('mlead.index', [$m_category_id, $m_product_id]);
        }
        $nodeletes = [];
        foreach ($students as $ins_id) {
            $delete = MProductIns::where([
                "m_category_id" => $m_category_id,
                "m_product_id" => $m_product_id,
                "id" => $ins_id,
            ])->delete();
            if(!$delete)array_push($nodeletes, $ins_id);
        }
        if(count($nodeletes)){
            alert()->error('Aviso!', 'No se pudo eliminar registros de id: '.implode(", ", $nodeletes));
        }else{
            alert()->success('Aviso!', 'Registros borrados con éxito');
        }
        return redirect()->route('mlead.index', [$m_category_id, $m_product_id]);
    }
}
