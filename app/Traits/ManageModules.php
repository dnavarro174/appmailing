<?php
namespace App\Traits;

use App\Departamento;
use App\Emails;
use App\Estudiante;
use App\Models\MAttr;
use App\Models\MCategoryField;
use App\Models\MPlantilla;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\Plantillaemail;
use App\TipoDoc;
use function PHPUnit\Framework\fileExists;

trait ManageModules {
    private $default_fields;
    private $default_attr_fields;

    public function getParamValues($m_category_id, $m_product_id, $ins_id, $default)
    {
        $data = [];
        $dd = $this->getAvailableFields($m_category_id, true);
        $product = MProduct::find($m_product_id);
        $d = $product->d;
        $dds = $d && is_object($d)?(array)$d:[];
        foreach($dds as $k=>$v){
            if(isset($dd[$k])){
                $name = $dd[$k];
                $data[$name] = $v;
            }
        }
        $ins = MProductIns::where(["m_category_id"=>$m_category_id,"m_product_id"=> $m_product_id, "id"=>$ins_id])->first();
        $d = $ins->d;
        $dds = $d && is_object($d)?(array)$d:[];
        foreach($dds as $k=>$v){
            if(isset($dd[$k])){
                $name = $dd[$k];
                $data[$name] = $v;
            }
        }
        return array_merge($default,$data);
    }

    function getHtmlModuleR($name){
        $name = trim($name);
        $filename = resource_path()."/views/modulos/emails/{$name}.blade.php";
        $exists = file_exists($filename);
        if($exists)return file_get_contents($filename);
        return "";
    }
    function saveHtmlModuleR($name, $value){
        $name = trim($name);
        $filename = resource_path()."/views/modulos/emails/{$name}.blade.php";

        $exists = file_exists($filename);
        if($value==""&&$exists)unlink($filename);
        if($value!=""){
            $file=fopen($filename,'w') or die ("error creando fichero!");
            fwrite($file,$value);
            fclose($file);
        }
        return file_exists($filename)?$filename:"";
    }

    function getHtmlModule($name ){
        $file = "m/tpl/{$name}.html";
        $exists = \Storage::disk('real_public')->exists($file);
        if($exists)return \Storage::disk('real_public')->get($file);
        return "";
    }
    function saveHtmlModule($name, $value){
        $name = trim($name);
        $filename = "m/tpl/{$name}.html";
        $exists = \Storage::disk('real_public')->exists($filename);
        if($value==""&&$exists)\Storage::disk('real_public')->delete($filename);
        if($value!=""){
            \Storage::disk('real_public')->put($filename, $value);
        }
        return \Storage::disk('real_public')->exists($filename)?$filename:"";
    }
    function getAvailableFields($modulo_id, $only_fields= false){
        $fields = $this->getRecIns($modulo_id);
        $recs = $fields[0];
        $ins = $fields[1];
        $fields_enabled = [1, 3, 4, 5, 7, 8, 9, 11, 13];
        $recs2 = $recs->whereIn("m_field_id", $fields_enabled);
        $ins2 = $ins->whereIn("m_field_id", $fields_enabled);

        $recs0 = [];
        $mf = [];
        $recs2->each(function ($v, $key) use (&$recs0, &$mf) {
            $name = "_".$v->name;
            $mf[$v->field] = $name;
            $recs0[$name] = $v->title;
        });
        $ins0 = [];
        $ins2->each(function ($v, $key) use (&$ins0, &$mf) {
            $name = $v->name;
            $mf[$v->field] = $name;
            $ins0[$name] = $v->title;
        });
        //$otros_params = ["ap_paterno", "ap_materno", "nombres"];
        return $only_fields?$mf:[$recs0, $ins0];
    }

    function getFieldAttrId($field, $rs=NULL){
        $DF = $this->getDefaultFields();
        $attr_ = $DF[$field]??"";
        $attr_id = str_replace("_","",$attr_);
        $r = collect($rs);

        $xx = $r->search(function ($item, $key) use($attr_id) {
            return $item["m_attr_id"] == $attr_id;
        });
        $index = $xx !== false ? $xx : -1;

        //$attr = $r->where("m_attr_id", $attr_id)->first();
        return compact("attr_","attr_id", "index");
    }

