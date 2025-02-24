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
                  
                  <h4 class="card-title text-transform-none">Reporte: Registrados al Eventos: {{$evento->nombre_evento}} <a class="btn btn-link" href="{{ URL::previous() }}">Volver al listado</a></h4>

                  <div class="row justify-content-between align-middle px-2 mb-4">
                    <div class="d-flex">
                      <p>
                        <strong>Total:</strong> {{$total}} participantes registrados
                      </p>
                    </div>
                    <div class="d-flex text-right">
                      <a href="{{ route('reportes.g_exp', ['id'=>5])}}" class="btn btn-small btn-dark d-none d-sm-block"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar Excel</a>
                    </div>
                    <div class="d-flex text-right d-block d-sm-none">
                      <a title="Descargar Excel" href="{{ route('reportes.g_exp', ['id'=>5])}}" class="px-1"><i class="mdi mdi-cloud-check text-dark icon-btn"></i></a>
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
Highcharts.chart('container', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Cuántos se han registrado'
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
    data: @json($count_registrados)
    /*[{
      name: 'Chrome',
      y: 61.41,
      sliced: true,
      selected: true
    }, {
      name: 'Internet Explorer',
      y: 11.84
    }, {
      name: 'Firefox',
      y: 10.85
    }, {
      name: 'Edge',
      y: 4.67
    }, {
      name: 'Safari',
      y: 4.18
    }, {
      name: 'Sogou Explorer',
      y: 1.64
    }, {
      name: 'Opera',
      y: 1.6
    }, {
      name: 'QQ',
      y: 1.2
    }, {
      name: 'Other',
      y: 2.61
    }]*/
  }]
});
</script>
@endsection