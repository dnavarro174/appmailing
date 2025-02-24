@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Plantilla HTML</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('plantillaemail.update', $plantilla_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    

                    <div class="row">
                      <div class="col-xs-12 col-sm-5">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                          <input type="text" class="form-control text-uppercase" autofocus id="nombre" name="nombre" required="" value="{{ $plantilla_datos->nombre }}">
                          {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="asunto">Asunto <span class="text-danger">*</span></label>
                          <input type="text" class="form-control text-" id="asunto" name="asunto" required="" value="{{ $plantilla_datos->asunto }}">
                          {!! $errors->first('asunto', '<span class=error>:message</span>') !!}
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="flujo_ejecucion">Flujo Ejecución <span class="text-danger">*</span></label>
                          <select class="form-control text-uppercase" name="flujo_ejecucion" id="flujo_ejecucion" required="">
                              <option value="">SELECCIONAR...</option>
                              <option value="INVITACION"
                              @if('INVITACION'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >INVITACION</option>
                              <option value="CONFIRMACION"
                              @if('CONFIRMACION'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >CONFIRMACION</option>
                              <option value="RECORDATORIO" 
                              @if('RECORDATORIO'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >RECORDATORIO</option>
                              <option value="NOINVITADO" 
                              @if('NOINVITADO'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >NO INVITADO</option>
                              <option value="LEY-27419" 
                              @if('LEY-27419'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >LEY 27419</option>
                              <option value="MAILING" 
                              @if('MAILING'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >MAILING</option>

                          </select>
                          {!! $errors->first('flujo_ejecucion', '<span class=error>:message</span>') !!}
                        </div>

                      
                      </div> {{-- end column 1 --}}

                      <div class="col-xs-12 col-sm-7">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="plantillahtml">HTML</label>
                          <textarea type="text" class="form-control" id="plantillahtml" name="plantillahtml" rows="19">{{ $html_1 }}</textarea>
                          {!! $errors->first('plantillahtml', '<span class=error>:message</span>') !!}
                        </div>

                        @if($plantilla_datos->flujo_ejecucion == 'LEY-27419')
                          <div class="col-sm-12 form-group ">
                            <label class=" col-form-label" for="plantilla_si">HTML Acepta confirmación</label>
                            <textarea type="text" class="form-control " id="plantilla_si" name="plantilla_si" rows="6">{{ $plantilla_datos->plantilla_gafete }}</textarea>
                            {!! $errors->first('plantilla_si', '<span class=error>:message</span>') !!}
                          </div>

                          <div class="col-sm-12 form-group ">
                            <label class=" col-form-label" for="plantilla_no">HTML NO Acepta confirmación</label>
                            <textarea type="text" class="form-control " id="plantilla_no" name="plantilla_no" rows="6">{{ $plantilla_datos->plantilla_extra }}</textarea>
                            {!! $errors->first('plantilla_no', '<span class=error>:message</span>') !!}
                          </div>
                        @endif

             

                      </div> {{-- end column 2 --}}

                      <div class="col-xs-12 col-sm-5"></div>
                      <div class="col-xs-12 col-sm-7">
                        <div class="col-sm-12">
                          <textarea id="summernote" name="cancelar">
                            {{ $html_2 }}
                          </textarea>
                        </div>
                      </div>
                    </div> {{-- end row --}}

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('plantillaemail.index')}}" class="btn btn-light">Volver al listado</a>
                        <a class="btn-sm btn-link " href="#" id="{{ $plantilla_datos->id }}" ><span class="openHTML" data-id="{{ $plantilla_datos->id }}"><i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i> Ver HTML Full</span></a>
                        

                      </div>

                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- modal openHTML --}}
        <div class="modal fade ass" id="openHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-800" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="plantillaHTML"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- modal openHTML --}}
        

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

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
  $("#flujo_ejecucion").on('change', function(){
    var tipo = $("#flujo_ejecucion").val();
    console.log('click flujo_ejecucion :');

    if(tipo == "LEY-27419"){
      $(".auto_conf_div").removeClass('d-none');
    }else{
      $(".auto_conf_div").addClass('d-none');
    }
  });
</script> 

<script>

//$('#summernote').summernote();
$('#summernote').summernote({
    placeholder: 'BLOQUE PARA CANCELAR SUSCRIPCIÓN',
    tabsize: 2,
    height: 180,
    toolbar: [
      /*['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],*/
      ['view', ['fullscreen', 'codeview']]//, 'help'
    ]
});
$('#plantillahtml').summernote({
    placeholder: 'HTML...',
    tabsize: 2,
    height: 700,
    toolbar: [
      ['view', ['fullscreen', 'codeview']]//, 'help'
    ]
});
</script>
@endsection