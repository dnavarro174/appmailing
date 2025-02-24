<?php
namespace App\Traits;

use App\Models\MAttr;

trait ManageSeeder {
    public function getDefaultFields()
    {
        $attrs = MAttr::select("id", "name", "is_detail")->get();
        $data = [];
        $attrs->each(function ($item, $key) use (&$data) {
            $pref = $item->is_detail==1?"":"_";
            $data[$pref.$item->name] = "_".$item->id;
        });
        return $data;
    }
}
