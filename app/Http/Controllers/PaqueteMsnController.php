<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class PaqueteMsnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function msn_whatsapp()
    {

        $datos = DB::table('tb_msn')->first();
  
        return view('caii.msn.index', compact('datos'));
    }

    
}
