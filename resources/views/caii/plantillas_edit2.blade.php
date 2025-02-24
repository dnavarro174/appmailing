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
                  
                  <h4 class="card-title text-transform-none">Edición de las Plantillas HTML</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('caii_plantilla.update', $datos->eventos_id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                      
                      <div class="form-group row">
                        <label for="p_preregistro" class="col-sm-12 col-form-label d-block">Pre - Registro (HTML)</label> 

                        <div class="col-sm-12">
                          <textarea placeholder="Pre - Registro (HTML)" class="form-control" name="p_preregistro" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres 
                            {{-- <a href="#" id="{{ $datos->eventos_id }}" class="openHTML_plantilla" data-name='p_preregistro' data-id="{{ $datos->id }}">
                              <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i>
                            </a> --}}
                            
                            @if($datos->p_preregistro != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_preregistro }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_preregistro_2" class="col-sm-12 col-form-label d-block">Pre - Registro (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pre - Registro (Whatsapp)" class="form-control" name="p_preregistro_2" id="" cols="30" rows="10">{{ $datos->p_preregistro_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 2 --}}
                      <div class="form-group row">
                        <label for="p_conf_inscripcion" class="col-sm-12 col-form-label d-block">Usuario y Contraseña (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (HTML)" class="form-control" name="p_conf_inscripcion" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres 
                            
                            @if($datos->p_conf_inscripcion != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_conf_inscripcion }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                            

                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_conf_inscripcion_2" class="col-sm-12 col-form-label d-block">Usuario y Contraseña (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (Whatsapp)" class="form-control" name="p_conf_inscripcion_2" id="" cols="30" rows="10">{{ $datos->p_conf_inscripcion_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      <div class="form-group row">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (HTML)" class="form-control" name="p_conf_registro" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            @if($datos->p_conf_registro != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_conf_registro }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                            
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (Whatsapp)" class="form-control" name="p_conf_registro_2" id="" cols="30" rows="10">{{ $datos->p_conf_registro_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="p_conf_registro_gracias" class="col-sm-12 col-form-label d-block">Pantallazo confirmación al finalizar el registro</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Sus datos han sido registrados correctamente, se le enviará automáticamente un correo electrónico de confirmación con su GAFETE personalizado." class="form-control" name="p_conf_registro_gracias" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_conf_registro_gracias != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_conf_registro_gracias }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                            
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 4 --}}
                      <div class="form-group row">
                        <label for="p_recordatorio" class="col-sm-12 col-form-label d-block">Recordatorio (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (HTML)" class="form-control" name="p_recordatorio" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_recordatorio != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_recordatorio }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_recordatorio_2" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2" id="" cols="30" rows="10">{{ $datos->p_recordatorio_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 5 --}}
                      <div class="form-group row">
                        <label for="p_negacion" class="col-sm-12 col-form-label d-block">Negación (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (HTML)" class="form-control" name="p_negacion" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres

                            @if($datos->p_negacion != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_negacion }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_negacion_2" class="col-sm-12 col-form-label d-block">Negación (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (Whatsapp)" class="form-control" name="p_negacion_2" id="" cols="30" rows="10">{{ $datos->p_negacion_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 6 --}}
                      <div class="form-group row">
                        <label for="p_baja_evento" class="col-sm-12 col-form-label d-block">Baja de Evento Pantallazo (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (HTML)" class="form-control" name="p_baja_evento" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            @if($datos->p_baja_evento != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_baja_evento }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="p_baja_evento_2" class="col-sm-12 col-form-label d-block">Baja de Evento (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (Whatsapp)" class="form-control" name="p_baja_evento_2" id="" cols="30" rows="10">{{ $datos->p_baja_evento_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 7 --}}
                      <div class="form-group row">
                        <label for="p_preinscripcion_cerrado" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (HTML)" class="form-control" name="p_preinscripcion_cerrado" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres

                            @if($datos->p_preinscripcion_cerrado != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_preinscripcion_cerrado }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_preinscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (Whatsapp)" class="form-control" name="p_preinscripcion_cerrado_2" id="" cols="30" rows="10">{{ $datos->p_preinscripcion_cerrado_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div> -->

                      {{-- plantilla 8 --}}
                      <div class="form-group row">
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block">Evento Cerrado (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Evento Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_inscripcion_cerrado != "")
                            <a href="{{url('')}}/files/html/{{ $datos->p_inscripcion_cerrado }}.html" target="_blank"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_inscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Incripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Incripciones Cerradas (Whatsapp)" class="form-control" name="p_inscripcion_cerrado_2" id="" cols="30" rows="10">{{ $datos->p_inscripcion_cerrado_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div> -->

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2" @if($datos->eventos_id==1) disabled @endif>Actualizar</button>
                        
                        <a href="{{ route('caiieventos.index') }}" class="btn btn-light">Volver al listado</a>{{-- caii.index --}}
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


                {{-- modal openHTML_plantilla --}}
                  <div class="modal fade ass" id="openHTML_plantilla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-800" role="document">
                      <div class="modal-content">
                        
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span> 
                          </button>
                        </div>
                        <div class="modal-body pt-0">
                          <div class="row" id="plantillaHTML">
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  {{-- modal openHTML --}}


@endsection