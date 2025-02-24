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
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Editar Formulario</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('caii_form.update', $datos->eventos_id) }}" method="post" enctype="multipart/form-data" >

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
                        <label for="plantilla" class="col-sm-2 col-form-label d-block">Descripción <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <textarea required="" placeholder="Descripción en HTML" class="form-control" name="descripcion" id="" cols="30" rows="10">{{ $datos->descripcion_form }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="img_cabecera" class="col-sm-2 col-form-label d-block">Imagen Cabecera <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          @if($datos->img_cabecera != "")
                            <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="Img" class="img-fluid">
                          @endif

                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>{{$datos->img_cabecera}} / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_cabecera" id="img_cabecera" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->img_cabecera }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="img_footer" class="col-sm-2 col-form-label d-block">Imagen Footer <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          @if($datos->img_footer != "")
                            <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="Img" class="img-fluid">
                          @endif
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
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="grupo" @if($datos->grupo == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Grupo <i class="input-helper"></i></label>
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
                                    <input name="profesion" @if($datos->profesion == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Profesión <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                          {{-- fila 4 --}}
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="entidad" @if($datos->entidad == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Empresa / Entidad <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="cargo" @if($datos->cargo == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Cargo <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email" @if($datos->email == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          {{-- fila 5 --}}
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="celular" @if($datos->celular == 1) checked="" @endif type="checkbox" class="form-check-input" value="1"> Celular <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>

                        </div>
                      </div>

                     


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Actualizar</button>
                        
                        <a href="{{ route('caii.index') }}" class="btn btn-light">Volver atrás</a>
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