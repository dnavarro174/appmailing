@extends('layout.home')

@section('content')

<div class="horizontal-menu bg_fondo">
    <!-- partial:partials/_navbar.html -->

    {{-- @include('layout.nav_superior') --}}
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper ">
      
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper pt-0" style="background: none;">
          <div class="container texto_caii">
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="caiiForm" action="{{ route('caii.confirmar') }}" method="post" enctype="multipart/form-data" autocomplete="on">
                  
                  {!! csrf_field() !!}

                  <div class="row ">

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                        
                          <div class="card-body">
                            <h4 class="card-title" style="color: #ed1c24 !important;">Indicaciones</h4>{{-- ELECCIÓN DE ACTIVIDADES --}}
                            @if(Session::has('actividades_selec'))
                              <p class="alert alert-danger">{!! Session::get('actividades_selec') !!}</p>
                            @endif

                            {{-- <p class="card-text">
                              A continuación, deberá seleccionar la actividad de su preferencia, recuerde que solo podrá elegir una actividad por cada horario estipulado a los cuales deberá asistir puntualmente. La capacidad es limitada.
                            </p>
                            <p class="card-text">
                              Se entregará constancia de participación electrónica a las personas que hayan asistido a los 2 días a la Conferencia Anual Internacional por la Integridad - CAII 2019.
                            </p> --}}
                            <p class="card-text">
                              Gracias por validar sus datos.
                              La CAII 2019 estará conformada por diferentes actividades, dirigidas a todos los participantes.
                              Agradeceremos seguir las siguientes indicaciones: 
                            </p>
                            <ul>
                              <li>El 02 de diciembre habrán 3 bloques (11:30 / 14:30 / 17:00 hrs.), donde se realizarán
                              actividades en simultáneo, usted deberá elegir una actividad por bloque.</li>
                              <li>El 03 de diciembre habrán 2 bloques (11:15 / 14:00 hrs.), donde se realizarán
                              actividades en simultáneo, usted deberá elegir una actividad por bloque.</li>
                              <li>Las actividades que elijan serán registradas en sistema y podrá verlas en su gafete y
                              correo electrónico de confirmación.</li>
                              <li>Una vez elegida la actividad no se podrá cambiar.</li>
                              <li>Habrá control de ingreso (solo podrán ingresar los que se encuentren en lista)</li>
                              <li>Debe asistir puntualmente</li>
                            </ul>
                            <p class="card-text">
                              A continuación, deberá seleccionar las actividades de su mayor interes:
                            </p>

                          </div>
                      </div>
                    </div>
                            

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">

                      <div class="card">
                        <div class="card-body sinpadding_mobile">
                                {{-- <div class="row mr-4 ml-4">
                                  <h4 class="card-title">
                                    Jueves, 06 de diciembre de 2018 
                                  </h4>
                                </div> --}}
                                <div class="form-group row">
                                  <div class="col-sm-12">
                                   @if(Session::has('no_actividades'))
                                    <p class="alert alert-danger">{{ Session::get('no_actividades') }}</p>
                                    @endif
                                  </div>
                                </div>
                                <div class="row mr-4 ml-4">
                                  {{-- <div class="col-sm-12 col-md-12"> --}}

                                  @foreach($data as $i=>$d)
                                  <div class="col-sm-12 col-md-12 pb-3 ">
                                    <div class="row">
                                      <div class="col-sm-12 col-md-12 sinpadding_mobile">

                                        <?php
                                          $xdias = \Carbon\Carbon::parse($eventos->fechai_evento)->format('d');
                                          $xmes_ano = \Carbon\Carbon::parse($eventos->fechai_evento)->format('m-Y');
                                          $ev_f_desde = $xdias+$i;
                                          $ndia = $d["fecha_desde"];

                                          $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                          $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Dic");

                                          //$ndia = $xdias+$i.'-'.$xmes_ano;
                                   
                                          $dia_texto = \Carbon\Carbon::parse($ndia)->format('d')." ".$meses[\Carbon\Carbon::parse($ndia)->format('n')-1];
                                        ?>

                                        @foreach($d["horas"] as $j=>$d2)
                                          <h2 class="card-title tit_act" style="color: #ed1c24 !important;">
                                            <span>{{$dia_texto}}</span>
                                            <em class="linea" ></em>
                                          </h2>
                                            {{-- {{$d["fecha_desde"]}} --}}

                                          <h4 class="card-title color_gris text-transform-none">{{$d2["hora_inicio"]}} HRS. - Elegir una actividad en este bloque</h4>

                                          <div class="col-sm-12 col-md-12 pb-3  ">
                                            @foreach($d2["actividades"] as $k=>$d3)
                                            <div class="row  @if($d3["vacantes"] <= $d3["inscritos"]) alert alert-danger @endif ">
                                              <div class="col-sm-12 col-md-12 form-control2">
                                                <label class="form-radio">
                                                  <input type="radio" id="varios[{{$i}}_{{$j}}]" class="form-check-" name="varios[{{$i}}_{{$j}}]" value="{{$d3["id"]}}" required="" @if($d3["vacantes"] <= $d3["inscritos"]) disabled @endif>
                                                      <em>{{$d3["titulo"]}}</em> <span>{{$d3["subtitulo"]}} </span>
                                                </label>
                                              </div>

                                              <div class="col-sm-12 col-md-3">
                                                <img src="{{ asset('images/act/')}}/{{$d3["imagen"]}}" alt="{{$d3["titulo"]}} {{date('Y')}}" class="img-fluid">
                                              </div>
                                              <div class="col-sm-12 col-md-9 text-justify">

                                                <p class="mb-0"><strong>Capacidad: </strong>{{$d3["vacantes"]}} </p>
                                                <p class=""><strong>Ubicación:</strong> {{$d3["ubicacion"]}}</p>

                                                {!!$d3["desc_ponentes"] !!}
                                                <p>{!!$d3["desc_actividad"] !!}</p>

                                                <?php if($d3["vacantes"] <= $d3["inscritos"]) echo "<p class='text-center pt-3'><strong>Las vacantes para esta actividad se encuentran agotadas.</strong></p>" ?>

                                                @if(Session::has('foro_1'))
                                                  <p class="alert alert-danger">{{ Session::get('foro_1') }}</p>
                                                @endif


                                              </div>

                                            </div>
                                            
                                          
                                            {{-- <div class="col-sm-12 col-md-12">Hora inicio: {{$d2["hora_inicio"]}}</div> --}}
                                            
                                              {{-- <div class="col-sm-12 col-md-12">Actividad: {{$d3["titulo"]}}
                                                <input type="radio" name="varios[{{$i}}_{{$j}}]" value="{{$d3["id"]}}">
                                              </div> --}}

                                             @endforeach
                                          </div>
                                        @endforeach
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach

                                  

                                  <div class="col-sm-12 col-md-12">
                                    <div class="form-group ">
                                      {{-- <input type="hidden" name="id_evento" id="id_evento" value="{{ $id_evento }}">
                                      <input type="hidden" name="user" id="user" value="{{ $user }}"> --}}
                                      <div class="col-sm-12 col-md-12 p-4">
                                        {{-- <a href="#" class="btn btn-secondary" id="btn_capa_tres_a">Atras</a> --}}
                                        <button type="submit" class="btn btn-dark mr-2" @if(Session::has('no_actividades')) disabled="" @endif>Confirmar / Confirm</button>
                                      </div>
                                    </div>

                                  </div>

                                </div> {{-- end row --}}


                          </div>
                        </div>
                      </div>
                      
                    





                  </div>


                </form>
                
              </div>
            </div>
          </div>
          
          
          
        </div>
<style>
.form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
.form-control2 label.form-radio em, .color_verde{color: #41464a;font-style: normal;}
.form-control2 label.form-radio span,.color_azul{color:#41464a;}
.texto_foros p{padding-left: 25px;}
</style>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


@endsection