    public function getModuloPlantilla($modulo_id)
    {
        $q = MPlantilla::where("m_category_id", $modulo_id)->first();
        $plantilla = $q ? $q->toArray() :[
            "asunto"=>"","nombre"=>"","gafete"=>"NO","flujo_ejecucion"=>"","m_category_id"=>$modulo_id,"lista"=>""
        ];
        $plantilla["html1"] = $this->getHtmlModuleR($modulo_id);
        $plantilla["html2"] = $this->getHtmlModuleR("{$modulo_id}-extra");
        $plantilla["html3"] = $this->getHtmlModuleR("{$modulo_id}-gafete");
        return $plantilla;
    }
    function getTextEditor($attr_id, $product_id, $is_detail=0, $ins_id=0 ){
        if($product_id<1 || ($ins_id<1 && $is_detail==1))return "";
        $file = "m/ed/{$product_id}-{$attr_id}.html";
        if($is_detail==1)
            $file = "m/ed2/{$product_id}-{$ins_id}-{$attr_id}.html";
        $exists = \Storage::disk('real_public')->exists($file);
        if($exists)return \Storage::disk('real_public')->get($file);
        return "";
    }
    function getH2Text($attr_id, $product_id, $is_detail=0, $ins_id=0 ){
        $h = str_replace(["_","-"], "", $attr_id);
        $attr_id = intval($h);
        if(array_key_exists($attr_id, $this->default_attr_fields))$attr_id = $this->default_attr_fields[$attr_id];//UPDATE
        return $this->getTextEditor($attr_id, $product_id, $is_detail, $ins_id);
    }

    function getConfirm($product_id, $is_detail=0, $ins_id=0){
        $df = $this->getDefaultFields();
        $adf = $this->getDefaultAttrFields($product_id);
        return $this->getH2Text($df["_confirmacion_reg"], $product_id, $is_detail, $ins_id);
    }
    function getCerrado($product_id, $is_detail=0, $ins_id=0){
        $df = $this->getDefaultFields();
        return $this->getH2Text($df["_cerrado_reg"], $product_id, $is_detail, $ins_id);
    }

    function isClose($data, $m_category_id, $m_product_id){
        $df = $this->getDefaultFields();
        $fecha_ini = $data[$df['_fecha_ini']];
        $hora_ini = $data[$df['_hora_ini']];
        $vacantes = $data[$df['_vacantes']];
        $f0 = $fecha_ini.$hora_ini;
        $f0 = str_replace(["_","-",":"," "], "", $f0);
        $fecha_fin = $data[$df['_fecha_fin']];
        $hora_fin = $data[$df['_hora_fin']];
        $f2 = $fecha_fin.$hora_fin;
        $f2 = str_replace(["_","-",":"," "], "", $f2);
        $f1 = \Carbon\Carbon::now()->format("YmdHi");
        $f1 = intval($f1);
        $f2 = intval($f2);
        if($vacantes > 0){
            $count = MProductIns::where(['m_category_id' => $m_category_id, "m_product_id" => $m_product_id])->count();
            if($count >= $vacantes)//inscritos superaron las vacantes
                return True;
        }
        return $f1 > $f2;
    }


    public function postDNI($m_product_id){
        $df = $this->getDefaultFields();
        $fdata = [];
        $data = [];
        $success = false;
        $registered = 0;
        if(request()->ajax()){
            $dni = request('dni') ?? "";
            $id = request('id') ?? 0;
            if(isset($dni) && trim($dni)!=""){
                $rs = Estudiante::select(
                    'tipo_id','dni_doc','nombres','ap_paterno','ap_materno','pais', 'region','organizacion','cargo','profesion','celular','email','grupo','codigo_cel'
                )->where('dni_doc',$dni)->first();
                if($rs){
                    $rs2 = MProductIns::select('id','m_product_id','m_category_id')->where('m_product_id', $m_product_id)
                        ->where('data->'.$df["dni"],$dni)->where("id", "!=", $id)->first();
                    if ($rs2)$registered = $rs2->id;
                }
                if($rs){// HIDE EMAIL Y CELL
                    $data = $rs->toArray();
                    $fdata = ['email'  => hideEmail($data["email"]), 'celular'   => hideCel($data["celular"])];
                    $success = true;
                }
            }
            return compact('success','data','fdata', 'registered');
        }
    }

