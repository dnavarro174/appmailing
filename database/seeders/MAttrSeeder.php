<?php

namespace Database\Seeders;

use App\Models\MAttr;
use Illuminate\Database\Seeder;

class MAttrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    protected $fillable = ["name", "title", "subtitle", "style","note", "note_style", "required", "value", "styles", "position", "visible", "is_detail", "m_field_id"];
    public function inserta($data)
    {
        $name = $data["n"];
        $title = $data["t"];
        $position = $data["p"];
        $m_field_id = $data["f"]??1;
        $is_detail = $data["isdet"] ?? 0;
        $opt = $data["o"]??'';
        $value = $data["v"]??'';

        $visible = 1;
        if(isset($data["vis"]))
            $visible = $data["vis"]==1;

        $required = 1;
        if(isset($data["r"]))
            $required = $data["r"]==1;

        $is_fullsize = $data["fs"]??0;
        $is_title_hidden = $data["ith"]??0;

        if($is_detail!=1)$is_detail = 0;
        //$visible = $m_field_id == 20 ? 0: 1;

        $data = [
            'name' => $name,
            'title' => $title,
            'visible' => $visible,
            'position' => $position,
            'm_field_id' => $m_field_id,
            'required' => $required,
            'is_detail' => $is_detail,
            'is_fullsize' => $is_fullsize,
            'is_title_hidden' => $is_title_hidden,
        ];
        if($opt!=''){
            $data["opt"] = $opt;
        }
        if($value!=''){
            $data["value"] = $value;
        }
        MAttr::factory(1)->create($data);
    }
    public function run()
    {
        $p = 1;
        $this->inserta(["n"=>"nombre", "t"=> "Nombre", "p"=> $p++, "f"=> 1]);
        $this->inserta(["n"=>"description", "t"=> "Descripción", "p"=> $p++, "f"=> 1]);
        $this->inserta(["n"=>"fecha_ini", "t"=> "Fecha de Inicio", "p"=> $p++, "f"=> 7]);
        $this->inserta(["n"=>"hora_ini", "t"=> "Hora de Inicio", "p"=> $p++, "f"=> 8]);
        $this->inserta(["n"=>"fecha_fin", "t"=> "Fecha Fin", "p"=> $p++, "f"=> 7]);
        $this->inserta(["n"=>"hora_fin", "t"=> "Hora Fin", "p"=> $p++, "f"=> 8]);
        $this->inserta(["n"=>"lugar", "t"=> "Lugar", "p"=> $p++, "f"=> 1]);
        $this->inserta(["n"=>"vacantes", "t"=> "Vacantes", "p"=> $p++, "f"=> 3]);
        $this->inserta(["n"=>"confirmacion", "t"=> "Tendrá Confirmación", "p"=> $p++, "f"=> 11, 'o'=>'{"t": "0", "v": [["NO", "NO", "", ""], ["SI", "SI", "", ""]], "flt": {"c": [], "e": "", "f": ""}}', 'v'=>'NO']);
        $this->inserta(["n"=>"asunto", "t"=> "Asunto para la confirmacion", "p"=> $p++, "f"=> 1]);
        $this->inserta(["n"=>"email_desde", "t"=> "Enviado desde", "p"=> $p++, "f"=>11, 0,'o'=>'{"t": 6, "v": []}']);
        $this->inserta(["n"=>"confirmacion_por", "t"=> "Confirmacion por", "p"=> $p++, "f"=>14, 'o'=>'{"t": "0", "v": [["Email", "1", "", ""], ["Mensaje Whatsapp", "2", "", ""]], "flt": {"c": [], "e": "", "f": ""}}','v'=> '[]']);

        $this->inserta(["n"=>"confirmacion_reg", "t"=> "Pantallazo confirmación al finalizar el registro", "p"=> $p++,  "f"=>2, 'o'=>'{"ed": "1", "ml": "", "ph": "", "flt": {"c": [], "e": "", "f": ""}}']);
        $this->inserta(["n"=>"cerrado_reg", "t"=> "Evento Cerrado (HTML)", "p"=> $p++,  "f"=>2, 'o'=>'{"ed": "1", "ml": "", "ph": "", "flt": {"c": [], "e": "", "f": ""}}']);
        $this->inserta(["n"=>"gafete", "t"=> "Gafete", "p"=> $p++, "f"=>11, 'o'=>'{"t": "0", "v": [["NO", "NO", "", ""], ["SI", "SI", "", ""]], "flt": {"c": [], "e": "", "f": ""}}', 'v'=>'NO']);
        $this->inserta(["n"=>"plantilla", "t"=> "Seleccionar plantilla", "p"=> $p++,  "f"=>2, 'o'=>'{"ed": "1", "ml": "", "ph": "", "flt": {"c": [], "e": "", "f": ""}}']);

        $this->inserta(["n"=>"evento_h", "t"=> "Confirmación de registro del evento (HTML)", "p"=> $p++,  "f"=>2, 'o'=>'{"ed": "1", "ml": "", "ph": "", "flt": {"c": [], "e": "", "f": ""}}']);
        $this->inserta(["n"=>"recordatorio_h", "t"=> "Recordatorio (HTML)", "p"=> $p++,  "f"=>2, 'o'=>'{"ed": "1", "ml": "", "ph": "", "flt": {"c": [], "e": "", "f": ""}}']);


        $this->inserta(["n"=>"im_header", "t"=> "Imagen Header", "p"=> $p++,  "f"=>15, "r" => 0]);
        $this->inserta(["n"=>"im_footer", "t"=> "Imagen Footer", "p"=> $p++,  "f"=>15, "r" => 0]);
        $this->inserta(["n"=>"_grabar", "t"=> "Grabar", "p"=> $p++, "f"=> 20, "vis"=>0, "fs"=>1, "ith"=>1]);

        $p = 101;
        $this->inserta(["n"=>"tipo_doc", "t"=> "Tipo Documento", "p"=> $p++,  "f"=>11 , "isdet"=>1,'o'=>'{"t": 1, "v": []}']);
        $this->inserta(["n"=>"dni", "t"=> "DNI", "p"=> $p++,  "f"=>1 , "isdet"=>1]);
        $this->inserta(["n"=>"grupo", "t"=> "Grupo", "p"=> $p++, "f"=> 11 , "isdet"=>1,'o'=>'{"t": 2, "v": []}']);
        $this->inserta(["n"=>"nom", "t"=> "Nombres", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"apepat", "t"=> "Apellido Paterno", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"apemat", "t"=> "Apellido Materno", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"pais", "t"=> "País", "p"=> $p++, "f"=>11 , "isdet"=>1,'o'=>'{"t": 3, "v": []}']);
        $this->inserta(["n"=>"departamento", "t"=> "Departamento", "p"=> $p++, "f"=> 11 , "isdet"=>1,'o'=>'{"t": 4, "v": [["", "", "19", 0]]}']);
        $this->inserta(["n"=>"grado-profesion",  "t"=>"Grado Profesional", "p"=> $p++, "f"=> 11 , "isdet"=>1, 'o'=>'{"t": 0, "v": [["TÍTULO", "1", "", 0], ["MAESTRÍA", "2", "", 0], ["DOCTORADO", "3", "", 0]]}']);
        $this->inserta(["n"=>"entidad", "t" =>"Empresa / Entidad", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"cargo", "t"=> "Cargo", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"email", "t"=> "Email", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"celular", "t"=> "Celular", "p"=> $p++, "f"=> 1 , "isdet"=>1]);
        $this->inserta(["n"=>"grabar", "t"=> "Enviar", "p"=> $p++, "f"=> 20, "isdet"=>1, "vis"=>0, "fs"=>1, "ith"=>1]);
    }
}
