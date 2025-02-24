<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Ticketing V2.0</title>
  <link rel="stylesheet" href="{{ asset('iconfonts/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.addons.css')}}">
  <link rel="stylesheet" href="{{ asset('css/style.css')}}">
  <link rel="stylesheet" href="{{ asset('css/jquery-ui.css')}}">
  <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <style>
.sidebar .nav .nav-item.active > a.active{color:#fff;text-decoration:none}a.active{color:red;text-decoration:underline}.error{color:red;font-size:12px}#accordion .ui-accordion-header{font-size:12px}#accordion .ui-accordion-content{font-size:12px}
</style>  
</head>
<body class="horizontal-menu-2">

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      <!-- end menu_user -->
    
    
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  

                  <h4 class="card-title">Editar Roles de Usuario</h4>
                  
                  

                  <form method="post" class="forms-sample" id="userrolesForm"  action="{{ route('usuarios.storeRoles') }}">
                    {!! method_field('POST') !!}
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{ $usuarios_datos->id}}">

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="usuario">Usuario </label>
                        <input type="text" class="form-control text-uppercase" id="name" name="name" placeholder="Nombre" value="{{ $usuarios_datos->name, old('name') }}" disabled >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_id">Rol</label>
                        <select class="form-control text-" name="cboRol[]" id="cboRol" size=10 multiple="" class="custom-scroll" style='height: 100%;'>
                          <?php foreach ($roles as $rol){?>
                          <option value="{{$rol["id"]}}"  
                            <?php 
                              $cc = 0;
                              foreach($rolesUs as $rUs){
                                if($rUs->role_id == $rol->id){
                                  $cc++;
                                }
                              }
                              if($cc>0){echo " selected ";}
                            ?>
                          >{{$rol["rol"]}}</option>
                          <?php }?>
                        </select>
                      </div>
                      
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
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



<div style="display: none;" id="cargador_empresa" class="content-wrapper pt-0" align="center">
  <div class="card">
    <div class="card-body">
      <label style="color:#FFF;background-color:#ABB6BA; text-align:center;display: inline-block;">&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
      <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Realizando tarea solicitada ...</label><br><hr style="color:#003" width="50%">
    </div>
  </div>
</div>


<!-- plugins:js -->
  <script src="{{ asset('js/jquery.js')}}"></script>
  <script src="{{ asset('js/jquery-ui.js')}}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    $('form#userrolesForm').submit( function( event ) {
        event.preventDefault();        
    }).validate({
    // Rules for form validation
    errorClass: 'error',
        submitHandler: function(form) {
          var actionform = $("#userrolesForm").attr('action');
        $("#btnGuardar").attr("disabled","disabled");
          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(respuesta){
                swal({
                  type: 'success',
                  title: 'Éxito...',
                  text: 'Actualización de roles de usuario correcta!',
                })
                .then((value) => {
                    location.href= "{{ route('usuarios.index')}}"
                });

              },
              error: function(xhr, status, error){
                //console.log(xhr.responseText);

                    var err = xhr.responseJSON.error;
                    //alert("error, intente mas tarde");
                    swal({
                      type: 'success',
                      title: 'ERROR...',
                      text: err
                    });                    
                    //e.preventDefault();         
              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });

    $(document).on('click','.optPermiso', function(e){

      var num     =   $(this).attr("num") ;
      var _idModulo =   $("#idModulo_" + num).val();
      var _idAccion =   $("#idAccion_" + num).val();
      var p =$("input[name=permiso_"+num+"]:checked").val();

      $("#idModulo").val(_idModulo);
      $("#idAccion").val(_idAccion);
      $("#permiso").val(p);
      
      $("#userrolesForm").submit();

    });   
    
  });

  $("#accordion" ).accordion();   


</script>
  <script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>
  <script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('js_a/off-canvas.js')}}"></script>
  <script src="{{ asset('js_a/hoverable-collapse.js')}}"></script>
  <script src="{{ asset('js_a/misc.js')}}"></script>
  <script src="{{ asset('js_a/settings.js')}}"></script>
  <script src="{{ asset('js_a/todolist.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js_a/dashboard.js')}}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js_a/formpickers.js')}}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js_a/data-table.js')}}"></script>
  <script src="{{ asset('js_a/funciones.js')}}"></script>

  <script src="{{ asset('js_a/form-validation.js')}}"></script>

  
  <!-- end footer_js -->

  @include('sweetalert::alert')
  
</body>
</html>