    public function getDNI($id,$evento=0){
        $df = $this->getDefaultFields();
        $fdata = [];
        $data = [];
        $success = false;

        if(request()->ajax()){
            \DB::enableQueryLog();

            //$selectDNI = ConsultaDNI::selectDNI($id,$evento);

            $rs = Estudiante::join('m_product_ins as de','estudiantes.dni_doc','=','de.data->'.$df["dni"])
                ->select('estudiantes.tipo_id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.pais',
                    'estudiantes.region','estudiantes.organizacion','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','estudiantes.email',
                    'de.id as eventos_id','estudiantes.grupo','estudiantes.codigo_cel')
                ->where('de.data->'.$df["dni"],$id)
                //->where('de.eventos_id',$evento)
                ->first();
            if($rs){
                $data = $rs->toArray();
                // HIDE EMAIL Y CELL
                $d_email = hideEmail($data["email"]);
                $d_cel = hideCel($data["celular"]);
                $fdata = array(
                    'email'     => $d_email,
                    'celular'   => $d_cel
                );
                $success = true;
            }else{
                $fdata = array();
            }
            $sql = \DB::getQueryLog();

            return compact('success','data','fdata');
            //return response()->json($datos);
        }
    }

    public function getDefaultFields()
    {
        if (!$this->default_fields) {
            $attrs = MAttr::select("id", "name", "is_detail")->get();
            $data = [];
            $attrs->each(function ($item, $key) use (&$data) {
                $pref = $item->is_detail==1?"":"_";
                $data[$pref.$item->name] = "_".$item->id;
            });
            $this->default_fields = $data;
        }
        return $this->default_fields;
    }

    public function getDefaultAttrFields($m_category_id)
    {
        if (!$this->default_attr_fields) {
            $attrs = MCategoryField::select("id", "m_attr_id", "is_detail")->where("m_category_id", "=", $m_category_id)->get();
            $data = [];
            $attrs->each(function ($item, $key) use (&$data) {
                $data[$item->m_attr_id] = $item->id;
            });
            $this->default_attr_fields = $data;
        }
        return $this->default_attr_fields;
    }

    public function inscritosLead($m_category_id, $g, $s, $campos){
        $df = $this->getDefaultFields();
        return MProductIns::where("m_category_id", $m_category_id)
            //->where("m_product_id", $m_product_id)
            ->when( $g, function ($query) use($g, $df) {
                //$query->whereJsonContains("data", ["_15"=>$g]);
                $query->where("data->{$df['grupo']}", $g);
                //
            })
            ->when( $s, function ($query) use($s, $campos, $df) {
                $field_names = [$df['grupo'], $df['nom'], $df['apepat'],$df['apemat']];
                $s = str_replace(" ", "%", $s);
                $query->where(function($q) use($campos, $field_names, $s, $df ) {
                    $q->orWhere(\DB::raw('concat(data->"$.'.$df['nom'].'"," ", data->"$.'.$df['apepat'].'"," ", data->"$.'.$df['apemat'].'")'), 'LIKE' , '%'.$s.'%');
                    foreach($campos as $c){
                        if(!in_array($c->field, $field_names)){
                            $q->orWhere("data->".$c->field, "LIKE", '%'.$s.'%');
                        }
                    }
                });
//                $query->where(DB::raw('concat(data->"$._13"," ", data->"$._14"," ", data->"$._15")'), 'LIKE' , '%'.$s.'%');
//                foreach($campos as $c){
//                    if(!in_array($c->field, $field_names)){
//                        $query->orWhere("data->".$c->field, "LIKE", '%'.$s.'%');
//                    }
//                }
                //
            });
    }

