  $("#estudiantesImportSave").change(function(){
 
    var texto = "Opciones Seleccionadas: ";
    var i=1;
    $("#estudiantesImportSave option:selected").each(function() {     
      texto += $(this).text() + " - ";
      i++;
    });
 
    $("#resultado").text(texto);
    console.log('i: ' + i + ' - Prueba :');
  }).trigger('change');d