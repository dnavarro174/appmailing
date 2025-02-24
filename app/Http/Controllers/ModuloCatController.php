<?php

namespace App\Http\Controllers;

use App\Departamento;
use App\Emails;
use App\Models\MCategory;
use App\Models\MCategoryField;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\TipoDoc;
use App\Traits\ManageModules;
use Illuminate\Http\Request;

class ModuloCatController extends Controller
{
    use ManageModules;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(Request $request, $m_category_id)
    {
        $pref = "_c{$m_category_id}";
        if ($request->get('pag')) {
            \Cache::flush();
            session(["{$pref}_pag" => $request->get('pag')]);
        }
        $pag = session("{$pref}_pag") ?? 15;

        $products = MProduct::where("m_category_id", $m_category_id)->paginate($pag);
        $modulo = MCategory::find($m_category_id);

        $permisos = [];

        $permisos['nuevo']['permiso'] = 1;
        $permisos['editar']['permiso'] = 1;
        $permisos['nuevo']['permiso'] = 1;

        $departamentos = $this->getDepartaments();
        $DF = $this->getDefaultFields();

        return view('moduloscat.index', compact('products', 'departamentos', 'permisos', 'modulo', 'DF'));
    }

    public function createEdit($m_category_id, $product_id = 0)
    {
        $m_product_id = $product_id ?? 0;
        $modulo = MCategory::find($m_category_id);

        [$recs, $ins] = $this->getRecIns($m_category_id);
        $ins_chunk = $ins->chunk(3);
        //$estypes  = DB::table('estudiantes_tipo')->get();
        $data = $this->getValuesElements();
        //$product_data = $this->getProductArrayData($m_product_id);

        $product = $this->getProductArray($m_product_id);
        $product_data = $product["data"];
        $visible_data = $product["visible"];
        $requireds = $this->getRequireds($recs);
        $datajs = $this->getJSData($recs, "content-", "inp-");
        $linesjs = $datajs["codes"];
        $eventsjs = $datajs["events"];
        $editors1 = $datajs["editors1"];

        $DF = $this->getDefaultFields();

        //SI ES NUEVO --- REV
        if($m_product_id == 0){
            $plantilla = $this->getModuloPlantilla($m_category_id);
            $xx = $this->getFieldAttrId("_asunto", $recs);
            if($xx["index"]!=-1)$product_data[$xx["attr_"]] = $plantilla["asunto"];
            $xx = $this->getFieldAttrId("_gafete", $recs);
            if($xx["index"]!=-1)$product_data[$xx["attr_"]] = $plantilla["gafete"];
            $xx = $this->getFieldAttrId("_evento_h", $recs);
            if($xx["index"]!=-1)$product_data[$xx["attr_"]] = $plantilla["html1"];
            $xx = $this->getFieldAttrId("_recordatorio_h", $recs);
            if($xx["index"]!=-1)$product_data[$xx["attr_"]] = $plantilla["html2"];
            $xx = $this->getFieldAttrId("_plantilla", $recs);
            if($xx["index"]!=-1)$product_data[$xx["attr_"]] = $plantilla["html3"];
            //$recs[$xx["index"]]->value = $plantilla["asunto"];

            //cargar gafete, seleccion, cerrado
        }



        return compact("modulo", "recs", "linesjs", "eventsjs", "requireds", "ins_chunk", "data", "m_product_id", "product_data", "visible_data", "editors1", "DF");
    }

    public function create($m_category_id)
    {
        return view(
            'moduloscat.form', $this->createEdit($m_category_id));
    }

    public function edit($m_category_id, $m_product_id = 0)
    {
        return view('moduloscat.form', $this->createEdit($m_category_id, $m_product_id));
    }

    public function store(Request $request, $m_category_id)
    {
        $inputs = $request->input("inputs");
        $ifields = $request->input("f");
        $visibles = $request->input("visibles");
        $id = $request->input("id") ?? 0;

        [$recs, $ins] = $this->getRecIns($m_category_id);
        //$modulo = MCategory::find($m_category_id);
        $product_data = $this->getProductArrayData($id);
        $visible_data = is_array($visibles)&&count($visibles) > 0 ? $visibles : [];
        $ddi = $this->inputsForSave($recs, $inputs, $request, $m_category_id, $product_data, $id);
        $d = [
            'm_category_id' => $m_category_id,
            'data' => json_encode($ddi["inputs"]),
            'visible' => json_encode($visible_data)
        ];
        $m_product = MProduct::updateOrCreate(["id" => $id], $d);
        $this->renameFiles($ddi["editors"], "m/ed/", "{$m_product->id}-");
        alert()->success('Registro grabado.','Mensaje Satisfactorio');
        return redirect()->route('mcat.index', $m_category_id);
    }

    public function destroy($m_category_id, $m_product_id = 0)
    {
        $modulo = MCategory::find($m_category_id);
        if(!$modulo){
            alert()->warning('No existe modulo.','Aviso!');
        }else{
            $product = MProduct::find($m_product_id);
            if(!$product){
                alert()->warning('No existe evento.','Aviso!');
            }else{
                $ins = MProductIns::where([
                    "m_product_id"=> $m_product_id,
                    "m_category_id" => $m_category_id
                ])->count();
                if($ins > 0) {
                    alert()->warning('Aviso!', 'El evento tiene inscritos');
                }
                else{
                    if($product->delete())
                        alert()->success('Registro borrado correctamente.','Aviso!');
                    else
                        alert()->warning('No se pudo eliminar el registro.','Aviso!');
                }
            }
        }
        return redirect()->route('mcat.index', $m_category_id);
    }
}
