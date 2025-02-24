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
                  <form class="forms-sample" id="maestriaForm" action="{{ route('maestria.store') }}" method="post" enctype="multipart/form-data" autocomplete="on">
                    {!! csrf_field() !!}

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
                                      <input type="text" class="form-control" id="fecha_req" name="fecha_req" aria-describedby="" placeholder="" value="{{ old('fecha_req', '28/09/2018') }}">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="numeror">N° de Requerimiento:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="numeror" name="numeror" aria-describedby="" placeholder="" value="{{ old('inputApe_pat', '2018-0001') }}">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="descripcion">Descripción:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="descripcion" name="descripcion" aria-describedby="" placeholder="Descripción" value="{{ old('descripcion') }}">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="uo_solicitante">U.O Solicitante:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="uo_solicitante" name="uo_solicitante" aria-describedby="" placeholder="" value="{{ old('uo_solicitante', 'D404-SUBDIRECCIÓN ADMINISTRATIVA') }} ">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="uo_beneficiada">U.O Beneficiada:</label>
                              </div>
                              <div class="col-xs-12 col-md-10">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="uo_beneficiada" name="uo_beneficiada" aria-describedby="" placeholder="" value="{{ old('uo_beneficiada','D404-SUBDIRECCIÓN ADMINISTRATIVA') }} ">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="tipo">Tipo:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="tipo" name="tipo" aria-describedby="" placeholder="" value="{{ old('tipo','ACTIVIDAD  DE CONTROL') }} ">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="nactividad">N° Acción / Actividad:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="nactividad" name="nactividad" aria-describedby="" placeholder="" value="{{ old('nactividad', '1D4042018003') }} ">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="n_os">N° Orden de Servicio de control:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="n_os" name="n_os" aria-describedby="" placeholder="" value="{{ old('nactividad', '02D4042018004') }} ">
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-md-2">
                                  <label for="tipo_bs">Tipo B/S:</label>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <div class="form-group ">
                                      <select name="tipo_bs" id="tipo_bs" class="form-control" required="">
                                          <option value="">Seleccione</option>
                                          <option value="Bien" {{ old('tipo_bs')=='Bien' ? 'selected':''}}>Bien</option>
                                          <option value="Servicio" {{ old('tipo_bs')=='Servicio' ? 'selected':''}}>Servicio</option>

                                      </select>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-2">
                                  <label for="desc_actividad">Descripción Actividad:</label>
                              </div>
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group ">
                                      <input type="text" class="form-control" id="desc_actividad" name="desc_actividad" aria-describedby="" placeholder="" value="{{ old('desc_actividad', 'TRAMITACIÓN DE CONTRATACIÓN DOCENTE, BIENES Y SERVICIO') }} ">
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-xs-12 col-md-12 pt-4 mt-4">
                                  
                                  <table class="table" id="ma_detalle">
                                    <thead class="thead-dark">
                                      <tr>
                                        {{-- <th scope="col">#</th> --}}
                                        <th scope="col">Itém</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">U.M.</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Especificaciones Técnicas / Término de Referencia</th>
                                        <th cope="col"></th>
                                      </tr>
                                    </thead>
                                    <tbody id="filas_contenedor">
                                      <tr class="reg_ejm">
                                        {{-- <th scope="row" id="item_num" class="item_num">1
                                          <input type="hidden" name="item_num" value="" width="10">
                                        </th> --}}
                                        <td>
                                          <input type="text" name="item[]" placeholder="S351000011" value="{{ old('item', 'S351000011') }}" class="form-control">
                                        </td>
                                        <td>
                                          <input type="text" name="descripcion_de[]" placeholder="Descripción" class="form-control" value="{{ old('descripcion_de') }}">
                                        </td>
                                        <td>
                                          <input type="text" name="um[]" placeholder="SRV" value="{{ old('um','SRV') }}" class="form-control">
                                        </td>
                                        <td>
                                          <input type="numeric" name="cantidad[]" placeholder="1" value="{{ old('cantidad', '1') }}" class="form-control">
                                        </td>
                                        <td>
                                          <textarea type="text" rows="3" name="especificaciones[]" placeholder="Especificaciones" class="form-control" placeholder="Pago por el servicio" >{{ old('especificaciones') }}</textarea>
                                        </td>
                                        <td>
                                          <a href="#" class="btn btn-sm btn-danger btn-deleteReg" >
                                            <span>Quitar</span>
                                          </a>
                                        </td>
                                      </tr>
                                      <template id="reg_datos">
                                        <tr class="reg_ejm">
                                        {{-- <th scope="row" id="item_num" class="item_num">1
                                          <input type="hidden" name="item_num" value="" width="10">
                                        </th> --}}
                                        <td>
                                          <input type="text" name="item[]" placeholder="S351000011" value="{{ old('item', 'S351000011') }}" class="form-control">
                                        </td>
                                        <td>
                                          <input type="text" name="descripcion_de[]" placeholder="Descripción" class="form-control" value="{{ old('descripcion_de') }}">
                                        </td>
                                        <td>
                                          <input type="text" name="um[]" placeholder="SRV" value="{{ old('um','SRV') }}" class="form-control">
                                        </td>
                                        <td>
                                          <input type="numeric" name="cantidad[]" placeholder="1" value="{{ old('cantidad', '1') }}" class="form-control">
                                        </td>
                                        <td>
                                          <textarea type="text" rows="3" name="especificaciones[]" placeholder="Especificaciones" class="form-control" placeholder="Pago por el servicio" >{{ old('especificaciones') }}</textarea>
                                        </td>
                                        <td>
                                          <a href="#" class="btn btn-sm btn-danger btn-deleteReg" >
                                            <span>Quitar</span>
                                          </a>
                                        </td>
                                      </tr>
                                      </template>
                                      
                                    </tbody>
                                  </table>

                                  <p class="pl-2">
                                    <a href="#" class="btn btn-primary" id="add_row" >
                                      <span>Agregar</span>
                                    </a>
                                  </p>

                                  
                              </div>
                          </div>

                          <div class="row pt-5 mt-5 text-center ">
                              <div class="col-xs-12 col-md-3 mx-auto">
                                  <input type="text" name="solicitante" class="form-control text-center" style="border-top: 2px solid" value="{{ old('solicitante', 'Alzamora Acosta, Victor Manuel') }} ">
                                  <label for="tipo_bs">Solicitante</label>
                              </div>
                              <div class="col-xs-12 col-md-3 mx-auto">
                                  <input type="text" name="gerente" class="form-control text-center" style="border-top: 2px solid" value="{{ old('gerente', 'Horna Gonzales, Felix Esteban') }} ">
                                  <label for="tipo_bs">Gerente de Area</label>
                              </div>
                          </div>
                      
                  </div>{{-- form_requerimiento --}}


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2 d-print-none"><i id="btn_spin2" class="fas fa-redo fa-spinner fa-spin" style="display: none;"></i>Guardar</button>
                        <a href="{{ route('maestria.index')}}" class="btn btn-light d-print-none">Volver al listado</a>
                      </div>

                    </div>

                  </form>
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
