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
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title"><strong>{{$rol->rol}}</strong>, Permisos</h4>
                

              <form id="from_permisos" name="from_permisos"  novalidate="novalidate" action="{{ route('roles.storepermisos') }}" >      
                <input type="hidden" name="idRol" id="idRol" value="<?php echo $id;?>">
                <!--<input type="hidden" name="idModulo" id="idModulo" value="">
                <input type="hidden" name="idAccion" id="idAccion" value="">
                <input type="hidden" name="permiso" id="permiso" value="">-->

               {!! csrf_field() !!}

              <div id="accordion">
                <?php
                  $flag1= 1;
                 $cont = 1;
                 foreach ($arr1 as $datos){
                 ?>
                  <h3>{{ $datos["nom_modulo"] }}</h3>

                        <div>
                            <table  class="table">
                            <thead>
                              <tr >
                                <th></th>
                                <th>Permiso</th>
                                <th>Habilitado</th>
                                <th>Denegado</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php  foreach ($datos["acciones"] as $datos2){
                                if($datos2["permiso"]==0){
                                  $flag1 = 0;
                                }
                                ?>
                              <tr role="row" class="odd">
                                <td></td>
                                <td>{{ $datos2["descripcion"] }}
                                  <input type="hidden" name="idModulo_{{$cont}}" id="idModulo_{{$cont}}" value="{{ $datos["id_modulo"] }}" >
                                  <input type="hidden" name="idAccion_{{$cont}}" id="idAccion_{{$cont}}" value="{{ $datos2["idAccion"] }}" >
                                </td>
                                <td><input type="radio" value="1" class="optPermiso opc1" name="permiso_{{$cont}}" num="{{$cont}}" <?php if($datos2["permiso"]==1){echo " checked "; }?> ></td>
                                <td><input type="radio" value="0" class="optPermiso opc2" name="permiso_{{$cont}}" num="{{$cont}}" <?php if($datos2["permiso"]==0){echo " checked "; }?> ></td>
                              </tr>
                              <?php 
                                  $cont++;
                                }
                              ?>
                            </tbody>
                          </table>
                      </div>

                <?php }?>
                   
                    <input type="hidden" name="totalRows" id="totalRows" value="<?= $cont-1;?>">
              </div>
            </form>                 

                    <form id="from_permisosall" name="from_permisosall"  novalidate="novalidate" action="{{ route('roles.storepermisosall') }}" >      
                      {!! csrf_field() !!}
                      <input type="hidden" name="idRol2" id="idRol2" value="<?php echo $id;?>">
                      <input type="hidden" name="hdnConceder" id="hdnConceder" >
                      <div class="row">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label">
                            <?php if($flag1==1){?>
                              <input type="checkbox" id="chkConceder" name="chkConceder" value="1" checked><span id="spanConceder">&nbsp;Quitar todos los permisos</span>
                            <?php }else{?>
                              <input type="checkbox" id="chkConceder" name="chkConceder" value="1" ><span id="spanConceder">&nbsp;Conceder todos los permisos</span>
                              
                            <?php }?>
                          </label>
                        </div>
                      </div>
                    <img src="{{ asset('images/cargando.gif') }}" width="20" height="20" align="middle" id="cargador" style="display: none;">
                    </form>
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="button" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('roles.index')}}" class="btn btn-light">Volver al listado</a>
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
    $('form#from_permisos').submit( function( event ) {
        event.preventDefault();      
      }).validate({
        // Rules for form validation
        errorClass: 'error',
          submitHandler: function(form) {
            var actionform = $("#from_permisos").attr('action');
          $("#actionSubmit").attr("disabled","disabled");
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
                   $("#actionSubmit").removeAttr("disabled");
                   //Alert("ok");
                   //swal({ type:'success',title:'Actualización correcta',showConfirmButton: false,timer: 1500});
                  swal({
                    type: 'success',
                    title: 'Éxito...',
                    text: 'Actualización de permisos correcta!',
                    //footer: '<a href>Why do I have this issue?</a>'
                  });
                },
                error: function(xhr, status, error){
                var err = JSON.parse(xhr.responseText);
                      alert("error, intente mas tarde");
                      /*$("#btnGuardar").removeAttr("disabled");
                      $.bigBox({
                        title : "Error",
                        content : err["error"],
                        color : "#C46A69",
                        icon : "fa fa-warning shake animated",
                        //number : "1",
                        timeout : 6000
                      });     */
                      e.preventDefault();         
                }
            });
          },
          errorPlacement : function(error, element) {
          error.insertAfter(element.parent());
        }
    });

  /*  $(document).on('click','.optPermiso', function(e){

      var num     =   $(this).attr("num") ;
      var _idModulo =   $("#idModulo_" + num).val();
      var _idAccion =   $("#idAccion_" + num).val();
      var p =$("input[name=permiso_"+num+"]:checked").val();

      $("#idModulo").val(_idModulo);
      $("#idAccion").val(_idAccion);
      $("#permiso").val(p);
      
      $("#from_permisos").submit();

    });   */

    $("#actionSubmit").click(
      function (){
        $("#from_permisos").submit();        
      }
    );
    $("#chkConceder").click(
      function(){
        
        if ($('#chkConceder').is(':checked')) {
          $('#spanConceder').html(" Quitar todos los permisos");
          $('#hdnConceder').val("1");
          //$("input[type=radio]").attr("checked","checked");
          //$(":radio[value='1']").attr("checked","checked");
          //$(":radio[value='2']").removeAttr("checked","checked");
          
          $("input:radio.opc1").prop("checked",true);
          $("input:radio.opc2").prop("checked",false);


        }else{          
          $('#hdnConceder').val("0");
          $('#spanConceder').html(" Conceder todos los permisos");
          $("input:radio.opc2").prop("checked",true);
          $("input:radio.opc1").prop("checked",false);
          //$("input:radio.opc1").removeAttr("checked");
          
        }
        $("#from_permisosall").submit();
      }
    );



    $('form#from_permisosall').submit( function( event ) {
        event.preventDefault();        
    }).validate({
    // Rules for form validation
    errorClass: 'error',
        submitHandler: function(form) {
          var actionform = $("#from_permisosall").attr('action');
        //$("#btnGuardar").attr("disabled","disabled");
          $("#chkConceder").attr("disabled","disabled");
          $("input[type=radio]").attr('disabled',"disabled");
          $("#cargador").attr("style","display: block");

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
                var _texto = "";
                if(respuesta=="1"){
                    _texto = "Se CONCEDIERON todos los permisos correctamente!";
                }else if(respuesta=="2"){
                    _texto = "Se QUITARON todos los permisos correctamente!";
                }
                $("#cargador").attr("style","display: none");
                swal({
                  type: 'success',
                  title: 'Éxito...',
                  text: _texto
                  
                });

                $("#chkConceder").removeAttr("disabled","disabled");
                $("input[type=radio]").removeAttr('disabled',"disabled");

              },
              error: function(xhr, status, error){
              var err = JSON.parse(xhr.responseText);
                    alert("error, intente mas tarde");
                    $("#chkConceder").removeAttr("disabled","disabled");
                    $("input[type=radio]").removeAttr('disabled',"disabled");                  
                    e.preventDefault();         
              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });



  });

  $("#accordion" ).accordion({    

    active: false,    
    collapsible: true    ,
    animate:500
 
  });


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

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js?v=1.4"></script>
  
  @include('sweetalert::alert')
  
</body>
</html>