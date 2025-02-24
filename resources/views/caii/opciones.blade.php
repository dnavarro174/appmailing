@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper  mt-3">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card m-auto">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Opciones</h4>
                  {{-- <p class="card-description">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste!
                  </p> --}}
                

                  <form class="forms-sample" id="caiiForm" action="{{ route('caii.updateO', $datos[0]->id) }}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">

                        <div class="col-sm-4 form-group">
                          <label class="col-form-label" for="plantilla_invitacion">Plantilla Invitación <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8 form-group">
                          
                          <select class="form-control text-uppercase required" autofocus name="plantilla_invitacion" id="plantilla_invitacion">
                              <option value="">SELECCIONAR...</option>
                              @foreach($plantillas as $plan)
                                <option value="{{$plan->id}}"
                                  @if($plan->id == $datos[0]->plantilla_invitacion) selected="" @endif 
                                  >{{$plan->nombre}}</option>
                              @endforeach
                              
                          </select>
                        
                        </div>

                        <div class="col-sm-4 form-group">
                          <label class="col-form-label" for="plantilla_conf">Plantilla Confirmación <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8 form-group">
                          
                          <select class="form-control text-uppercase required" name="plantilla_conf" id="plantilla_conf">
                              <option value="">SELECCIONAR...</option>
                              @foreach($plantillas as $plan)
                                <option value="{{$plan->id}}"
                                  @if($plan->id == $datos[0]->plantilla_conf) selected="" @endif 
                                  >{{$plan->nombre}}</option>
                              @endforeach
                              
                          </select>
                        
                        </div>

                        <div class="col-sm-4 form-group">
                          <label class="col-form-label" for="plantilla_noinvitado">Plantilla No Invitado <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8 form-group">
                          
                          <select class="form-control text-uppercase required" name="plantilla_noinvitado" id="plantilla_noinvitado">
                              <option value="">SELECCIONAR...</option>
                              @foreach($plantillas as $plan)
                                <option value="{{$plan->id}}"
                                  @if($plan->id == $datos[0]->plantilla_noinvitado) selected="" @endif 
                                  >{{$plan->nombre}}</option>
                              @endforeach
                              
                          </select>
                        
                        </div>

                        <div class="col-sm-4 form-group">
                          <label class="col-form-label" for="programacion_id">Programación <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8 form-group">
                          
                          <select class="form-control text-uppercase required" name="programacion_id" id="programacion_id">
                              <option value="">SELECCIONAR...</option>
                              @foreach($progs as $prog)
                                <option value="{{$prog->id}}"
                                  @if($prog->id == $datos[0]->programacion_id) selected="" @endif 
                                  >{{$prog->codigo}} - {{$prog->nombre}}</option>
                              @endforeach
                              
                          </select>
                        
                        </div>


                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro1_vac">Foro 1: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro1_vac" name="foro1_vac" placeholder="Código" required="" value="{{ $datos[0]->foro1_vac }}" />
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro2_vac">Foro 2: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro2_vac" name="foro2_vac" placeholder="Código" required="" value="{{ $datos[0]->foro2_vac }}" />
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro3_vac">Foro 3: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro3_vac" name="foro3_vac" placeholder="Código" required="" value="{{ $datos[0]->foro3_vac }}" />
                      </div>

                       <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro4_vac">Foro 4: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro4_vac" name="foro4_vac" placeholder="Código" required="" value="{{ $datos[0]->foro4_vac }}" />
                      </div>

                       <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro5_vac">Foro 5: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro5_vac" name="foro5_vac" placeholder="Código" required="" value="{{ $datos[0]->foro5_vac }}" />
                      </div>

                       <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="foro6_vac">Foro 6: Vacantes Disponibles <span class="text-danger">*</span></label>
                        
                      </div>
                      <div class="col-sm-8 form-group">
                       <input type="text" class="form-control text-uppercase" id="foro6_vac" name="foro6_vac" placeholder="Código" required="" value="{{ $datos[0]->foro6_vac }}" />
                      </div>
                      
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('caii.index')}}" class="btn btn-light">Volver al listado</a>
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