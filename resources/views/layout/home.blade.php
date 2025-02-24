<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta abc name="viewport" content="width=device-width, initial-scale=1">
  <title>
    @hasSection('title')
      @yield('title')
    @else
      {{config('app.name')}}
    @endif
  </title>

  <link rel="stylesheet" href="{{ asset('iconfonts/mdi/css/materialdesignicons.min.css?v=1.4.1')}}">
  {{-- <link rel="stylesheet" href="{{ asset('iconfonts/puse-icons-feather/feather.css?v=1.4.1')}}"> --}}
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.base.css?v=1.4.1')}}">
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.addons.css?v=1.4.1')}}">
  <link rel="stylesheet" href="{{ asset('css/style.css?v=1.4.1')}}">
  <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
  <link rel="stylesheet" href="{{ asset('css/timepicker.min.css?v=1.4.1')}}">
  <style>
    .sidebar .nav .nav-item.active > a.active{color: #fff;text-decoration: none;}
    a.active{color:red;text-decoration: underline;}
    .error{color: red;font-size: 12px;}
  </style>
  @stack('css')
  @stack('js')

</head>
<body class="horizontal-menu-2">
  
@yield('content')

<div style="display: none;" id="cargador_empresa" class="content-wrapper pt-0" align="center">
  <div class="card">
    <div class="card-body">
      <label style="color:#FFF;background-color:#ABB6BA; text-align:center;display: inline-block;">&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
      <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Realizando tarea solicitada ...</label><br><hr style="color:#003" width="50%">
    </div>
  </div>
</div>
  <script src="{{ asset('js_a/vendor.bundle.base.js?v=1.4.1')}}"></script>  
  <script src="{{ asset('js_a/vendor.bundle.addons.js?v=1.4.1')}}"></script>

  <script src="{{ asset('js_a/off-canvas.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/hoverable-collapse.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/misc.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/settings.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/todolist.js?v=1.4.1')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js_a/dashboard.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/horizontal-menu.js') }}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js_a/formpickers.js?v=1.4.1')}}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js_a/data-table.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/funciones.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/form-validation.js?v=1.7')}}"></script>

  {{-- ocultar por mientras --}}
  <script src="{{ asset('js_a/timepicker.min.js?v=1.4.1')}}"></script>
  <script src="{{ asset('js_a/actividades.js?v=1.4.1')}}"></script>

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js?v=1.4.1"></script>
  
  <script src="{{ asset('js_a/toastDemo.js?v=1.4.1')}}"></script>
  {{-- <script src="js_a/sweetalert.min.js?v=1.4.1"></script> --}}
  <script>
    function baseURL(url){
      return '{{url('')}}/'+url;
    }
  </script>
  @yield('scripts')
  @include('sweetalert::alert')

  <!-- Desarrollado por: DNM -->

</body>
</html>