$('form#programacionesImportSave').submit( function( e ) {
        $("#btnSumImport_programaciones").attr("disabled","disabled");
        var _numC = $("#totCol").val();
        var x;
        var _flag=0;
        var _flagDni=0;
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() > 0 ){
            _flag = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 1 ){
            _flagDni = 1;break;
          }
        }

        if(_flag==0){
            //alert("Por favor asignar al menos una columna");
            swal({ type:'info',title:'Por favor asignar al menos una columna',showConfirmButton: false,timer: 1500});
            $("#btnSumImport_programaciones").removeAttr("disabled");
            return false;
        }

        if(_flagDni==0){
            //alert("El DNI es un campo obligatorio");
            swal({ type:'info',title:'El CODIGO es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport_programaciones").removeAttr("disabled");
            return false;
        }
        $("#cargador_excel2").attr("style","display: block; position: -webkit-sticky;position: sticky;left: 0;");
        e.preventDefault();
        var actionform = $(this).attr('action');
     // $("#btnGuardar").attr("disabled","disabled");
        $.ajax({
            url: actionform,
            type:'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){
                //console.log(respuesta);
                $("#cargador_excel2").attr("style","display: none");
                //alert("DATOS CARGADOS");
                //swal('Datos cargados');
                swal({ type:'success',title:'Datos cargados',showConfirmButton: false,timer: 1500});

                $("#btnSumImport_programaciones").removeAttr("disabled");
                $("#iframePrev").attr("style","display: block;");
                $("#programacionesImportSave").attr("style","display: none");
                $("#btnSumImport_programaciones").attr("style","display: none");
                $("#iframePrev").attr("style","display: none ");
                //sleep(500);
                document.getElementById("iframePrev").contentDocument.location.reload(true);
                $("#iframePrev").attr("style","display: block;border: 1px solid #e6e6e6;");
                
                //$('#order-listing').DataTable().ajax.reload();

                e.preventDefault();
            },
            error: function(xhr, status, error){
              $("#cargador_excel2").attr("style","display: none");
              var err = JSON.parse(xhr.responseText);
              console.log('Error');
            }
        });
    });


      $("#btnCerrarIf_programaciones").click(function(){
        location.href="/programaciones";
      })

      $("#btnRegresar").click(function(){
        _flagMod = 1;
        eximForm();
      })

      $('#Modal_organizar_programaciones').on('hidden.bs.modal', function () {
        if(_flagMod == 0){
          location.href="/programaciones"; 
        }

      });

     $("#archivo").change(function (){
        _flagMod = 0;
        $("#f_cargar_datos_programaciones").submit();
     });

    $('form#f_cargar_datos_programaciones').submit( function( event ) {

        $("#btnImport1").attr("disabled","disabled");
        $("#cargador_excel").attr("style","display:block");      
        event.preventDefault();        
    }).validate({
      // Rules for form validation
      errorClass: 'error', 
        rules : {
          archivo: {
            required: true,
            extension: "xls|csv"
          }
        },
        // Messages for form validation
        messages : {
          archivo : {
            required: "Solo se aceptan archivos XLS y CSV"
          }

        },
        submitHandler: function(form) {

          $("#iframePrev").attr("style","display: none ");
          $("#programacionesImportSave").attr("style","display: block");
          $("#btnSumImport_programaciones").attr("style","display: block");
                
         // $("#btnImport1").removeAttr("disabled");

          var actionform = $("#f_cargar_datos_programaciones").attr('action');
          //$("#btnGuardar").attr("disabled","disabled");

          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(datos){
                //console.log(datos);
                $("#hdnTabla").val(datos);
                $("#btnImport1").removeAttr("disabled");    
                $("#cargador_excel").attr("style","display:none");

                $('#Modal_estudiantes').modal('hide');
                $('#tbl_estudiantes_imp_ord').html("");
                var _combo = "<select class='form-control border-primary text-uppercase' required onchange='cmbOrganiza(this)' id='cambiar' name='cambiar' style='width:200px'>";
                _combo = _combo + "<option value=0></option>";

                _combo = _combo + "<option value=1>codigo</option>";
                _combo = _combo + "<option value=2>nombre programa</option>";
                _combo = _combo + "<option value=3>tipo</option>";
                _combo = _combo + "<option value=4>modalidad</option>";
                _combo = _combo + "<option value=5>nombre_curso</option>";
                _combo = _combo + "<option value=6>area_tematica</option>";
                _combo = _combo + "<option value=7>docente</option>";
                _combo = _combo + "<option value=8>aula</option>";
                _combo = _combo + "<option value=9>nsesiones</option>";
                _combo = _combo + "<option value=10>fecha_desde</option>";
                _combo = _combo + "<option value=11>fecha_hasta</option>";
                _combo = _combo + "<option value=12>horario</option>";
                /*_combo = _combo + "<option value=11>email</option>";
                _combo = _combo + "<option value=12>sexo</option>";
                _combo = _combo + "<option value=13>Entidad</option>";*/
                _combo = _combo + "</select>";

                $("#Modal_organizar_programaciones").modal('show').addClass('modal-big');

                if(datos.length>0){
                  var _strTable = '<thead><tr role="row">';
                  var _numCol = datos[0].length ;
                  /*if(_numCol>12){
                    alert("El número máximo de columnas es 12, por favor revise su archivo de Excel");
                    return false;
                  }*/
                  $("#totCol").val(_numCol);
                  var x;
                  var xx = 1;
                  for(x = 0; x < _numCol ; x++){
                    var _nnombre = "cmbOrganizar"+ xx;
                    var _num = xx;
                    
                    _combo = _combo.replace("cambiar", _nnombre );
                    _combo = _combo.replace("cambiar", _nnombre );

                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );
                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );

                    _strTable = _strTable + '<th >' + _combo + '</th>';
                    xx++;
                  }
                  _strTable = _strTable + '</tr></thead>';
                  
                  var y;
                  _strTable = _strTable + '<tbody>';
                  for(y=0; y< datos.length; y++){
                    _strTable = _strTable + '<tr>';
                    var z;  
                    for(z=0; z< _numCol; z++){
                      var _clase = "col" + (z+1);
                      _strTable = _strTable + '<td style="background:#E5E5E5;" class="'+_clase+'">' + datos[y][z] + '</td>' ;
                    }
                    _strTable = _strTable + '</tr>';
                  }
                  _strTable = _strTable + '</tbody>';
                  $('#tbl_estudiantes_imp_ord').append(_strTable);

                }
                organizaFirstRow();
                  
              },
              error: function(xhr, status, error){ 
                    var err = JSON.parse(xhr.responseText);
                    //alert(err["error"]);
                    swal(err["error"]);
                    $("#btnImport1").removeAttr("disabled");    
                    $("#cargador_excel").attr("style","display:none");                        

              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });