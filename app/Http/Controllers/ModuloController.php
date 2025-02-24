<?php

namespace App\Http\Controllers;

use App\Emails;
use App\Models\MAttr;
use App\Models\MCategory;
use App\Models\MCategoryField;
use App\Models\MField;
use App\Models\MPlantilla;
use App\Models\MProduct;
use App\Plantillaemail;
use App\TipoDoc;
use App\Traits\ManageModules;
use Illuminate\Http\Request;
use App\AccionesRolesPermisos;

class ModuloController extends Controller
{
    use ManageModules;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "eventos-es";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $pag = 15;
        if($request->get('pag')){
            \Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }

        /* $permisos = [];
        $permisos['nuevo']['permiso'] = 1;
        $permisos['editarr']['permiso'] = 1;
        $permisos['mostrar']['permiso'] = 1;
        $permisos['eliminar']['permiso'] = 1; */

        $mcategories = MCategory::orderBy('id', request('sorted', 'DESC'))
            ->when( request()->query("s"), function ($query) {
                $query->where("name", request()->query("s"));
            })
            ->paginate($pag);;
        return view("modulos.index", compact('mcategories', 'permisos'));

    }
    public function eliminarVarios(Request $request){

    }
    public function createEdit($id=0)
    {
        $data = ["id" => 0, "name"=>"", "slug" => "","description"=>""];
        $plantilla_datos = [];
        $edit = false;
        $opts = ["Manual", "Tipo de documento", "Grupo","Pais", "Departamento","Provincia","Correos","Dominios","Distrito"];
        //$fields = MField::select(["id", "name"])->get();
        $emails = Emails::orderBy("nombre",'asc')->get();
        $fields = MField::select(["id", "name"])->orderBy("id")->get();
        $attrs = MAttr::select(["id", "name","is_detail"])->orderBy("id")->get();

        $m_categories = MCategory::pluck( "name", "id");
        $exp = $this->getExpData();

        return compact('opts','fields', 'attrs','data', 'emails', 'plantilla_datos', 'edit', 'm_categories','exp');
    }
    public function create(Request $request)
    {
        $this->actualizarSesion();
       
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["nuevo"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        return view('modulos.form', $this->createEdit());
    }
    public function edit(MCategory $modulo)
    {
        $this->actualizarSesion();
       
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $data = $this->createEdit();
        $data["data"] = $modulo->toArray();
        $data["edit"] = true;
        $data["m_categories"] = [];
        return view('modulos.form', $data);
    }
    public function plantilla(MCategory $modulo)
    {
        $d = $this->createEdit();
        $modulo = $modulo->toArray();
        $data["edit"] = true;
        //$data["m_categories"] = [];
        $html1= "";
        $html2 = "";
        $html3= "";
        $modulo_id = $modulo["id"];
        $plantilla = $this->getModuloPlantilla($modulo_id);
        $plantillas = Plantillaemail::select('id','nombre', 'asunto', 'lista','flujo_ejecucion' )->get();

        $flujos = $plantillas->pluck('flujo_ejecucion')->unique()->whereNotNull()->toArray();
        $gafetes = ["NO", "SI"];
        $fields = $this->getAvailableFields($modulo_id);

        return view('modulos.template', compact("modulo", "plantillas", "flujos", "gafetes", "plantilla","fields"));
    }
    public function storeTemplate(Request $request)
    {
        $nombre = $request->get('nombre');
        $lista = $request->get('lista');
        $asunto = $request->get('asunto');
        $gafete = $request->get('gafete');
        $flujo_ejecucion = $request->get('flujo_ejecucion');
        $template1 = $request->get('template1');
        $template2 = $request->get('template2');
        $template3 = $request->get('template3');
        $id = $request->get('id');
        MPlantilla::updateOrCreate( ["m_category_id" => $id], compact("nombre", "lista", "asunto", "gafete", "flujo_ejecucion"));
        $this->saveHtmlModuleR($id, $template1);
        $this->saveHtmlModuleR("{$id}-extra", $template2);
        $this->saveHtmlModuleR("{$id}-gafete", $template3);
        return redirect(route("modulos.plantilla.form", $id));
    }
    public function saveField($data, $m_category_id, $m_category_id_old, $is_detail)
    {
        #dd('c',$data);
        $ids = [];
        $tags = [];
        if(count($data)>0){
            foreach($data as $i=>$v){
                $v["m_category_id"] = $m_category_id;
                $v["note_style"] = "{}";
                if(!$v["m_attr_id"])$v["m_attr_id"] = NULL;
                //if($v["m_field_id"]==17)$v["value"] = json_encode($v["value"]);;
                if(in_array($v["m_field_id"], [12, 14, 17]))$v["value"] = json_encode($v["value"]);;
                $dd = NULL;
                $tag = $v["tag"];
                if($m_category_id_old==0){
                    unset($v["id"]);
                    $dd = MCategoryField::create($v);
                }else{
                    $mcf_id = $v["id"]+0;
                    unset($v["id"]);
                    try {
                        $dd = MCategoryField::updateOrCreate(["id"=>$mcf_id], $v);
                    } catch (\Throwable $e) {
                        dump($v);
                        dd($e->getMessage());

                    }
                }
                $id2 = $dd->id;
                $data[$i]["id2"] = $id2;
                array_push($ids, $id2);
            }

            //update campos condicion
            foreach($data as $v)$tags[$v["tag"]] = $v["id2"];
            foreach($data as $v){
                $id = $v["id2"];
                $opt = $v["opt"];
                $dd = json_decode($opt ??"[]", true);
                $flt = $dd["flt"]??[];
                $fltc = $flt["c"]??[];
                $v2 = $dd["v2"]??[];
                $xids = [];
                $xids2 = [];
                if(count($fltc)>0){
                    foreach ($fltc as $f)
                        array_push($xids, $tags[$f]);
                }
                if(count($v2)>0){
                    foreach ($v2 as $f)
                        array_push($xids2, $tags[$f]);
                }
                if(count($xids)>0||count($xids2)>0){
                    $dd["flt"]["c"] = $xids;
                    $dd["v2"] = $xids2;
                    //$opt = json_encode($dd);
                    \DB::table("m_category_fields")->where("id", $id)->update(
                        [
                            'opt->flt->c'=> $xids,
                            'opt->v2'=> $xids2,
                        ]
                    );
                }
            }

        }
        MCategoryField::whereNotIn("id", $ids)->whereIsDetail($is_detail)->whereMCategoryId($m_category_id)->delete();
    }
    public function store(Request $request)
    {
        $recs = json_decode($request->get('datar')??"[]", true);
        $ins = json_decode($request->get('datai')??"[]", true);
        #dd('store',$ins);
        $name = $request->get('name');
        $description = $request->get('description');
        $id = $request->get('id');
        //$slug = $request->get('slug');
        $slug = \Str::slug($name);
        $category = ["name" => $name, "slug"=>$slug, "description" => $description];

        //$val_name = 'required|unique:m_categories,name';
        //if($id>0)$val_name .= ",{$id}";
        $dn = $id>0 ? $id : 'NULL';
        $val_name = 'required|unique:m_categories,name,'.$dn.',id,deleted_at,NULL';
        $validator = \Illuminate\Support\Facades\Validator::make($category, ['name' => $val_name,],["required"=>"El nombre esta vacio","unique"=>"El nombre ya ha sido registrado"]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            alert()->warning('Aviso!', $errors->first('name'));
            return redirect()->back();//->withInput($request->all());
        }


        $m_category = MCategory::updateOrCreate(["id" => $id], $category);
        $m_category_id = $m_category->id;
        $this->saveField($recs, $m_category_id, $id, 0);
        $this->saveField($ins, $m_category_id, $id, 1);
        \Cache::flush();
        $msgTipo = $id>0?'Actualizado':'Grabado';
        alert()->warning('Aviso!', 'Registro '. $msgTipo .' con éxito');
        return redirect()->route('modulos.edit', [$m_category_id]);
    }

    public function inputs($id=0)
    {
        $data = $this->getValuesElements();
        $m_category_id = $id;
        $attrs = ($id==0) ? MAttr::get() : MCategoryField::where("m_category_id", $m_category_id)->orderBy("position")->get();
        $recs = [];
        $ins = [];
        foreach($attrs as $attr){
            $value = $attr->value;
            if(in_array($attr->m_field_id, [12, 14, 17]))$value = json_decode($value, TRUE);
            $d = [
                "id" => ($id==0)?0:$attr->id,
                "m_field_id" => $attr->m_field_id,
                "m_attr_id" => ($id==0)?$attr->id:$attr->m_attr_id,//ADDED
                "name" => $attr->name,
                "title" => $attr->title,
                "subtitle" => $attr->subtitle,
                "style" =>  $attr->style,
                "note" => $attr->note,
                "note_style" =>  $attr->note_style,
                "required" =>  $attr->required,
                "value" =>  $value,
                "styles" =>  $attr->styles,
                "visible" =>  $attr->visible,
                "is_detail" =>  $attr->is_detail,
                "position" =>  $attr->position,
                "opt" =>  $attr->opt,
                "is_title_hidden" =>  $attr->is_title_hidden,
                "is_fullsize" =>  $attr->is_fullsize,
            ];
            if($attr->is_detail)
                array_push($ins, $d);
            else
                array_push($recs, $d);
        }
        $data["recs"] = $recs;
        $data["ins"] = $ins;
        return $data;
    }
    public function destroy($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        $evento = MProduct::where('m_category_id', $id)->get();
        $count = count($evento);
        if($count >=1 ){
            alert()->warning('Alerta','El módulo esta siendo utilizado en el sistema.')->persistent('Close');
            return redirect()->route('modulos.index');
        }
        $mcategory = MCategory::where('id', $id)->first();
        if(!$mcategory){
            alert()->warning('Alerta','El módulo no existe en el sistema.')->persistent('Close');
            return redirect()->route('modulos.index');
        }
        //falta eliminar los recursos
        if($mcategory->delete()){
            alert()->error('Registro borrado.','Eliminado');
        }
        return redirect()->back();

    }
}
