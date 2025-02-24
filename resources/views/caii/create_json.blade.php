@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Creación del Evento </h4>
                
                  <form class="forms-sample pr-4 pl-4" id="formModulos" action="" method="post">
                    {!! csrf_field() !!}
                      <input type="hidden" id="datar" name="datar">
                      <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label d-block">Evento <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" id="nombre_evento" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ old('nombre_evento') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label">Vacantes <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" id='vacantes' name="vacantes" required="" placeholder="Cantidad de vacantes" value="{{ old('vacantes') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="auto_conf" class="col-sm-3 col-form-label">Tendrá Confirmación Pre-Registro</label>
                        <div class="col-sm-9 capa-auto_conf ">
                          <select class="form-control text-uppercase valid" id="auto_conf_pre" name="auto_conf_pre" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group row d-none campos_opcles_pre bg-secondary">
                        <label for="email_asunto_pre" class="col-sm-3 col-form-label">Asunto para la Confirmación <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="email_asunto_pre" placeholder="Ejm: Se ha registrado satistactoriamente al..." value="{{ old('email_asunto_pre') }}" />
                        </div>
                      </div>
                      <div class="form-group row d-none campos_opcles_pre bg-secondary">
                        <label for="email_id_pre" class="col-sm-3 col-form-label">Enviado desde <span class="text-danger">*</span></label>
                        <div class="col-sm-9 capa-email_id {{-- poner espacio --}}">
                          <select class="form-control" id="email_id_pre" name="email_id_pre">
                            <option value="">SELECCIONE / CHANGE</option>
                            @foreach($emails as $e)
                            <option @if($e->id == old('email_id_pre')) selected @endif value="{{$e->id}}">{{$e->nombre}} - {{$e->email}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="form-group row d-none campos_opcles_pre bg-secondary">
                        <label for="auto_conf" class="col-sm-3 col-form-label">Confirmación por</label>
                        <div class="col-sm-9">

                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="prereg_confirm_email" id='prereg_confirm_email' type="checkbox" class="form-check-input" value="0"> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="prereg_confirm_msg" id="prereg_confirm_msg" type="checkbox" class="form-check-input" value="0"> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      {{-- ASUNTO CONFIRMACION --}}
                    

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y Continuar Paso 2</button>
                        
                        <a href="{{ route('caii.index') }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>

                  </form>
                  
                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        @include('email.view_html.view_html')
        

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

@endsection

@section('scripts')
<script>
  console.log('Ejecutando create...');
  var inp_index = 0;

        var data = []
        var $inputs, $inputs2, $btnModal, $cbfields, $fopt, $fvselect;
        var recs = [];
        var ins = [];
        var type_id_old = 0
        var doctypes = [], groups = [], countries = [], departments = [], provinces = [], emails = [], domains = [];

        var $ftext, $ftexts, $fselect, $flatRadios1, $flatRadios2, $btnAddField2;
        var $fMaxlength, $fPlaceholder, $fEditor, $fMin, $fMax, $fStep, $fDominio, $fGrupo, $fFormato;
        var $fCond, $fEfecto, $campos2, $fAccept, $fSize, $fcampos;
        var $fExp, $fMinE, $fMaxE, $fCountE;
        var $chkPermite;
        var indexlel = 1;
        var oinputs = {};
        var oidputs = {};


        $('document').ready(function(){
            //$.fn.select2.defaults.set('language', 'es');
            var select2_opts = {
                language: "es"
            };

            $inputs = $("#inputs");
            $inputs2 = $("#inputs2");
            $btnModal = $("#btnModal");
            $cbfields = $("#cbfields");
            $fvselect = $("#fvselect");
            $fopt = $("#fopt");


            /* $("#formField").on("submit",function(e){
                var index = parseInt($("#findex").val());
                var is_detail = parseInt($("#is_detail").val()) == 1 ?1:0;
                var d = is_detail?ins:recs;

                var position = parseInt($("#fposition").val());

                if(0&&getExistsPosition(d, position)){
                    swal("Advertencia", "La posicion existe", "warning").then(function() {
                        swal.close();
                        $("#fposition").focus().select();
                    });
                    return;
                }
                index = parseInt($("#findex").val());
                var $inp = is_detail !=1 ?$inputs:$inputs2;

                var m_field_id = parseInt( $("#ftype").val() || "0");

                var opt = {
                    "id": parseInt($("#fid").val() || "0"),
                    "name": $("#fname").val(),
                    "title": $("#ftitle").val(),
                    "subtitle":  $('#fsubtitle').summernote('code'),//$("#fsubtitle").val(),
                    "value": getFValue(),
                    "note": $('#fnote').summernote('code'),//$("#fnote").val(),
                    "m_field_id": m_field_id,
                    //"m_attr_id": parseInt($("#ffield option:selected").attr("value") || "0"),//$("#ffield").val(),
                    "m_attr_id": parseInt($("#ffield").val() ||0),//$("#ffield").val(),
                    "required": $("#frequired").prop("checked")?1:0,
                    "visible": $("#fvisible").prop("checked")?1:0,
                    "opt": JSON.stringify(generateOpt()),
                    "is_title_hidden": $("#f_is_title_hidden").prop("checked")?1:0,
                    "is_fullsize": $("#f_is_fullsize").prop("checked")?1:0,

                }
                console.log("SALIENDO", opt);
                var val = $("#campos2").val();
                // if(verifyCValue(d, index, "name", $("#fname").val())){
                //     swal("Advertencia", "El nombre existe", "warning").then(function() {
                //         swal.close();
                //         $("#fposition").focus().select();
                //     });
                //     return false;
                // }
                if(verifyCValue(d, index, "title", $("#ftitle").val())){
                    swal("Advertencia", "El titulo existe", "warning").then(function() {
                        swal.close();
                        $("#fposition").focus().select();
                    });
                    return false;
                }
                if (index==-1){
                    opt.id = 0;
                    opt.is_detail = is_detail;//ADDED
                    //$inp.append($(getText(opt)));
                    $inp.append(generateInputHML(opt, 0, -1));
                    index = d.length;
                    //d.//ADDED
                    setValueD(d, index);
                }else{
                    opt.id = parseInt(opt.id);
                    opt.is_detail = is_detail;
                    var tag = parseInt($($inp.children()[index]).attr("tag"));
                    $($inp.children()[index]).replaceWith(generateInputHML(opt, opt.id, tag));//$(getText(opt))
                    loadCombo($("#inp-"+opt.id), opt);
                }
                setPickers();
                setValueD(d, index);
                $('#ModalForm').modal('toggle');
                if(m_field_id==17){
                    loadQuestion($("#inp-"+opt.id), getQuestionList());
                }
                $("#questions").empty();

                e.preventDefault();
                return false;
                //
            }); */

            $('#prereg_confirm_email').click(function() {
                // Verifica si el checkbox está marcado
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
               }
            });

            $('#prereg_confirm_msg').click(function() {
                // Verifica si el checkbox está marcado
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
               }
            });


            $("#formModulos").on("submit", function(e){
                e.preventDefault();
                console.log('Probando... formulario');
                /* for(i in recs)
                    recs[i]["tag"] = getTagIndex(recs, i);
                for(i in ins)
                    ins[i]["tag"] = getTagIndex(ins, i); */


                /* var opt = {
                    "id": parseInt($("#fid").val() || "0"),
                    "name": $("#fname").val(),
                    "title": $("#ftitle").val(),
                    "subtitle":  $('#fsubtitle').summernote('code'),//$("#fsubtitle").val(),
                    "value": getFValue(),
                    "note": $('#fnote').summernote('code'),//$("#fnote").val(),
                    "m_field_id": m_field_id,
                    "m_attr_id": parseInt($("#ffield").val() ||0),//$("#ffield").val(),
                    "required": $("#frequired").prop("checked")?1:0,
                    "visible": $("#fvisible").prop("checked")?1:0,
                    "opt": JSON.stringify(generateOpt()),
                    "is_title_hidden": $("#f_is_title_hidden").prop("checked")?1:0,
                    "is_fullsize": $("#f_is_fullsize").prop("checked")?1:0,
                } */
                var opt = {
                  'id': '1',
                  'evento': $("#nombre_evento").val(),
                  'prereg_confirm_email': $("#prereg_confirm_email").val(),
                  'prereg_confirm_msg': $("#prereg_confirm_msg").val(),
                }
                console.log("SALIENDO", opt);
                /* $("#datar").val(JSON.stringify(recs));
                $("#datai").val(JSON.stringify(ins)); */
                $("#datar").val(JSON.stringify(opt));
                //$("#datai").val(JSON.stringify(ins));

                console.log('Submit');
                console.log(JSON.stringify(ins));
                this.submit();
                return false;
            });

          });
</script>
@endsection