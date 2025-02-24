<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ubigeo extends Controller
{
    function index()
    {
        $departamentos = DB::table('ubigeos')
            ->select('departamento')
            ->groupBy('departamento')
            ->get();
        return view('biblio.form1')->with('departamentos', $departamentos);
    }

    function posgrado()
    {
        $departamentos = DB::table('ubigeos')
            ->select('departamento')
            ->groupBy('departamento')
            ->get();
        return view('posgrado.form1')->with('departamentos', $departamentos);
    }

    function fetch(Request $request)
    {
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('ubigeos_peru')
        ->where($select, $value)
        ->groupBy($dependent)
        ->get();
        $output = '<option value="" class="text-uppercase">SELECCIONE '.mb_strtoupper($dependent).'</option>';
        foreach($data as $row)
        {
            $output .= '<option value="'.$row->$dependent.'">'.$row->$dependent.'</option>';
        }
        echo $output;
    }
}

