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
        <div class="content-wrapper" style="background: none;">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="caiiForm" action="{{ route('caii.confirmar') }}" method="post" enctype="multipart/form-data" autocomplete="on">
                  
                  {!! csrf_field() !!}

                  <div class="row ">

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        {{-- <img src="https://enc-ticketing.org/tktv1/caii/Validacion_Datos2_files/Header.jpg" alt="encabezado caii {{date('Y')}}" class="card-img-top"> --}}
                        <img src="{{ asset('images/banner_form.jpg') }}" alt="encabezado caii {{date('Y')}}" class="card-img-top">
                        
                          <div class="card-body">
                            <h4 class="card-title">ELECCIÓN DE FOROS</h4>
                            @if(Session::has('foro_seleccionado'))
                              <p class="alert alert-danger">{!! Session::get('foro_seleccionado') !!}</p>
                            @endif
                            <p>
                              A continuación, deberá seleccionar el foro de su preferencia, recuerde que solo podrá elegir un foro por día al cual deberá asistir puntualmente. La capacidad de los foros es limitada. 
                            </p>

                            <p class="alert alert-warning">
                              Se entregará constancia de participación a las personas que hayan <strong>asistido los 2 días a la Conferencia Anual Internacional por la Integridad - CAII {{date('Y')}}</strong>.
                            </p>

                            {{-- <div class="row mr-4 ml-4">
                                  <h4 class="card-title">
                                    Jueves, 06 de diciembre de {{date('Y')}} 
                                  </h4>
                                </div>
                                <div class="row mr-4 ml-4">

                                  <h4 class="card-title color_verde">Registro de participantes y entrega de materiales | <span class="text-dark">08:00 hrs. - </span><span class="text-danger">Obligatoria</span></h4>

                                  <h4 class="card-title color_verde">CEREMONIA DE INAUGURACIÓN | <span class="text-dark">08:45 hrs. - </span><span class="text-danger">Obligatoria</span></h4>

                                  <h4 class="card-title color_verde">Conferencia Magistral "Tendencias en prevención de la corrupción: El poder de una denuncia" | <span class="text-dark">09:45 hrs. - </span><span class="text-danger">Obligatoria</span></h4>

                                  <p>A cargo del Dr. Stephen M. Kohn, Fundador y Director Ejecutivo del Centro Nacional de Denunciantes de los Estados Unidos de América, en temas de fraude en contratos públicos, responsabilidad gubernamental y corporativa, entre otros.</p>
                                  

                                </div>  --}}
                          </div>
                      </div>
                    </div>
                            

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                                {{-- <div class="row mr-4 ml-4">
                                  <h4 class="card-title">
                                    Jueves, 06 de diciembre de 2018 
                                  </h4>
                                </div> --}}
                                <div class="row mr-4 ml-4">


                                  {{-- <h4 class="card-title color_verde">
                                    <label class="form-radio">
                                      <input type="radio" class="form-check-" name="foro_1" value="7" >
                                      <em>CONFERENCIA PLENARIA 1:</em>

                                      Integridad Pública y Prevención de la Corrupción | 
                                      <span class="text-dark">10:45 hrs. - </span><span class="text-danger">Obligatoria</span>

                                    </label>
                                  </h4>
                                  <p class="text-justify">
                                    La presente sesión plenaria busca reflexionar sobre las condiciones y factores que hacen de un gobierno, un "buen" gobierno, y ofrecer una evaluación sobre los principales riesgos de integridad que existen en la actualidad, así como sobre las principales recomendaciones que ofrece la experiencia reciente de la región para la formulación y adopción de mecanismos de respuesta y marcos de integridad efectivos, y orientados a proteger y defender el interés público sobre los intereses privados en el Estado.
                                  </p>

                                  <div class="col-sm-12 col-md-12">
                                    <p>
                                      <strong>Moderador:</strong><br>
                                      - Carlos Vargas Mas, Gerente de Prevención de la Contraloría General de la República del Perú.<br><br>
                                      
                                        <strong>Panelistas:</strong><br>
                                        - David Rogelio Colmenares Páramo, Titular de la Auditoría Superior de la Federación (ASF) de México.<br>
                                        - Manuel Villoria Mendieta, Director del Departamento de Gobierno y Administración Pública del Instituto Universitario Ortega y Gasset de España.<br>
                                        - Paul Flather, Profesor del Mansfield College Oxford de Reino Unido.<br>
                                        - David Crocker, Profesor e investigador de la Escuela de Políticas Públicas de la Universidad de Maryland de los Estados Unidos de América.
                                        
                                      
                                    </p>
                                  </div> --}}
                                  

                                  @foreach($foros_datos as $foros)
                                    <?php

                                    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                             
                                    $dia_texto = $dias[\Carbon\Carbon::parse($foros->fecha_desde)->format('w')]." ".\Carbon\Carbon::parse($foros->fecha_desde)->format('d')." de ".$meses[\Carbon\Carbon::parse($foros->fecha_desde)->format('n')-1]. " del ".\Carbon\Carbon::parse($foros->fecha_desde)->format('Y');