    public function getDepartaments(){
        return Departamento::select('ubigeo_id','nombre' )
            ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
            ->get();
    }
    public function getPlantillasEmail(){
        return Plantillaemail::
        select('id','nombre', 'asunto', 'flujo_ejecucion' )
            ->get();
    }
    public function getPlantillaEmail($id){
        $success = false;
        if($data = Plantillaemail::find($id))$success = true;
        return compact("success", "data");
    }
    public function getExpData(){
        $companytypes = ["PRIVADA", "ESTATAL"];
        $modalities = ["CONTRATO DE LOCACIÓN DE SERVICIOS", "CAS", "CAP"];
        $cargos = ["GERENTE EN EL SISTEMA DE CONTROL GUBERNAMENTAL", "GERENTE EN LA ADMINISTRACIÓN PÚBLICA", "GERENTE EN EL SECTOR PRIVADO",
            "JEFATURAS", "OTROS COMPETENTE A SU PROFESIÓN"];
        $ins_tipos = ["UNIVERSIDAD", "ESCUELA DE EDUCACIÓN SUPERIOR", "INSTITUTO", "OTROS"];
        $niveles = ["PREGRADO", "POSGRADO"];

        $emptyExp = ["empresa"=>"", "tipo"=>"", "puesto"=>"", "modalidad"=>"", "actividad"=>"", "inicio"=>"", "termino"=>""];
        $emptyExp2 = ["ins_tipo"=>"", "ins_nombre"=>"", "nivel"=>"", "curso"=>"", "inicio"=>"", "termino"=>""];
        $filesizes = ["1 MB", "10 MB", "100 MB", "1 GB", "10 GB"];
        $filetypes = ["document"=>"Documento", "presentation"=>"Presentación", "sheet"=>"Hoja de Cálculo", "draw"=>"Dibujo",
            "pdf"=>"PDF", "image"=>"Imagen", "video"=>"Video", "audio"=>"Audio"];
        $filetypesAceept = ["document"=>".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, text/plain",
            "presentation"=>"application/vnd.ms-powerpoint", "sheet"=>".xlsx,.xls,application/vnd.ms-excel", "draw"=>"image/x-dwg,image/x-dxf,drawing/x-dwf",
            "pdf"=>".pdf", "image"=>"image/*", "video"=>"video/*", "audio"=>"audio/*"];
        return compact( "companytypes", "modalities", "cargos", "emptyExp", "filesizes", "filetypes", "filetypesAceept", "ins_tipos", "niveles", "emptyExp2");
    }
    public function getValuesElements()
    {
        $doctypes = TipoDoc::select(["id", "tipo_doc as name"])->get();
        $groups = \DB::table('est_grupos')->select([/*"id", */"codigo as id", "nombre as name"])->get();
        $countries = \DB::table('country')->select('name', 'phonecode','nicename')->get();
        $domains = \DB::table('tb_email_permitos')->select(["id", "nombre as name", "dominio as domain"])->get();
        $emails = Emails::select(["id", "nombre as name", "email"])->orderBy("nombre",'asc')->get();
        $plantillas = $this->getPlantillasEmail();
        $departments = $this->getDepartaments();
        $exp = $this->getExpData();
        return compact("doctypes", "groups", "countries", "domains", "emails", "departments", "exp", "plantillas");
    }

    public function getRecIns($m_category_id)
    {
        $campos = MCategoryField::where("m_category_id", $m_category_id)->orderBy("position")->get();
        $recs = $campos->where("is_detail", 0)??[];
        $ins = $campos->where("is_detail", 1)??[];
        return [$recs, $ins];
    }
    public function getProductArray($id){
        $data = [];
        $product = [];
        $visible = [];
        if($id>0){
            $product = MProduct::find($id)->toArray()?? [];
            if( count($product) > 0 ){
                $data = json_decode($product["data"], true);
                $visible = json_decode($product["visible"], true);
            }
        }
        return compact("product", "data", "visible");
    }
    public function getProductArrayData($id){
        $data = [];
        if($id>0){
            $d = MProduct::find($id)->toArray()?? [];
            $data = count($d) > 0 ? json_decode($d["data"], true) : [];
        }
        return $data;
    }
    public function getInsArrayData($m_product_id, $id){
        $data = [];
        if($id>0){
            $d = MProductIns::firstWhere(compact("m_product_id", "id"))->toArray()?? [];
            $data = count($d) > 0 ? json_decode($d["data"], true) : [];
        }
        return $data;
    }


