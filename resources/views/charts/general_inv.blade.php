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
                  <div class="d-flex justify-content-between align-item-center">
                    <h4 class="card-title text-transform-none">Reporte: Invitados <a class="btn btn-link" href="{{ URL::previous() }}">Volver al listado</a></h4>

                    <h4 class="card-title text-transform-none"><strong>Evento:</strong> {{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</h4>
                  </div>

                  <div class="row justify-content-between align-middle px-2 mb-4">
                    <div class="d-flex">
                      <p>
                        <strong>Total:</strong> {{$total}} participantes invitadas
                      </p>
                    </div>
                    <div class="d-flex text-right">
                      <a href="{{ route('reportes.g_exp', ['id'=>1.3])}}" class="btn btn-small btn-dark d-none d-sm-block"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar Excel</a>
                    </div>
                    <div class="d-flex text-right d-block d-sm-none">
                      <a title="Descargar Excel" href="{{ route('reportes.g_exp', ['id'=>1.3])}}" class="px-1"><i class="mdi mdi-cloud-check text-dark icon-btn"></i></a>{{-- 2 --}}
                    </div>
                  </div>

                  <div id="container"></div>

                  <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <a href="{{ URL::previous() }}" class="btn btn-light">Volver al listado</a>
                      </div>
                  </div>  



                </div>
              </div>
            </div>

            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex">
                    <p>
                      <strong>Total:</strong> {{$reports[1]['total']}} participantes
                    </p>
                  </div>
                  <div id="presencial"></div>
                </div>
              </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex">
                    <p>
                      <strong>Total:</strong> {{$reports[2]['total']}} participantes
                    </p>
                  </div>
                  <div id="virtual"></div>
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
@foreach($reports as $r)
Highcharts.chart('{{ $r['name'] }}', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: '{{ $r['title'] }}'
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
    data: @json($r['cantidad'])
  }]
});
@endforeach
console.log(@json($reports[1]));
</script>
@endsection