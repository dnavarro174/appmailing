
$( document ).ready(function() {

	__docAutocomplete();

function __docAutocomplete(){

	var docentes = $('#docentes');
	var dni_doc = $('#dni_doc');
	var lugar = $('#lugar');

  var options = {
	url: function(q) {
			/*if(q.length >= 2){
				$('#docentes').val('');
				$('#dni_doc').val('');
			}*/

		return baseURL('autocomplete/findDoc?q='+q);
		},

		getValue: "nombre_c",
		list: 
			{
			maxNumberOfElements: 10,
			onClickEvent: function() {
				var e = $('#docentes').getSelectedItemData();
				//docentes.val(e.docentes);
				dni_doc.val(e.dni_doc);
				lugar.focus();
			},

			onKeyEnterEvent: function() {
				var e = $('#docentes').getSelectedItemData();
				console.log(e);
				//docentes.val(e.docentes);
				dni_doc.val(e.dni_doc);
			},
			onSelectItemEvent:  function() {
				var e = $('#docentes').getSelectedItemData();
				console.log(e);
				//docentes.val(e.docentes);
				dni_doc.val(e.dni_doc);
			}
		}
	};

  	$("#docentes").easyAutocomplete(options);
}

	$("#costo_mn").on("input", function(e) {
		e.preventDefault();
		let s = $(this);
		let monto = s.val();
		let cant  = $("#cant").val();
		if(monto == 0 || monto == ""){
			alert('Ingrese monto');
		}
		console.log(cant);
	});

});