    function removeFileSave($m_category_id, $m_field, $product_data){
        $path = "images/m_{$m_category_id}";
        $success = False;
        $valueOld = array_key_exists($m_field, $product_data)?$product_data[$m_field]:"";
        if($valueOld!="" && file_exists("{$path}/{$valueOld}"))
            $success = unlink("{$path}/{$valueOld}");
        return compact('path', 'success');
    }
    public function inputsForSave($recs, $inputs, $request, $m_category_id, $product_data, $id)
    {
        //files eliminados
        $inpr = $request->input("inpr");
        $inputs_remove = json_decode($inpr, true) ?? [];
        if(count($inputs_remove)>0){
            $recr = MCategoryField::where("m_category_id", $m_category_id)->whereIn('id', $inputs_remove)->orderBy("position")->get();
            if(count($recr)>0){
                foreach ($recr as $c){
                    if(in_array($c['id'], $inputs_remove)){
                        $xx = $this->removeFileSave($m_category_id, $c['field'], $product_data);
                    }
                }
            }
        }

        $texts = $request->get("texts") ?? [];
        $inps = [];
        $eds = [];
        $data = [];
        $if = 0;
        $htmls = [];//ADDED
        $emailcodes = false;
        foreach($recs as $c){
            $m_field_id = $c["m_field_id"];
            $visible = $c["visible"];
            $m_attr_id = $c["m_attr_id"];
            $m_field = $c["field"];
            $_id = $c["id"];// = $m_attr_id

            $value = array_key_exists($_id, $inputs)?$inputs[$_id]:"";
            if($m_field_id == 3){//Int
                $value0 = intval($value);
                $value1 = floatval($value);
                $value = ($value0 == $value1) ? $value0: $value1;
            }elseif($m_field_id == 4){//Email
                if($value==""){
                    $value1 = array_key_exists($_id."-email", $inputs)?$inputs[$_id."-email"]:"";
                    $value2 = array_key_exists($_id."-domain", $inputs)?$inputs[$_id."-domain"]:"";
                    if($value1!="")$value = "{$value1}{$value2}";
                }
            }
            elseif($m_field_id == 7){//Fecha
                $value2 = intval(preg_replace('/(\d{2})[\/-]?(\d{2})[\/-]?(\d{4})/', "$3$2$1", $value));
                if($value2>0)$value = $value2;
            }
            elseif($m_field_id == 15){//File
                if($file = $request->file("inputs.{$_id}")){
                    $xx = $this->removeFileSave($m_category_id, $m_field, $product_data);
                    $path = $xx['path'];
                    //$name = $file->getClientOriginalName();
                    $name = $m_category_id.'_'.strtotime('now').$if.'.'.$file->getClientOriginalExtension();
                    $file->move( $path,$name);
                    $value = $name;
                    $if++;
                }else{//value anterior
                    if($id>0)
                        $value = array_key_exists($m_field, $product_data)?$product_data[$m_field]:"";
                }
            } elseif($m_field_id == 17) {//File
                //dd($data);
            }elseif($m_field_id == 18||$m_field_id==19) {//File
                $value = json_encode($value);
                //dd($data);
            }

            if($m_field_id == 2){
                //GRABAR VALUE
                $ff = date('mdYHis') . uniqid();
                $filename = $this->saveHtmlEd($ff, $value);
                if($filename!=""){
                    array_push($eds, [
                        "filename" => $filename,
                        "id"=>$_id,
                        "field"=>$m_field,
                    ]);
                }
                $value = "";
            }

            $inps[$m_field] = $value;

            $_text = array_key_exists($m_attr_id, $texts) && $texts[$m_attr_id] != ""?$texts[$m_attr_id]:"";
            if($_text!="")$inps[$m_field."_t"] = $_text;
        }
        return ["inputs"=>$inps, "editors"=>$eds];
    }

    function saveHtmlEd($name, $value){
        $name = trim($name);
        $filename = "m/tmp/{$name}.html";
        $exists = \Storage::disk('real_public')->exists($filename);
        if($value==""&&$exists)\Storage::disk('real_public')->delete($filename);
        if($value!=""){
            \Storage::disk('real_public')->put($filename, $value);
        }
        return \Storage::disk('real_public')->exists($filename)?$filename:"";
    }

    function renameFiles($files, $base, $pref=""){
        if(count($files)>0){
            foreach ($files as $f){
                $extension = pathinfo(storage_path($f["filename"]), PATHINFO_EXTENSION);
                $filename = $base.$pref.$f["id"].".".$extension;
                if(\Storage::disk('real_public')->exists($filename))\Storage::disk('real_public')->delete($filename);
                \Storage::disk('real_public')->rename($f["filename"], $filename);
            }
        }
    }

    public function getImageData($product_data, $m_category_id, $field, $path="")
    {
        if($path=="")$path = "images/m_{$m_category_id}";
        $img = array_key_exists($field, $product_data)?$product_data[$field]:"";
        return  $img!="" && file_exists("{$path}/{$img}") ? asset("{$path}/{$img}") :"";
    }