//{{ \Carbon\Carbon::parse($datos->fechai_evento)->format('d.m.Y') }}
                                  ?>

                                    @if($foros->id == 1)
                                      <h4 class="card-title" style="text-transform: none;">
                                        {{$dia_texto }} {{-- | 10:45 hrs. --}}
                                      </h4>
                                    @endif
                                    @if($foros->id == 4)
                                      <h4 class="card-title" style="text-transform: none;">
                                        {{$dia_texto }} {{-- | 10:45 hrs. --}}
                                      </h4>
                                    @endif
                                    
                                    {{-- @if($foros->id == 1) --}}
                                    <div class="col-sm-12 col-md-12 pb-3 <?php if($foros->vacantes <= $foros->inscritos) echo "alert alert-danger" ?>">
                                      <div class="form-group texto_foros">
                
                                          <div class="form-control2">
                                            <label class="form-radio">
                                              <input type="radio" class="form-check-" name="foro_{{ $foros->dia }}" value="{{ $foros->id }}" <?php if($foros->vacantes <= $foros->inscritos) echo "disabled"  ?> required="">
                                              <em>{{ $foros->nombre_abre }}</em> <span>{{ $foros->nombre_completo }}</span>
                                              </label>
                                            {{-- <p class="text-justify">
                                              El foro 1 aborda la experiencia de la gobernanza e integridad pública a nivel subnacional, pues es a estos niveles que se producen las interacciones más directas entre el ciudadano y el aparato estatal. 
                                            </p> --}}
                                            <p class="text-justify">
                                              {!! $foros->descripcion !!}
                                            </p>
                                              <?php if($foros->vacantes <= $foros->inscritos) echo "<p class='text-center'><strong>Las vacantes para este foro se encuentran agotadas.</strong></p>" ?>
                                            
                                            @if(Session::has('foro_1'))
                                              <p class="alert alert-danger">{{ Session::get('foro_1') }}</p>
                                            @endif
                                          </div>

                                      </div>
                                    </div>
                                    {{-- @endif --}}
                                  
                                  @endforeach

                                  <div class="col-sm-12 col-md-12">
                                    <div class="form-group ">
                                      {{-- <input type="hidden" name="id_evento" id="id_evento" value="{{ $id_evento }}">
                                      <input type="hidden" name="user" id="user" value="{{ $user }}"> --}}
                                      <div class="col-sm-12 col-md-12 p-4">
                                        {{-- <a href="#" class="btn btn-secondary" id="btn_capa_tres_a">Atras</a> --}}
                                        <button type="submit" class="btn btn-primary mr-2">Confirmar</button>
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
.form-control2 label.form-radio em, .color_verde{color:#21AFAF;font-style: normal;}
.form-control2 label.form-radio span,.color_azul{color:#556685;}
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
