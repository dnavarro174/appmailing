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
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  {{--<h4 class="card-title">Leads / Registros</h4>
                   <p class="card-description">
                  </p> --}}
                                    

                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>                    
                  @endif

                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{ session('info') }}
                    </div>
                    <a href="{{ route('maestria.index') }}" class="btn btn-success">Volver al listado</a>

                  @endif
                  

                    <div class="col-xs-12 col-md-12 pt-4 form_requerimiento">

                      <h1 class="text-danger text-center">FORMATO DE REQUERIMIENTO</h1>
                      <h2 class="text-dark  text-center mb-4 pb-4">PARA LA MAESTRÍA EN CONTROL GUBERNAMENTAL</h2>

                      
                          <input type="hidden" name="key" value="1">
                          <div class="row">
                              <div class="col-xs-12 col-md-8"></div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="fecha_req">Fecha Reg:</label>
                              </div>
                              <div class="col-xs-12 col-md-2 text-right">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="fecha_req" name="fecha_req" aria-describedby="" placeholder="" value="{{ $maestria_datos->fecha_req }}" disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="numeror">N° de Requerimiento:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="numeror" name="numeror" aria-describedby="" placeholder="" value="{{ $maestria_datos->numeror }}" disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="descripcion">Descripción:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="descripcion" name="descripcion" aria-describedby="" placeholder="Descripción" value="{{ $maestria_datos->descripcion }}" disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="uo_solicitante">U.O Solicitante:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="uo_solicitante" name="uo_solicitante" aria-describedby="" placeholder="" value="{{ $maestria_datos->uo_solicitante }} " disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="uo_beneficiada">U.O Beneficiada:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="uo_beneficiada" name="uo_beneficiada" aria-describedby="" placeholder="" value="{{ $maestria_datos->uo_beneficiada }} " disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="tipo">Tipo:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <textarea rows="2" class="form-control" disabled="">{{ $maestria_datos->tipo }}</textarea>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="nactividad">N° Acción/ Actividad:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="nactividad" name="nactividad" aria-describedby="" placeholder="" value="{{ $maestria_datos->nactividad }} " disabled>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="n_os">N° Orden de Servicio de control:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="n_os" name="n_os" aria-describedby="" placeholder="" value="{{ $maestria_datos->n_os }} " disabled>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="tipo_bs">Tipo B/S:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <select name="tipo_bs" id="tipo_bs" class="form-control" required="" disabled="">
                                          <option value="">Seleccione</option>
                                          {{-- <option value="Bien" {{ old('tipo_bs')=='Bien' ? 'selected':''}}>Bien</option>
                                          <option value="Servicio" {{ old('tipo_bs')=='Servicio' ? 'selected':''}}>Servicio</option> --}}
                                          <option value="Bien"
                                            @if ('Bien' === $maestria_datos->tipo_bs)
                                              selected
                                            @endif
                                          >Bien</option>
                                          <option value="Servicio"
                                            @if ('Servicio' === $maestria_datos->tipo_bs)
                                              selected
                                            @endif>Servicio</option>

                                      </select>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="desc_actividad">Descripción Actividad:</label>
                              </div>
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="desc_actividad" name="desc_actividad" aria-describedby="" placeholder="" value="{{ $maestria_datos->desc_actividad }} " disabled="">
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-xs-12 col-md-12 pt-4 mt-4">
                                  
                                  <table class="table" id="ma_detalle">
                                    <thead class="thead-dark">
                                      <tr>
                                        <th scope="col" style="width: 1% !important;">#</th>
                                        <th scope="col" style="width: 18% !important;">Itém</th>
                                        <th scope="col" style="width: 30% !important;">Descripción</th>
                                        <th scope="col" style="width: 8% !important;">U.M.</th>
                                        <th scope="col" style="width: 5% !important;">Cantidad</th>
                                        <th scope="col" style="width: 40% !important;">Especificaciones Técnicas / Término de Referencia</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <th scope="row" id="item_num" class="item_num">1
                                          <input type="hidden" name="item_num" value="" width="10">
                                        </th>
                                        <td>
                                          <input type="text" name="item" placeholder="" value="{{ $detalle_datos->item }}" disabled class="form-control">
                                        </td>
                                        <td>
                                          {{-- <input type="text" row="2" name="descripcion_de" placeholder="Descripción" class="form-control" value="{{ $detalle_datos->descripcion_de }}" disabled > --}}


                                          <textarea type="text" rows="5" name="descripcion_de" placeholder="" class="form-control" disabled="">{{ $detalle_datos->descripcion_de }}</textarea>
                                        </td>
                                        <td>
                                          <input type="text" name="um" placeholder="SRV" value="{{ $detalle_datos->um }}" disabled  class="form-control">
                                        </td>
                                        <td>
                                          <input type="numeric" name="cantidad" placeholder="1" value="{{ $detalle_datos->cantidad }}" disabled  class="form-control">
                                        </td>
                                        <td>
                                          <textarea type="text" rows="7" name="especificaciones" placeholder="Especificaciones" class="form-control" placeholder="Pago por el servicio" disabled="">{{ $detalle_datos->especificaciones }}</textarea>
                                        </td>
                                      </tr>
                                      
                                    </tbody>
                                  </table>

                                  
                              </div>
                          </div>

                          <div class="row pt-5 mt-5 text-center ">
                              <div class="col-xs-12 col-md-3 mx-auto">
                                  <input type="text" name="solicitante" class="form-control" style="border-top: 2px solid" value="{{ $maestria_datos->solicitante }} " disabled>
                                  <label for="tipo_bs">Solicitante</label>
                              </div>
                              <div class="col-xs-12 col-md-3 mx-auto">
                                  <input type="text" name="gerente" class="form-control" style="border-top: 2px solid" value="{{ $maestria_datos->gerente }} " disabled>
                                  <label for="tipo_bs">Gerente de Area</label>
                              </div>
                          </div>
                      
                  </div>{{-- form_requerimiento --}}


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        
                        <a href="{{ route('maestria.index')}}" class="btn btn-light d-print-none">Volver al listado</a>
                      </div>

                    </div>

                  
                  {{-- @endif --}}
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



  <script>
    $(document).ready(function(){
      /*var $form_academica=$("#form_academica");
      var $form_experiencia_laboral=$("#form_experiencia_laboral");
      var tmpl=$.templates("#formacionTemplate");*/
      console.log('Paso');

    });
  </script>