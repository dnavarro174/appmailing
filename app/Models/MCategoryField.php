<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCategoryField extends Model
{
    protected $table = "m_category_fields";
    public $timestamps = false;
    use HasFactory;

    protected $fillable = ["name", "title", "subtitle", "style","note", "required", "value", "styles", "position", "visible", "is_detail", "m_field_id", "m_category_id","m_attr_id", "opt", "is_title_hidden", "is_fullsize"];

    public function getFieldAttribute(){
        return $this->m_attr_id ? "_{$this->m_attr_id}": "c{$this->id}";
    }
    protected $appends = ['field'];
    public function oj(){
        return json_decode($this->opt, true)??[];
    }
    public function st(){
        return json_decode($this->style, true)??[];
    }
    public function listAccess($d2){
        $d = $this->oj();
        $acc = array_key_exists("acc", $d) ? $d["acc"]: "";
        $ex = explode(",", $acc);
        if(count($ex)==0)return "";
        //$arr = [];
        $arr = ["file_extension"];
        foreach($ex as $v){
            if($v!="")array_push($arr, $d2[$v]);
        }
        return implode(",", $arr);
    }
    public function getDataField($data){
        $opt = json_decode($this->opt, true)??[];
        $elements = [];
        $t = $opt["t"];
        $f_key = "id";
        $f_text = 'name';
        if($t == 0){
            $v = $opt["v"]??[];

            foreach($v as $x){
                array_push($elements,(object)["id"=>$x[1], "name"=>$x[0]]);
            }
        }elseif($t == 1)
            $elements = $data['doctypes'] ?? [];
        elseif($t == 2)
            $elements = $data['groups'] ?? [];
        elseif($t == 3 ) {
            $elements = $data['countries'] ?? [];
            $f_key = "name";
        }
        elseif($t == 4 ){
            $elements = $data['departments'] ?? [];
            $f_key = "nombre";
            $f_text = 'nombre';
        }
        elseif($t == 5 ){
            $elements = $data['provinces'] ?? [];
            $f_key = "nombre";
            $f_text = 'nombre';
        }
        elseif($t == 6) {
            $elements = $data['emails'] ?? [];
            $f_text = 'name - email';
        }
        elseif($t == 7 )
            $elements = $data['domains'] ?? [];
        return compact("elements", "f_key", "f_text");
    }
    public function getDataQ(){
        $value = $this->_value  ?? "[]";
        return json_decode($value, true)??[];
    }
    public function getFileText($id, $id2=0){
        $is_detail = $this->is_detail??0;
        if($this->m_field_id == 2){
            if($id<1 || ($id2<1 && $is_detail==1))return "";
            $name = "{$id}-{$this->id}";
            $file = "m/ed/{$name}.html";
            if($is_detail==1){
                $name = "{$id}-{$id2}-{$this->id}";
                $file = "m/ed2/{$name}.html";
            }
            $exists = \Storage::disk('real_public')->exists($file);
            if($exists)return \Storage::disk('real_public')->get($file);
        }
        return "";
    }
}
