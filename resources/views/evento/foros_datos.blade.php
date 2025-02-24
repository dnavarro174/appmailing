@extends('layout.home')

@section('content')

<div class="horizontal-menu">
    <!-- partial:partials/_navbar.html -->

    {{-- @include('layout.nav_superior') --}}
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper ">
      
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="maestriaForm" action="{{ route('caii.update', $caii_datos->id) }}" method="post" enctype="multipart/form-data" autocomplete="on">
                  {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                  <div class="row ">

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        {{-- <img src="https://enc-ticketing.org/tktv1/caii/Validacion_Datos2_files/Header.jpg" alt="encabezado caii 2018" class="card-img-top"> --}}
                        <img src="{{ asset('images/banner_form.jpg') }}" alt="encabezado caii 2018" class="card-img-top">
                        
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Datos Personales / OOOOOOO</h4>

                          <div class="row">
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="nombres">Nombres <span class="text-danger">*</span> {{ $caii_datos->id }}</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"  placeholder="Nombres" required="" value="{{ $caii_datos->name }}" {{-- style="border-bottom: 2px solid;" --}}>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ap_paterno" name="ap_paterno"  placeholder="Apellido Paterno" required="" value="{{ old('ap_paterno', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ap_materno" name="ap_materno"  placeholder="Apellido Materno" required="" value="{{ old('ap_materno', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="dni_doc">DNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="dni_doc" name="dni_doc"  placeholder="DNI" value="{{ old('dni_doc', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="telefono">Número Fijo</label>
                                <input type="text" class="form-control" id="telefono" name="telefono"  placeholder="Número Fijo" value="{{ old('telefono', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="celular">Número Celular</label>
                                <input type="text" class="form-control" id="celular" name="celular"  placeholder="Número Celular" value="{{ old('celular', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email">Correo electrónico personal</label>
                                <input type="text" class="form-control" id="email" name="email"  placeholder="Correo electrónico personal" value="{{ old('email', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="profesion">Profesión / Ocupación</label>
                                <input type="text" class="form-control" id="profesion" name="profesion"  placeholder="Profesión" value="{{ old('profesion', 'AAAA') }}">
                              </div>
                            </div>
                          
                          </div> {{-- end row --}}
                        </div>
                      </div>
                    </div>


                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          
                          <h4 class="card-title">Datos Laborales</h4>

                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="centro_laboral">Centro de labores <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="centro_laboral" name="centro_laboral"  placeholder="Centro de labores" required="" value="{{ old('centro_laboral', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="cargo">Cargo</label>
                                <input type="text" class="form-control" id="cargo" name="cargo"  placeholder="Cargo" value="{{ old('cargo', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="direccion">Dirección laboral</label>
                                <input type="text" class="form-control" id="direccion" name="direccion"  placeholder="Dirección laboral" value="{{ old('direccion', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="nombres">Región</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"  placeholder="Región" value="{{ old('nombres', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="telefono_labor">Teléfono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telefono_labor" name="telefono_labor"  placeholder="Teléfono" required="" value="{{ old('telefono_labor', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group ">
                                <label for="email_labor">Correo electrónico de trabajo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email_labor" name="email_labor"  placeholder="Email" required="" value="{{ old('email_labor', 'AAAA') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked="">
                                    Dejo constancia que los datos registrados son correctos
                                  <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked="">
                                    Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales
                                  <i class="input-helper"></i></label>
                                </div>
                                <div class="col-sm-12 col-md-12 p-4">
                                  <button type="submit" class="btn btn-primary mr-2">Guardar y Continuar</button>
                                </div>
                                
                              </div>

                            </div>
                          </div> {{-- end row --}}


                          



                        </div>
                      </div>
                    </div>




                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      
                    </div>
                    {{-- <div class="col-xs-12 col-md-2">
                        <label for="fecha_req">Fecha Reg:</label>
                    </div>
                    <div class="col-xs-12 col-md-2 text-right">
                        <div class="form-group ">
                            <input type="text" class="form-control" id="fecha_req" name="fecha_req" aria-describedby="" placeholder="" value="{{ old('fecha_req', 'AAAA') }}">
                        </div>
                    </div> --}}
                  </div>


                </form>
                
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



  <script>
    $(document).ready(function(){
      /*var $form_academica=$("#form_academica");
      var $form_experiencia_laboral=$("#form_experiencia_laboral");
      var tmpl=$.templates("#formacionTemplate");*/
      console.log('Paso');

    });
  </script>