    public function getRequireds($data)
    {
        return $data->filter(function ($item) {
            return $item->required==1;
        })->values() ?? [];
    }
    public function lineJS(&$lines, $tipo, $selectorInput, $selectorContenedor, $ok){
        $ln1 = $selectorInput.'.prop("disabled", true);';
        $ln2 = $selectorContenedor.'.addClass("invisible");';
        $ln3 = $selectorContenedor.'.addClass("d-none");';

        $ln1f = $selectorInput.'.prop("disabled", false);';
        $ln2f = $selectorContenedor.'.removeClass("invisible");';
        $ln3f = $selectorContenedor.'.removeClass("d-none");';

        if($ok){
            if($tipo == 1) array_push($lines, $ln1);
            elseif($tipo == 2) array_push($lines, $ln2, $ln1);
            elseif($tipo == 3) array_push($lines, $ln3, $ln1);
        }else{
            if($tipo == 1) array_push($lines, $ln1f);
            elseif($tipo == 2) array_push($lines, $ln2f, $ln1f);
            elseif($tipo == 3) array_push($lines, $ln3f, $ln1f);
        }
    }
    public function getJSEvent($m_field_id){
        if(in_array($m_field_id, [5, 6, 13, 14]))return "click";
        elseif(in_array($m_field_id, [11, 12, 15]))return "change";
        return "keyup";
    }

