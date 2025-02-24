(function($) {
  'use strict';
  $.validator.setDefaults({
    submitHandler: function() {
      console.log('paso');
      submit();

      //alert("submitted!");
    }
  });
  $(function() {
    //$("#estudiantesForm :input").attr("disabled", true);
    //$("#estudiantesForm #cboTipDoc").attr("disabled", false);
    // DNI  DEPARTAMENTO PROVINCIA DISTRITO
    $("#cboTipDoc").change(function(){
      //$("#estudiantesForm :input").attr("disabled", false);

      if($("#cboTipDoc").val()==1){
        console.log('dni');
      }
    });
    // PASAPORTE  : CONTINENTE PAIS CIUDAD
    $('#cboDepartamento').change(function (event){
      
      $.get("ubigeo/"+event.target.value+"",function(resp,depa){
        console.log("ID: "+event.target.value);
        if(resp.length>0){
          console.log("valor resp ="+resp.length);

          $('#cboProvincia').empty();
          $("#cboProvincia").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboProvincia").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{
          console.log("0 Registros.");
        }
        
      });
    });

    $('#cboProvincia').change(function (event){
      //console.log("PASO3");
      console.log("ID: "+event.target.value);
      $.get("ubigeo2/"+event.target.value+"",function(resp,depa){
        if(resp.length>0){
          console.log("valor resp ="+resp.length);
          //console.log('aaaa'+event.target.value);
          $('#cboDistrito').empty();
          $("#cboDistrito").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboDistrito").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{console.log("0 Registros.");}
      });
    });

    var $inputdni=$("#inputdni");
    $inputdni.bind("change",function(){
      var pos=this.value;
      $.get("vdni/"+event.target.value+"",function(resp,depa){
        if(resp.length>0){
          console.log("El DNI ya esta registrado.");
          alert("El DNI ya esta registrado.");
          $('#inputdni').val("");
        }/*else{
          console.log("El DNI no esta registrado.");
        }*/
      });

    });

    

    //https://medium.com/@bucalexis/obtener-valor-de-optgroup-en-tag-select-con-laravel-aa20c84bfaf9
    $("#clic").change(function(){
      var selected = $(':selected',this);
      $('#seleccion').val(selected.parent().attr('label'));
        alert($("#seleccion").val());
    });


    // validate the comment form when it is submitted
    $("#commentForm").validate({
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    // validate signup form on keyup and submit
    $("#estudiantesForm").validate({ 
      rules: {
        inputdni: "required",
        //inputNombres: "required",
        inputdni: {
          required: true,
          minlength: 8,
          maxlength: 8
        },
        inputEmail: {
          //required: true,
          email: true
        }
        
      },
      messages: {
        inputdni: "Escribir DNI",
        //inputNombres: "Please enter your lastname",
        inputdni: {
          required: "Escribir DNI",
          minlength: "DNI debe tener 5 digitos",
          maxlength: "DNI debe máximo 8 digitos",
        },
        
        email: "Escribir email valido"
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    // propose username by combining first- and lastname
    $("#username").focus(function() {
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      if (firstname && lastname && !this.value) {
        this.value = firstname + "." + lastname;
      }
    });

    /*var $actionSubmit=$("#actionSubmit");
    $actionSubmit.bind("click",function(e){
      
      console.log('paso');
      return false;
    });*/





    /*
    //code to hide topic selection, disable for demo
    var newsletter = $("#newsletter");
    // newsletter topics are optional, hide at first
    var inital = newsletter.is(":checked");
    var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
    var topicInputs = topics.find("input").attr("disabled", !inital);
    // show when newsletter is checked
    newsletter.on("click", function() {
      topics[this.checked ? "removeClass" : "addClass"]("gray");
      topicInputs.attr("disabled", !this.checked);
    });
    */


  });
})(jQuery);