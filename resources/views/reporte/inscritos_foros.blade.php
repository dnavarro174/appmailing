@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

   
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel mx-auto">
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title text-transform-none">Reportes de participantes inscritos por actividad <a href="{{route('estudiantes.index')}}" class="btn btn-link">Volver Participantes</a></h4>

              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif

              <p>
                Total de inscritos: {{ $total }}
              </p>

              
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">            

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="order-listing2" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead>
                            <tr role="row">
                              <th class="sorting" >DNI</th>
                              <th class="sorting" style="">NOMBRES</th>
                              <th class="sorting" style="">EMAIL</th>
                              <th class="sorting" style="">TELEFONO</th>
                              <th class="sorting" style="">CELULAR</th>
                              <th class="sorting" style="">CARGO</th>
                              <th class="sorting" style="">ORGANIZACION</th>
                              <th class="sorting" style="">GRUPO</th>
                              <th class="sorting" style="">PAÍS</th>
                              <th class="sorting" style="">DEPARTAMENTO</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            <?php
                            
                            $comp = "";
                            foreach ($inscritosf_datos as $datos) {

                            ?>
                              <?php
                              if($comp == $datos->titulo){ ?>

                            <tr role="row" class="odd">
                                <td class="scope">{{ $datos->dni_doc or ''}}</td>
                                <td class="scope">{{ $datos->nombres or '' }}</td>
                                <td class="scope">{{ $datos->email or '' }}</td>
                                <td class="scope">{{ $datos->telefono or '' }}</td>
                                <td class="scope">{{ $datos->celular or '' }}</td>
                                <td class="scope">{{ $datos->cargo or '' }}</td>
                                <td class="scope">{{ $datos->organizacion or '' }}</td>
                                <td class="scope">{{ $datos->grupo, '' }}</td>
                                <td class="scope">{{ $datos->pais, '' }}</td>
                                <td class="scope">{{ $datos->region, '' }}</td>
                              </tr>

                              <?php }else{ ?>

                               
                              <tr role="row" class="odd">
                                <td colspan="10" class="bg_gris"> <strong>Actividad {{ $datos->id }}: {{ $datos->titulo }} - <span class="subtitle">Registrados: {{ $datos->cantidad }}</span></strong></td>
                              </tr>
                              <tr role="row" class="odd">
                                <td class="scope">{{ $datos->dni_doc or ''}}</td>
                                <td class="scope">{{ $datos->nombres or '' }}</td>
                                <td class="scope">{{ $datos->email or '' }}</td>
                                <td class="scope">{{ $datos->telefono or '' }}</td>
                                <td class="scope">{{ $datos->celular or '' }}</td>
                                <td class="scope">{{ $datos->cargo or '' }}</td>
                                <td class="scope">{{ $datos->organizacion or '' }}</td>
                                <td class="scope">{{ $datos->grupo, '' }}</td>
                                <td class="scope">{{ $datos->pais, '' }}</td>
                                <td class="scope">{{ $datos->region, '' }}</td>
                              </tr>


                              <?php
                                $comp = $datos->titulo;
                              }

                              ?>
                                {{-- <td>{{ $datos->titulo }}</td>
                                <td>{{ $datos->nombres or '' }}</td> --}}
                                
                            </tr>
                            <?php
                              
                            }
                            ?>
                          </tbody>

                          


                        </table>
                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

                </div>
              </div> {{-- end cap_form_list --}}

              
            </div>
          </div>
        </div> <!-- end listado table -->

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

  {{-- form importar --}}


