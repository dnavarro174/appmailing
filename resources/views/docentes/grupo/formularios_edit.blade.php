@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Editar Formulario</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('grupo-doc_form.update', $datos->eventos_id) }}" method="post" enctype="multipart/form-data" >

                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                      <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                          @if(count($errors)>0)
                            <div class="alert alert-danger">
                              Error al subir la imagen:<br>
                              <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                              </ul>
                            </div>
                          @endif
                        </div>
                      </div>
                    
                      <div class="form-group row">
                        <label for="plantilla" class="col-sm-2 col-form-label d-block">Descripción </label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción en HTML" class="form-control" name="descripcion" id="" cols="30" rows="5">{{ $datos->descripcion_form }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" id="auto">
                        <label for="auto_conf" class="col-sm-8 col-form-label">Desea incluir imágenes de cabecera y pie de página en el formulario</label>
                        <div class="col-sm-4">
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email" name="imagen" type="checkbox" class="form-check-input" value="1" @if($datos->imagen == 1) checked="" @endif> SI <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div> {{-- end confirmacion --}}
                      
                      <div class="form-group row show_block @if($datos->imagen != 1) d-none @endif">
                        <label for="img_cabecera" class="col-sm-12 col-form-label d-block">Imagen Cabecera <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="Img header" class="img-fluid @if($datos->imagen != 1) d-none @endif">

                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>{{$datos->img_cabecera}} / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_cabecera" id="img_cabecera" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->img_cabecera }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>
                      
                      <div class="form-group row show_block @if($datos->imagen != 1) d-none @endif">
                        <label for="img_footer" class="col-sm-12 col-form-label d-block">Imagen Footer <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="Img footer" class="img-fluid @if($datos->imagen != 1) d-none @endif">
                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>{{$datos->img_footer}} / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_footer" id="img_footer" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->img_footer }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>
                      

                      <div class="form-group row">
                        <label for="auto_conf" class="col-sm-2 col-form-label">Campos</label>
                        <div class="col-sm-10">
                          <div class="form-group row">
                            <h4>Datos Personales</h4>
                          </div>



                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="grupo" type="checkbox" class="form-check-input" value="1" checked> Tipo de Participante <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="tipo_doc" @if($datos->tipo_doc == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Tipo Documento <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="dni" @if($datos->dni == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> DNI <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          {{-- fila 2 --}}
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="nombres" @if($datos->nombres == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Nombres <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_paterno" @if($datos->ap_paterno == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Apellido Paterno <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_materno" @if($datos->ap_materno == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Apellido Materno <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            

                          </div>

                          <div class="form-group row">
                            <h4 class='h6'>Nacimiento</h4>
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="fecha_nac" type="checkbox" class="form-check-input" value="1" @if($datos->fecha_nac == 1) checked="" @endif> Fecha Nacimiento <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="edad" type="checkbox" class="form-check-input" value="1" @if($datos->edad == 1) checked="" @endif> Edad <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          

                          

                          {{-- fila 3 --}}
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="pais" @if($datos->pais == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> País <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="departamentos" @if($datos->departamentos == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Departamento <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="provincia" type="checkbox" class="form-check-input" value="1" @if($datos->provincia == 1) checked="" @endif> Provincia <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="distrito" type="checkbox" class="form-check-input" value="1" @if($datos->distrito == 1) checked="" @endif> Distrito <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email" @if($datos->email == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Correo Electrónico Personal <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email_labor" type="checkbox" class="form-check-input" value="1" @if($datos->email_labor == 1) checked="" @endif> Correo Electrónico Institucional <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <h4 class='h6'>Domicilio Actual</h4>
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="departamentos" @if($datos->departamentos == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Departamento <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="provincia" type="checkbox" class="form-check-input" value="1" @if($datos->provincia == 1) checked="" @endif> Provincia <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="distrito" type="checkbox" class="form-check-input" value="1" @if($datos->distrito == 1) checked="" @endif> Distrito <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            

                          
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="direccion" type="checkbox" class="form-check-input" value="1" @if($datos->direccion == 1) checked="" @endif> Dirección <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="celular" @if($datos->celular == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Celular <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          {{-- fila 4 --}}
                          
                            

                            <div class="form-group row">
                              <h4>II. Formación Académica</h4>
                            </div>
  
                            <div class="form-group row">
                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="gradoprof" @if($datos->gradoprof == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Nivel Académico <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="profesion" type="checkbox" class="form-check-input" value="1" @if($datos->profesion == 1) checked="" @endif> Carrera Profesional <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="cargo" type="checkbox" class="form-check-input" value="1" @if($datos->cargo == 1) checked="" @endif> Especialidad <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="entidad" type="checkbox" class="form-check-input" value="1" @if($datos->entidad == 1) checked="" @endif> Centro de Estudios <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="email_labor2" type="checkbox" class="form-check-input" value="1" @if($datos->email_labor2 == 1) checked="" @endif> Fecha Obtención del Grado/Título <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_8" type="checkbox" class="form-check-input" value="1" @if($datos->opc_8 == 1) checked="" @endif> N° de Apostillado (1) <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                            </div> 
                            
                            <div class="form-group row">
                              <h4>III. Capacitaciones</h4>
                            </div>
  
                            <div class="form-group row">
                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="compago" @if($datos->compago == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Nombre <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="decjur" type="checkbox" class="form-check-input" value="1" @if($datos->decjur == 1) checked="" @endif> Tipo de Capacitación <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="ficins" type="checkbox" class="form-check-input" value="1" @if($datos->ficins == 1) checked="" @endif> Centro de Estudios <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="nvoucher" type="checkbox" class="form-check-input" value="1" @if($datos->nvoucher == 1) checked="" @endif> Fecha Inicio <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="fechadepo" type="checkbox" class="form-check-input" value="1" @if($datos->fechadepo == 1) checked="" @endif> Fecha de Término <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="cod_curso" type="checkbox" class="form-check-input" value="1" @if($datos->cod_curso == 1) checked="" @endif> Condición Actual <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="nom_curso" type="checkbox" class="form-check-input" value="1" @if($datos->nom_curso == 1) checked="" @endif> Horas Cronologicas <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                            </div> {{--  end form-group   --}}

                            <div class="form-group row">
                              <h4>IV. Experiencia Laboral</h4>
                            </div>
  
                            <div class="form-group row">
                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="si_cgr" @if($datos->si_cgr == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Empresa / Institución <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="codigo_cgr" type="checkbox" class="form-check-input" value="1" @if($datos->codigo_cgr == 1) checked="" @endif> Tipo de Empresa <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="foto" type="checkbox" class="form-check-input" value="1" @if($datos->foto == 1) checked="" @endif> Cargo Puesto <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="ubigeo" type="checkbox" class="form-check-input" value="1" @if($datos->ubigeo == 1) checked="" @endif> Modalidad <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="cv" type="checkbox" class="form-check-input" value="1" @if($datos->cv == 1) checked="" @endif> Actividad Desarrollada <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="discapacidad" type="checkbox" class="form-check-input" value="1" @if($datos->discapacidad == 1) checked="" @endif> Fecha Inicio <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_0" type="checkbox" class="form-check-input" value="1" @if($datos->opc_0 == 1) checked="" @endif> Fecha de Término <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                            </div> {{--  end form-group   --}}
                            <div class="form-group row">
                              <h4>V. Experiencia en Docencia</h4>
                            </div>
  
                            <div class="form-group row">
                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_1" @if($datos->opc_1 == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Institución <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_2" type="checkbox" class="form-check-input" value="1" @if($datos->opc_2 == 1) checked="" @endif> Nombre de la Institución <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_3" type="checkbox" class="form-check-input" value="1" @if($datos->opc_3 == 1) checked="" @endif> Nivel <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_4" type="checkbox" class="form-check-input" value="1" @if($datos->opc_4 == 1) checked="" @endif> Curso a Cargo <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_5" type="checkbox" class="form-check-input" value="1" @if($datos->opc_5 == 1) checked="" @endif> Fecha Inicio <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_6" type="checkbox" class="form-check-input" value="1" @if($datos->opc_6 == 1) checked="" @endif> Fecha de Término <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-sm-4">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="opc_7" type="checkbox" class="form-check-input" value="1" @if($datos->opc_7 == 1) checked="" @endif> Elegir Cursos <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>


                            </div> {{--  end form-group   --}}


                          <div class="form-group row">
                            <h4>VI. Declaraciones Juradas</h4>
                          </div>

                          <div class="form-group row">
                            <div class="col-sm-6 p-0">
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_1" type="checkbox" class="form-check-input" value="1" @if($datos->preg_1 == 1) checked="" @endif> Pregunta 1 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_2" type="checkbox" class="form-check-input" value="1" @if($datos->preg_2 == 1) checked="" @endif> Pregunta 2 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_3" type="checkbox" class="form-check-input" value="1" @if($datos->preg_3 == 1) checked="" @endif> Pregunta 3 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6 p-0">
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_4" type="checkbox" class="form-check-input" value="1" @if($datos->preg_4 == 1) checked="" @endif> Pregunta 4 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_5" type="checkbox" class="form-check-input" value="1" @if($datos->preg_5 == 1) checked="" @endif> Pregunta 5 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-12">
                                <div class="form-check">
                                  <div class="col-sm-10 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input name="preg_6" type="checkbox" class="form-check-input" value="1" @if($datos->preg_6 == 1) checked="" @endif> Pregunta 6 <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          
                          </div>

                          {{-- fila 4 --}}
                          

                          <div class="form-group row">
                            <div class="col-sm-8">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label" id="terminos">
                                    <input name="terminos" type="checkbox" class="form-check-input" value="1" @if($datos->terminos == 1) checked="" @endif> Términos y condiciones <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                     
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Actualizar</button>
                        
                        <a href="{{ route('grupo-doc.index') }}" class="btn btn-light">Volver atrás</a>
                      </div>

                    </div>

                  </form>
                  
                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        

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

@section('scripts')
<style>
.hidden_email, .hidden_whatsapp{display: none;}
.show_block{display: block;}
</style>
<script>
console.log('Ready eventos');
$('document').ready(function(){

  // seleccionar todos

    $('#confirm_email').change(function() {
      if ($('#confirm_email').is(':checked')) {
        $('.show_block').addClass('d-block').removeClass('d-none');
        $('#img_cabecera, #img_footer').prop('required',true);
      }else{
        $('.show_block').addClass('d-none').removeClass('d-block');
        $('#img_cabecera, #img_footer').removeAttr('required');
      }

    });

    $('#confirm_msg').change(function() {

      if ($('#confirm_msg').is(':checked')) {
        $('.hidden_whatsapp').css('display','block');
      }else{
        $('.hidden_whatsapp').css('display','none');
      }

    });

});
</script>

@endsection