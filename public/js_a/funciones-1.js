function buscarusuario(){
  var pais=$("#select_filtro_pais").val();
  var dato=$("#s").val();
      if(dato == "")
        {
          var url="buscar_usuarios/"+pais+"";
        }
        else
        {
          var url="buscar_usuarios/"+pais+"/"+dato+"";
        }

      console.log('url: '+url);
        $(".main-panel").html($("#cargador_empresa").html());
        $.get(url,function(resp, resul){
          $('.main-panel').empty();
          if(resp.length>0){
            console.log("valor resul ="+resp.length);
            $(".main-panel").html(resp); 
          
          }else{
          	console.log("0 Registros.");
          }
        });
  }

function formActividad(evento_id,dia, actividad_id, num , url){ 
      
      event.preventDefault(); 
      event.stopImmediatePropagation();
      $("#Modal_add_actividad").modal('show');

    if(num == "docente"){
      var url = url+"/addDocentes/"+evento_id+"/"+dia+"/"+ actividad_id + "/" + num;
    }else{
      var url = url+"/eventos_form_actividad/"+evento_id+"/"+dia+"/"+ actividad_id + "/" + num;
    }
      
      $.get(url, function (resp,resul){

        $(".form-act").html(resp);
        $('.timepicker1').timepicker();
        $('.timepicker2').timepicker();  
      
        $('.file-upload-browse').on('click', function() { 
          var file = $(this).parent().parent().parent().find('.file-upload-default');
          file.trigger('click');
        });
        $('.file-upload-default').on('change', function() {
          $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });
      });

   
}
  //------------------------ FORM IMPORT -------------------------//


  function eximForm(){
    $("#Modal_estudiantes").modal('show');
    $("#Modal_estudiantes form")[0].reset();
  }
  function cerrar_form(){
    $("#Modal_organizar").modal('hide');
    $("#Modal_organizar_cursos").modal('hide');
    $("#Modal_organizar_programaciones").modal('hide');
  }
  function openModal(){
    //$("#modalHistorial").modal('show');
    //.modal('hide');
  }


  function cmbOrganiza(obj){
    var _num = parseInt($(obj).attr("id").replace("cmbOrganizar",""));
    var coll = $(".col" + _num);

    if($(obj).val()>0){
      //VERIFICAR QUE NINGUNA COLUMNA ESTE SELECCIONADA
      var _valorS = $(obj).val(); 
      var _totC = $("#totCol").val();
      var h;
      for(h=1; h <= _totC ;h++){
        if(h != _num){
          if( $("#cmbOrganizar" + h).val() == _valorS){
              $("#cmbOrganizar" + h).val(0) ;
              var coll2 = $(".col" + h);
              coll2.each(function() {
                  $(this).attr("style","background:#E5E5E5");
              });  
          }
        }
      }

      coll.each(function() {
          $(this).attr("style","background:#FFFFFF");
      });      
    }else{
      coll.each(function() {
          $(this).attr("style","background:#E5E5E5");
      });  
    }
    organizaFirstRow();
  }

function organizaFirstRow(){
  //$("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#E5E5E5");
  if ($('#chkPrimeraFila').is(':checked')) {
    $("#tbl_estudiantes_imp_ord tbody tr:first").attr("style", "color:#B5B5B5;");
    $("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#E5E5E5");
  }else{
    $("#tbl_estudiantes_imp_ord tbody tr:first").attr("style", "color:#000000;");
    //$("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#FFFFFF");
    var _totC = $("#totCol").val();
    var h;
    for(h=1; h <= _totC ;h++){
      if( $("#cmbOrganizar" + h).val() != 0){
        $("#tbl_estudiantes_imp_ord tbody tr:first .col" + h).attr("style", "background:#FFFFFF");      
      }
    }
  } 
}

function eximFormOrganizar(data){
  $("#Modal_organizar").modal('show').addClass('modal-big');
  //$("#Modal_estudiantes form")[0].reset();
}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}

function validEmailArroba($email){
  
  if( !validateEmail($email)  ) {
    $('#salida').html('').addClass('d-none').removeClass('d-block');
    $('#actionSubmit').attr('disabled', false);
  }else{
    $('#salida').html('Lo sentimos, solo se permite el nombre de usuario, sin @dominio.com').addClass('d-block').removeClass('d-none');
    $('#actionSubmit').attr('disabled', true);
  }
  if($email.indexOf("@") > -1 || $email.indexOf(";") > -1 || $email.indexOf(",") > -1){
    $('#salida').html('Lo sentimos, solo se permite el nombre de usuario, sin @dominio.com').addClass('d-block');
    $('#actionSubmit').attr('disabled', true);
    //console.log('indexOf @');
  }
}

//------------------------ BEGIN DOC READY -------------------------//

$( document ).ready(function() {

/*
  $(document).on('click','.usRoles', function(e){
    e.stopPropagation();
    e.stopImmediatePropagation();   
    var _href =  $(this).attr("href") ;
    alert(_href);
    //window.open("reportes-pensiones-pdf/" + id   ); return false;   
   }
  );  
*/
//ocultar busqueda avanzada
  	$('#bAdvance').click(function(){
      $('#capBusqueda').css('display','block');
      $('.ocultar').css('display','block');
      $('.mostrar').css('display','none');
    });
    $('#bAdvanceOcultar').click(function(){
      $('#capBusqueda').css('display','none');
      $('.ocultar').css('display','none');
      $('.mostrar').css('display','block');
    });  


    
    // eliminar varios reg.
    $('#delete_selec').click(function() {
      //console.log('¿Desea borrar los registros seleccionados?');
      //return false;
       if (!confirm('¿Desea borrar los registros seleccionados?'))
        {
            return false;
        }
        $('#form-delete').submit();
    });

    // eliminar definitivamente.
    $('#delete_bd').click(function() {
      console.log('¿Desea borrar');
      //return false;
       if (!confirm('¿Desea borrar los registros seleccionados? Se borrará de todo el sistema'))
        {
            return false;
        }
        $('#form-delete').submit();
    });

    // seleccionar todos
    $('#chooseAll_1').change(function() {
      if ($('#chooseAll_1').is(':checked')) {
        $('#delete_selec').attr('disabled',false);
        $('#delete_bd').attr('disabled',false);
        console.log('boton act');
      }else{
        $('#delete_selec').attr('disabled',true);
        $('#delete_bd').attr('disabled',true);
        console.log('boton desact'); 
      }

      $("input:checkbox").prop('checked', $(this).prop("checked"));

    });

    


    //------------------------ IMPORT ESTUDIANTES-------------------------//

    

    $("#btnSumImport_cursos").click(
      function(){
        $("#cursosImportSave").submit();
      }
    );
   $("#btnSumImport_programaciones").click(
      function(){
        $("#programacionesImportSave").submit();
      }
    );

    //------------------------ SAVE ESTUDIANTES-------------------------//

  // .on para ejc: múltiples eventos
  $('#email').on('keyup change', function(e){
    let em = $('#email').val();
    validEmailArroba(em);
    //console.log(em);
  });

});