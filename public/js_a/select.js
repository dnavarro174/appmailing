$('#state').change(function (event){
	console.log("PASO");

	$.get("towns/"+event.target.value+"",function(resp,state){
		console.log(resp);
		$('#town').empty();
		$("#town").append("<option value='0'>SELECCIONE</option>");
		for(i=0;i<resp.length;i++){
			$("#town").append("<option value='"+resp[i].id+"'>"+resp[i].name+"</option>");
		}
	});
});


