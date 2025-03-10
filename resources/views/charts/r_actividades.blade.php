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
                    <div class="row justify-content-between align-middle px-2 mb-4">
                        <div class="d-flex">
                            <a class="btn btn-link" href="{{ URL::previous() }}">Volver al listado</a>
                        </div>
                        <div class="d-flex text-right">
                            <a href="{{ route('reportes.g_exp', ['id'=>1.5])}}" class="btn btn-small btn-dark d-none d-sm-block"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar Excel</a>
                        </div>
                    </div>

                    @foreach($reports as $r)
                        @if(!$loop->first)
                            <hr class="py-3">
                        @endif
                        @php($total=$r['total'])
                        @php($cant_xgrupo=$r['cantidad'])


                    <h4 class="card-title text-transform-none">{{$r['title']}}<!-- enlace volver --></h4>




                    <div class="row justify-content-between align-middle px-2 mb-4">
                    <div class="d-flex">
                      <p>
                        <strong>Total:</strong> {{$total}} actividades registradas
                      </p>
                    </div>
                    <!-- ANTES BOTON DESCARGA -->
                    <div class="d-flex text-right d-block d-sm-none">
                      <a title="Descargar Excel" href="{{ route('reportes.g_exp', ['id'=>1.5])}}" class="px-1"><i class="mdi mdi-cloud-check text-dark icon-btn"></i></a>
                    </div>
                  </div>

                  <div id="container">
                    <table class="table table-striped">

                          <tbody>
                            <?php $comp = ""; ?>
                            @foreach($cant_xgrupo as $da)
                              @if($comp == $da->fecha_desde)
                                <tr>
                                  <td>{{$da->name}}</td>
                                  <td class="text-center" style="width: 10%;">{{$da->y}}</td>
                                </tr>

                              @else
                                <?php $comp = $da->fecha_desde; ?>
                                <thead class="thead-dark">
                                  <tr>
                                    <th colspan="2">{{\Carbon\Carbon::parse($da->fecha_desde)->format('d/m/Y')}}</th>
                                  </tr>
                                </thead>
                                <tr>
                                  <td>{{$da->name}}</td>
                                  <td class="text-center" style="width: 10%;">{{$da->y}}</td>
                                </tr>

                              @endif
                            @endforeach
                          </tbody>
                        </table>
                  </div>
                  @endforeach


                  <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <a href="{{ URL::previous() }}" class="btn btn-light">Volver al listado</a>
                      </div>
                  </div>

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
@endsection
