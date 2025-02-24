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
                  
                  <h4 class="card-title text-transform-none">Reporte General de Asistencia por Grupo<a class="btn btn-link" href="{{ URL::previous() }}">Volver al listado</a></h4>

                  <div class="row justify-content-between align-middle px-2 mb-4">
                    {{-- <div class="d-flex">
                      <p>
                        <strong>Total:</strong> {{$total}} participantes tomados su asistencia de ingreso y salida
                      </p>
                    </div> --}}
                    <div class="d-flex text-right">
                      <a href="{{ route('asistencia.exp', ['id'=>0])}}" class="btn btn-small btn-dark d-none d-sm-block"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar Excel</a>
                      <a href="{{ route('asistencia.exp', ['id'=>2])}}" class="btn btn-small btn-dark d-none d-sm-block mx-2"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar Todo</a>
                    </div>
                    <div class="d-flex text-right d-block d-sm-none">
                      <a title="Descargar Excel" href="{{ route('asistencia.exp', ['id'=>0])}}" class="px-1"><i class="mdi mdi-cloud-check text-dark icon-btn"></i></a>
                      <a title="Descargar Excel" href="{{ route('asistencia.exp', ['id'=>2])}}" class="px-1 mx-2"><i class="mdi mdi-cloud-check text-dark icon-btn"></i></a>
                    </div>
                  </div>


                  <!-- lllllllllll -->

                  <div id="containerss">

                    <table class="table table-striped">
                          
                          <tbody>
                            <?php $comp = "";$subtotal=0;
                            $f = 0;
                            $f2 = 0;
                            $ban = 0;

                            ?>
                            @foreach($count_registrados as $iii=>$da)
                                <thead class="thead-dark">
                                  <tr>
                                    <th>{{$da->fecha}} {{$da->tipo==1?' - ENTRADA':($da->tipo==2?' - SALIDA':'')}} </th>
                                    <th class="text-right" style="width: 10%;">{{$da->total}} </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td colspan="2">
                                      <div id="container{{$iii}}"></div>
                                    </td>
                                  </tr>
                                  @foreach($da->data as $v)
                                  <tr>
                                    <td>{{$v->name}}</td>
                                    <td class="text-right" style="width: 10%;">{{$v->total}}
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>

                            @endforeach

                        </table>
                  </div>



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
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script>

@foreach($count_registrados as $iii=>$da)
generaGrafica('container{{$iii}}',@json($da->data))
@endforeach
function generaGrafica(id,data){
  Highcharts.chart(id, {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: ''
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} % <br> Cantidad:{point.y}'
      }
    }
  },
  series: [{
    name: 'Porcentaje',
    colorByPoint: true,
    data: data
  }]
});
}
</script>
@endsection