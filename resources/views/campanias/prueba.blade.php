<!DOCTYPE html>
<!-- To run the current sample code in your own environment, copy this to an html page. -->

<html>
<head>
  <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="http://www.jsviews.com/download/jsviews.min.js"></script>
  <link href="http://www.jsviews.com/samples/samples.css" rel="stylesheet" />
</head>
<body>

<table><tbody id="result"></tbody></table>

<script id="theTmpl" type="text/x-jsrender">
  <b>[%:title%]</b>
  <ul>
    [%for members%]
      <li>Name: [%:name%]</li>
    [%for%]
  </ul>

  {{/for}}
</script>

<script>
console.log('paso');
$.views.settings.delimiters("[%", "%]");

var template = $.templates("#theTmpl");

var people = [
    {
      name: "Adriana"
    },
    {
      name: "Robert"
    }
  ];

var counter = 1;

template.link("#result", {people: people});

$("#addBtn").on("click", function() {
  $.observable(people).insert({name: "name" + counter++});
})

$("#result")
  .on("click", ".change", function() {
    var dataItem = $.view(this).data;
    $.observable(dataItem).setProperty("name", dataItem.name + "*");
  })
  .on("click", ".remove", function() {
    var index = $.view(this).index;
    $.observable(people).remove(index);
  });
</script>

</body>
</html>


{{-- <script id="formacionTemplate" type="text/x-jsrender">
    <input type="hidden" name="lista[]" value="{{:index}}" />
    <div class="hijo_form_academica">
    
      <div id="" class="q required width-80">
         <label class="question top_question" for="txt_prof_especialidad-{{:index}}"><span class="txtcampo" >Profesión o Especialidad</span>&nbsp;<b class="icon_required c-red">*</b></label>
         <input type="text" name="txt_prof_especialidad[{{:index}}]" class="text_field" id="txt_prof_especialidad-{{:index}}" size="250" maxlength="250" required="required" value="">
      </div>

      <!-- <a href="#" class="elimina">Elimina</a> -->
    </div>
    <!-- end hijo_form_academica -->
  </script>
  <script src="{{ asset('js_a/jsrender.min.js')}}"></script> --}}