    public function getListDependentSelects($data)
    {
        $types = [3,4,5,8];
        $selects = [];

        if(count($data)>0) {
            foreach ($data as $c) {
                $elements_x = [];
                $elements = [];
                $elements2 = [];
                $input = [];
                $types2 = [];
                $types3 = [];
                $oj = $c->oj();
                $v2 = $oj['v2'] ?? [];
                $ph = $oj['ph'] ?? '';
                $t1 = intval($oj['t'] ?? "0");
                $index = array_search($t1, $types);
                if($index===FALSE)$index=-1;
                if(count($v2)>0){
                    $index2 = -1;
                    foreach($v2 as $v){
                        $d = $data->find($v);
                        if($d){
                            $oj2 = $d->oj();
                            $t2 = $oj2['t'];
                            if($t2<$t1) $types2[] = $t2;
                            $elements_x[] = [
                                'type' => $t2,
                                'id' => $v,
                                'ph' => $ph,
                            ];
                        }
                    }
                    $t3 = count($types2) > 0 ? max($types2) : 0;
                    foreach ($elements_x as $e){
                        if($e['type'] == $t3) {
                            $input = $e;
                        }
                        elseif($e['type'] > $t3)$elements2[] = $e;
                        else $elements[] = $e;
                    }
                    if(count($input)>0)
                        $selects[] = [
                        'type' => $t1,
                        'id' => $c['id'],
                        "index" => $index,
                        'elements' => $elements,
                        'elements2'=>$elements2,
                        'input' => $input,
                        "max" => $t3
                    ];
                }
            }
        }
        return $selects;
    }
    public function getJSData($data, $prefContenedor, $prefInput){
        $editors1 = [];
        $requireds = $this->getRequireds($data);
        $events = [];
        $codes = [];
        $emailcodes = 0;
        if(count($data)>0){
            foreach ($data as $c){
                $oj = $c->oj();
                $m_field_id = $c->m_field_id;
                $event = $this->getJSEvent($m_field_id);
                if($m_field_id==2){
                    $tiene_editor_1 = $oj["ed"] ?? 0;
                    if($tiene_editor_1 == 1){
                        array_push($editors1, "#{$prefInput}{$c->id}");
                    }
                }
                if(count($oj)>0 and isset($oj["flt"]) and count($oj["flt"])>0 and $oj["flt"]["f"] != "" and  $oj["flt"]["e"] >0 and count($oj["flt"]["c"])>0){
                    $lines = [];
                    $value = $oj["flt"]["f"];
                    $e = $oj["flt"]["e"];
                    $fields = $oj["flt"]["c"];
                    $values = explode(",", $value);
                    $cond = [];
                    $condIds = [];
                    if(count($values)>0){
                        foreach($values as $v){
                            $first = substr($v, 0, 1);
                            $operator = "=";
                            if($first=="!"){
                                $v = substr($v, 1);
                                $operator = "!";
                            }
                            $v1 = addslashes($v);
                            array_push($cond, "this.value {$operator}= \"{$v1}\"");
                            array_push($condIds, "\$(\"#{$prefInput}{$c->id}\").val() {$operator}= \"{$v1}\"");
                        }
                    }
                    $selectorInputs = [];
                    $selectorContainers = [];
                    foreach($fields as $x){
                        array_push($selectorInputs, "\"#{$prefInput}{$x}\"");
                        array_push($selectorContainers, "\"#{$prefContenedor}{$x}\"");
                    }
                    $l1 = implode(", ", $selectorInputs);
                    $l2 = implode(", ", $selectorContainers);
                    if($event == 'click'){
                        if($operator!='!')$operator = "";
                        //array_push($lines,"if( {$operator}this. checked ){");
                        array_push($lines,"effS( {$operator}this. checked, {$e}, [{$l1}], [{$l2}]);");
                        array_push($codes,"effS( \$(\"#{$prefInput}{$c->id}\").prop('checked'), {$e}, [{$l1}], [{$l2}]);");

                    }else{
                        //array_push($lines,"if(".implode(" || ", $cond)."){");
                        array_push($lines,"effS( ".implode(" || ", $cond).", {$e}, [{$l1}], [{$l2}]);");
                        array_push($codes,"effS( ".implode(" || ", $condIds).", {$e}, [{$l1}], [{$l2}]);");
                    }


                    foreach($fields as $x){
                        $ct = "\$(\"#{$prefContenedor}{$x}\")";
                        $in = "\$(\"#{$prefInput}{$x}\")";
                        //$this->lineJS($lines, $e, $in, $ct,1);
                    }

                    //array_push($lines, "}else{");

                    foreach($fields as $x){
                        $ct = "\$(\"#{$prefContenedor}{$x}\")";
                        $in = "\$(\"#{$prefInput}{$x}\")";
                        //$this->lineJS($lines, $e, $in, $ct,0);
                    }

                    //array_push($lines, "}");
                    $events[$c->id] = ["lines"=>$lines, "event"=>$event] ;
                }
                // evento correo invalido, campo email
                $dm = $oj["dm"]??0;
                if ($m_field_id == 4){
                    if($dm==1 ){
                        $emailcodes = 1;
                        $code = "\$(\"#inp-{$c->id}-email\").on(\"keyup\", function(){
                            validEmailAaa(this.value, \"#message-{$c->id}\", \"#actionSubmit\");
                    })";
                        array_push($codes, $code);
                    }
                }
            }
        }
        $fe =  $this->getJsStr();
        //if($emailcodes == 1)
            array_unshift($codes, $fe);
        return compact("events", "codes", "editors1");
    }
    function getJsStr(){
        return <<<PHP
function isValidEmail(email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( email );
}
function validEmailAaa(email, sel_message, sel_submit){
var sm = $(sel_message);
var sb = $(sel_submit);
  if( !isValidEmail(email)  ) {
    sm.html("").addClass("d-none").removeClass("d-block");
    sb.attr("disabled", false);
  }else{
    sm.html("Lo sentimos, solo se permite el nombre de usuario, sin @dominio.com").addClass("d-block").removeClass("d-none");
    sb.attr("disabled", true);
  }
  if(email.indexOf("@") > -1 || email.indexOf(";") > -1 || email.indexOf(",") > -1){
    sm.html("Lo sentimos, solo se permite el nombre de usuario, sin @dominio.com").addClass("d-block");
    sb.attr("disabled", true);
  }
 }

function eff(cond, t, selInput, selContainer){
    if(cond){
        //DEFECTO
        $(selInput).prop("disabled", true);
        if($(selInput).next('.note-editor').length)$(selInput).summernote('disable');

        if (t==2){
            $(selContainer).addClass("invisible");
        }else if (t==3){
            $(selContainer).addClass("d-none");
        }
    }else{
        $(selInput).prop("disabled", false);
        if($(selInput).next('.note-editor').length)$(selInput).summernote('enable');

        if (t==2){
            $(selContainer).removeClass("invisible");
        }else if (t==3){
            $(selContainer).removeClass("d-none");
        }
    }
}
function effS(cond, t, selectors, selectors2){
    if(selectors.length){
        $.each(selectors, function(i, sel){
            eff(cond, t, sel, selectors2[i]);
        });
    }
}
PHP;
    }
}
