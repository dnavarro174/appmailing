(function($) {
		////////////////////FORMULARIO
		$('form#f_actividad').submit(function (e) {
		    e.preventDefault();
			e.stopImmediatePropagation();
			console.log('form activides');

			var actionformPar = $("#f_actividad").attr('action');
			//$("#saveActividades").attr("disabled","disabled");
			$.ajax({
		    	url: actionformPar,
		    	type:'POST',
		    	data: new FormData(this),
		    	processData: false,
		    	contentType: false,
		        beforeSend: function(){
		            //toastr.warning('Procesando su solicitud');
		        },
		    	success: function(res){
	                swal({
	                  type: 'success',
	                  title: 'Éxito...',
	                  text: 'Registro Grabado!',
					  icon: "success",
	                })
	                .then((value) => {
	                    //location.href= "{{ route('usuarios.index')}}"
	                    console.log('Grabado: ');
	                    console.log(res.ok);
	                    if(res.ok=="exito"){
	                    	$('#Modal_add_actividad').modal('hide');
	                    	$('#docentes').val(res.docente);
	                    	$('#dni_doc').val(res.dni_doc);
	                    	console.log('Grabado: '+res.dni_doc +"--"+res.docente);

	                    }else if(res!=0){
	                    	$('#Modal_add_actividad').modal('hide');	
	                    	location.reload();
	                    }else{
	                    	$("#f_actividad")[0].reset();
	                    }
	                    
	                });
		    	},
		    	error: function(xhr, status, error){
		    		$("#saveActividades").removeAttr("disabled");
		  			var err = JSON.parse(xhr.responseText);
		  			var tipo = err.tipo;
		  			alert(err.error);

					$("#btnGen").removeAttr("disabled");
					return false;
					
			    }
			});
		});

})(jQuery);

$( document ).ready(function() {
  $('.timepicker1').timepicker();
  $('.timepicker2').timepicker();
});