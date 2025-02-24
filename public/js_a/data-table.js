(function($) {
  'use strict';
  $(function() {
    /*var datatables = $('#order-listing').DataTable({
      
      lengthChange: true,
      "aLengthMenu": [
        [15, 80, 100, -1],
        [15, 80, 100, "Todos"]
      ],
      "iDisplayLength": 15,

      "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrando _MENU_ registros por página",
            //"zeroRecords": "No se ha encontrado ningún registro.",
            //"info": "Mostrando páginas _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "paginate": {
              "previous": "Anterior",
              "next": "Siguiente"
            }
      }
    });*/
    
  });

    // eliminar varios reg.
    $('.btn-delete').click(function() {
        $('#delete_selec').attr('disabled',false);
        $('#delete_bd').attr('disabled',false);
        var id = $(this).data('id');
        console.log('click btn-delete=' +id);
        //$(this).parents('tr').fadeOut(1000);

        if ($('.btn-delete').is(':checked')) {
        }else{
          $('#delete_selec').attr('disabled',true);
          $('#delete_bd').attr('disabled',true);
          console.log('boton desact');
        }
         //row.fadeOut(1000);
    });

    // pop up detalles de cursos llevados x un estudiante
    $('.estudianteHistorial').click(function (event){
      console.log("Historial Estudiante");

      $("#modalHistorial").modal('show');

      var id_est = $(this).data('id');
      var url = "historial/"+id_est+"";

      $.get(url, function (resp,resul){
        //console.log('Ajax '+ url);

        if(resp.length > 0){
          console.log("estudianteHistorial :");
          console.log(resp.length);
          console.log(resp[0].ap_paterno);

          $("#historiaE table tbody").empty();
          
          for(var i=0;i<resp.length;i++){
            
            $("#heTitle").empty().append("Participante: "+resp[0].nombres );

            $("#historiaE table tbody").append("<tr><th scope=row>"+resp[i].id+"</th><td>"+resp[i].tipo+"</td><td>"+resp[i].evento+"</td><td>"+resp[i].inicio+"</td><td>"+resp[i].fin+"</td><td>"+resp[i].estado+"</td></tr");
          }

        }else{
           $("#heTitle").empty().append("Historial Estudiante: ");

          $("#historiaE table tbody").empty();

          $("#historiaE table tbody").append("<tr><th scope=row colspan='5'>No existen registros.</th></tr");
          console.log('No existen registros.');

        }

      });

    });

    // Asignar código de programación al estudiante:
    $('.asignarProg').click(function (event){
      $("#modalCodProg").modal('show');

      var id_est = $(this).data('id');
      var url = "det_programacion2/"+id_est+"";      

      $.get(url, function (resp,resul){
        console.log('Ajax '+ url);
        var chek = "";
        var dni_check ="";
        var chek_html = "";
        var xcodprog = 0;
        var posicion = 0;
        //console.log('codigos '+resp.codigos.length); //codigo_check
        //console.log('codigo_check '+resp.codigo_check.length); //codigo_check
        //console.log('HTML '+resp.html.length); //codigo_check

        if(resp.codigos.length > 0){

          $("#detProgramacion table tbody").empty();
          
          for(var i=0;i<resp.codigos.length;i++){
            chek = "";
            var pdf="";

            for(var j=0; j < resp.codigo_check.length;j++ ){
              
              if( resp.codigos[i].codigo == resp.codigo_check[j].programacion_id){
                posicion = i;
                if( resp.codigos[posicion] ){ chek ="checked = checked"; }

              }
              var chek_html = 0;

              for( var k=0; k<resp.html.length; k++){
                
                if( resp.codigo_check[j].programacion_id == resp.html[k].lista && resp.codigos[i].codigo == resp.html[k].lista){
                  chek_html = 1;
                  xcodprog = resp.html[k].lista;
                  //console.log('check html: '+'i='+i+ ' j='+j + ' k==> '+k); console.log('chek_html=1 ===== '+chek_html);
                }

              }

              if(chek_html == 1){
                pdf = "<a href='storage/confirmacion/"+xcodprog+'-'+id_est+".pdf' title='Descargar' target='_blank'><i class='mdi mdi-file-pdf icon-md text-danger'></i></a>";
                //console.log('Bandera html SI - '+'i='+i+ ' j='+j + ' k==> '+k)
              }

            }
            
              //$("#heTitle").empty().append("Historial Estudiante: "+resp[i].nombres +' '+resp[i].ap_paterno);
              $("#detProgramacion table tbody").append("<tr><th scope=row>"+
                "<input type=hidden value="+id_est+" name=id_dni>"+
                "<input "+chek+" type='checkbox' class=codpro value='" +resp.codigos[i].codigo+"' class='optPermiso opc1' name='detprog_"+i+"' num='60'><label for=''>&nbsp;"
                +resp.codigos[i].codigo +"</label></th><td>"
                +resp.codigos[i].nombre+"</td>"+
                "<td>"+pdf+"</td><td>"
                +resp.codigos[i].fecha_desde+"</td><td>"
                +resp.codigos[i].fecha_hasta+"</td></tr");
          }
          
            $("#totalRows").val(resp.codigos.length);

        }else{
          $("#detProgramacion table tbody").empty();

          $("#detProgramacion table tbody").append("<tr><th scope=row colspan='5'>No existen registros.</th></tr");
          console.log('No existen registros.');

        }

      });

    });

    // Recorremos todos los checkbox para contar los que estan seleccionados
    /*var contador = 0;
    $("input[type=checkbox].codpro").each(function(j, el){
      if($(this).is(":checked")){
        contador++;
        $("#totalRows").val(contador);
      }
    });*/

    // Ajax asignar programaciones a Estudiantes
    $('form#detalleProgramacion').submit( function( event ) {
        event.preventDefault();  
      }).validate({
        // Rules for form validation
        errorClass: 'error',
          submitHandler: function(form) {
            var actionform = $("#detalleProgramacion").attr('action');
            $("#enviar_det_programacion").attr("disabled","disabled");
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
                   $("#enviar_det_programacion").removeAttr("disabled");
                   //Alert("ok");
                   //swal({ type:'success',title:'Actualización correcta',showConfirmButton: false,timer: 1500});
                  swal({
                    type: 'success',
                    title: 'Éxito...',
                    text: 'Se grabarón los cambios.',
                    //footer: '<a href>Why do I have this issue?</a>'
                  });
                },
                error: function(xhr, status, error){
                var err = JSON.parse(xhr.responseText);
                      alert("error, intente mas tarde");
                      e.preventDefault();         
                }
            });
          },
          errorPlacement : function(error, element) {
          error.insertAfter(element.parent());
        }
    });

    $('#add_row').on('click', addRows);
    $('#add_row2').on('click', addRows2);
    $('#add_row3').on('click', addRows3);
    $('#add_row4').on('click', addRows4);
    $('#add_row5').on('click', addRows5);
    //$('#add_row5').on('click', {a:1,b:2}, addRows5);// como paso argumentos
    $(document).on('click', '.btn-deleteReg', removeElement);
    $(document).on('click', '.btn-deleteReg-cursos', removeCursos);
    let conta = 0;


    function addRows(){
      event.preventDefault();
      console.log('click');
      var fila = $("#template_datos1").html();
      $("#filas_contenedor_datos1").append(fila);
    }

    function removeElement(){
      event.preventDefault();
      $(this).parent().parent().parent().remove();
      //$(this).closest("tr").remove();
      console.log('Eliminado');
    }

    function removeCursos(){
      event.preventDefault();
      $(this).parent().parent().remove();
      //$(this).closest("tr").remove();
      conta--;
      if(conta <= 2){
        $('#add_row5').addClass('d-block-inline').removeClass('d-none');
      }
    }

    function addRows2(){
      event.preventDefault();
      var fila = $("#template_datos2").html();
      $("#filas_contenedor_datos2").append(fila);
    }
    function addRows3(){
      event.preventDefault();
      var fila = $("#template_datos3").html();
      $("#filas_contenedor_datos3").append(fila);
    }
    function addRows4(){
      event.preventDefault();
      var fila = $("#template_datos4").html();
      $("#filas_contenedor_datos4").append(fila);
    }

    
    function addRows5(){//e
      event.preventDefault();
      /*var arg1 = e.data.a;
      var arg2 = e.data.b;
      console.log(`a: ${arg1} - ${arg2}`);*/
      conta++;
      //$("#add_row5").css("display",$(".hijo_cursos").length<3?"":"none");

      if(conta >= 2){
        $('#add_row5').addClass('d-none');
      }
      var fila = $("#template_datos5").html();
      $("#filas_contenedor_datos5").append(fila);

    }


    // AGREGAR BOTÓN AGREGAR Y QUITAR



    // end asig prog a estudiantes

})(jQuery);


