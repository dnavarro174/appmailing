@extends('layout.home')

@section('content')
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_settings-panel.html -->

        <div class="main-panel">

          <div class="content-wrapper mt-3">
            <div class="row justify-content-center">
              <div class="col-md-9 grid-margin">
                <div class="card">
                  <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title text-transform-none">Mailing</h4>
                        <p class="card-text mb-0">
                          <a href="{{ route('campanias.index') }}" class="btn btn-link">
                            <i class="mdi text-link mdi-keyboard-backspace"></i>
                          Volver al listado</a>
                            <button class="btn btn-sm btn-warning text-white" type="button"
                                    data-toggle="modal" data-target="#modalRemote" data-remote="{{route('campanias.noemails')}}"
                                    data-backdrop="static" data-title="Emails no permitidos" data-fc="form-codigo">
                                <i class="mdi mdi-email text-white icon-md"></i> Correos exepciones
                            </button>
                        </p>
                      </div>
                      @if(Session::has('message-import'))
                          <p class="alert alert-info">{{ Session::get('message-import') }}</p>
                      @endif

                      <div id="capaEstudiantes" class="row">

                          <div class="col-12">
                              <form  action="{{route('campanias.store')}}" method="POST" id="form_html" name="form_html" style="display: inline;">

                                  @csrf


                                  <div class="row">
                                      <div class="col-xs-12 col-sm-6 mb-4">
                                          <div class="col-xs-12 col-sm-12 col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                              <h4 class="card-title m-0">Plantillas
                                                  @if(Request::has('history'))
                                                      <a class="btn btn-warning" href="{{ route('historiaemail.index', ['evid'=>1]) }}">Historial mensajes enviados</a>
                                                  @endif
                                              </h4>
                                              <a class="btn btn-sm btn-dark" href="{{ route('plantillaemail.create') }}">Crear HTML</a>
                                              <a class="btn btn-sm btn-success" href="{{ route('plantillaemail.index') }}">Listado HTML</a>
                                            </div>

                                              <div class="bloque_plantilla border  mb-4 pt-2" style="height:auto;min-height: 500px;overflow-x: auto;overflow-y: auto; ">
                                                  <ul class="">
                                                      @foreach ($plantilla_datos as $datos)
                                                          <li>
                                                              <a href="#" id="{{ $datos->id }}">
                                                                  <input type="radio" class="form btn-html" name="checkHTML" {{old('checkHTML')==$datos->id?'checked':''}}
                                                                         value="{{ $datos->id }}" data-xid="{{ $datos->id }}" >
                                                                  <span class="openHTML" data-id="{{ $datos->id }}">{{ $datos->nombre }}
                                                                      {{-- <em class="color-gris text-small" style="font-size: 10px;display: block;">{{ $datos->flujo_ejecucion }}</em> --}}</span>
                                                              </a>
                                                          </li>
                                                      @endforeach
                                                  </ul>
                                              </div>
                                          </div>

                                      </div> {{-- end col-sm-3 --}}

                                      <div class="col-xs-12 col-sm-6 mb-4" id="campo_2">
                                          <div class=" col-sm-12 col-xs-12 mt-4 mb-2 mb-2">
                                              <span class="text-white">&nbsp;</span>
                                          </div>

                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                            <div class="form-group">
                                                <label for="nombre">Nombre de Campaña <span class="text-danger">*</span></label>
                                                <div class="input-group mb-2">
                                                    <input type="text" name="nombre" id="nombre" required="" value="{{ old('nombre') }}" class="form-control">
                                                </div>
                                            </div>
                                          </div>

                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                            <div class="form-group">
                                              <label for="nombre">Correo Emisor: <span class="text-danger">*</span></label>
                                              <select class="form-control" name="from_id" id="from_id">
                                                  <option selected="selected" value="">ENVIAR DESDE</option>
                                                  @foreach($emails as $mail)
                                                      <option value="{{$mail->id}}" title="{{$mail->email}}" {{old('from_id')==$mail->id?'selected':''}}>{{$mail->nombre }} - {{$mail->email}}</option>
                                                  @endforeach
                                              </select>
                                            </div>
                                          </div>
                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                            <div class="form-group">
                                              <label for="nombre">FILTROS </label>
                                              <select class="form-control load" name="evento" id="evento">
                                                  <option selected="selected" value="">EVENTO</option>
                                                  @foreach($eventos as $evento)
                                                      <option value="{{$evento->id}}" {{old('evento')==$evento->id?'selected':''}}>{{$evento->nombre_evento }}</option>
                                                  @endforeach
                                              </select>
                                            </div>
                                          </div>

                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                              <select class="form-control load" name="grupo" id="grupo">
                                                  <option selected="selected" value="">GRUPOS</option>
                                                  @foreach($tipos as $tipo)
                                                      <option value="{{$tipo->codigo}}" {{old('grupo')==$tipo->nombre?'selected':''}}>{{$tipo->nombre }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                              <select class="form-control load" name="pais" id="pais">
                                                  <option selected="selected" value="">PAÍS</option>
                                                  <option value="PERÚ" {{old('pais')=="PERÚ"?'selected':''}}>PERÚ</option>
                                                  @foreach($paises as $pais)
                                                      <option value="{{$pais->name}}" {{old('pais')==$pais->name?'selected':''}}>{{$pais->name }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                              <select class="form-control load" name="depa" id="depa">
                                                  <option selected="selected" value="">DEPARTAMENTO</option>
                                                  <option value="LIMA">LIMA</option>
                                                  @foreach($departamentos_datos as $dep)
                                                      <option value="{{$dep->nombre}}" {{old('depa')==$dep->nombre?'selected':''}}>{{$dep->nombre }}</option>
                                                  @endforeach
                                              </select>
                                              <input type="hidden" name="valor" id="valor" value="0">
                                          </div>

                                          {{-- ORGANIZACIONES PRFESIONES--}}
                                          <div class=" col-sm-12 col-xs-12 mb-2 test d-none">
                                              <select class="form-control load" name="organizacion" id="organizacion">
                                                  <option selected="selected" value="">ENTIDAD</option>
                                                  @forelse($organizaciones as $organizacion)
                                                      <option value="{{$organizacion}}" {{old('organizacion')==$organizacion?'selected':''}}>{{$organizacion }}</option>
                                                  @empty
                                                  @endforelse
                                              </select>
                                          </div>
                                          <div class=" col-sm-12 col-xs-12 mb-2 test d-none">
                                              <select class="form-control load" name="profesion" id="profesion">
                                                  <option selected="selected" value="">PROFESIÓN</option>
                                                  @forelse($profesiones as $profesion)
                                                      <option value="{{$profesion}}" {{old('profesion')==$profesion?'selected':''}}>{{$profesion }}</option>
                                                  @empty
                                                  @endforelse
                                              </select>
                                          </div>

                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                              <div class="form-group" id="lblParticipantes">
                                                  <input type="hidden" name="participantes" id="participantes" required="" value="{{ old('participantes') }}" class="form-control text-uppercase">
                                                  <div class="alert text-center alert-danger" role="alert" id="alerta">
                                                    <span class="badge badge-danger badge-lg mb-2">0 participantes</span>
                                                      {{-- <strong>0</strong> participantes --}}
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-xs-12 col-sm-12 mt-3">
                                               <div class="col-sm- form-group">
                                                <label class="d-block col-form-label">
                                                    <input type="checkbox" id="chkSeleccionarTodos" name="all" value="1" {{old('all')==1?'selected':''}}><span id="spanConceder"> Enviar a toda la base de datos</span>
                                                </label>
                                                <label class="d-block col-form-label">
                                                    <input type="checkbox" id="chkTest" name="mailing_test" value="1" {{old('mailing_test')==1?'checked':''}}><span id="spanConceder"> Mailing de prueba</span>
                                                </label>
                                                <label class="d-block col-form-label" style="display: none !important;" id="email-list"> <span class="text-danger mb-2 d-block">Los emails tienen que estar separados por ;</span>
                                                  <input type="text" name="email_test" id="email_test" placeholder="email@gmail.com;email2@gmail.com" value="{{old('email_test')}}" class="form-control">
                                                </label>
                                              </div>
                                          </div>

                                          <div class="col-xs-12 col-sm-12">
                                              <div class="col-sm- form-group">
                                                  <label class="d-block col-form-label">
                                                      <input type="checkbox" id="chkInv" name="inv" value="1" {{old('inv')==1?'checked':''}}><span id="spanConceder">
                                                          Base de Datos Mailing Correos
                                                      </span>
                                                  </label>
                                              </div>
                                          </div>

                                          <div class=" col-sm-12 col-xs-12 mb-2">
                                              <div class="form-group">
                                                  <select class="form-control" name="inv_id" id="inv_id">
                                                      <option selected="selected" value="0">Todos</option>
                                                      @foreach($imp_datos as $im)
                                                          @php($xnombre = trim($im->nombre))
                                                          <option value="{{$im->id}}" title="{{$im->nombre}}" {{old('inv_id')==$im->id?'selected':''}}>{{$xnombre!=''?$xnombre:'Importacion # '.$im->id }}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>

                                          <div class="col-xs-12 col-sm-12 mt-3">
                                              <button type="button" class="btn btn-dark btn-block" name="btnGrabar" id="btnGrabar" disabled="true">Grabar Campaña</button>
                                          </div>


                                      </div>



                                  </div>



                                  {{-- end close form --}}
                              </form>


                              {{-- modal openHTML --}}
                              <div class="modal fade ass" id="openHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-800" role="document">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" id="plantillaHTML"></div>

                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              {{-- modal openHTML --}}
                          </div>




                      </div> {{-- end cap_form_list --}}


                  </div>
                </div>
              </div>
            </div>

          </div> <!-- end listado table -->

          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->

            <div class="modal hide fade" id="modalRemote" tabindex="-1" role="dialog" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form  id="f_modal" name="f_modal" method="post" action="" class="cmxform">
                            <div class="modal-header">
                                <h4 class="modal-title text-dark" id="myModalLabel">Nuevo</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class=" text-dark">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body pt-0">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
          @include('layout.footer')
          <!-- end footer.php -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <style>
        .bloque_plantilla ul li {
            list-style: none;
        }
    </style>

@endsection
@section('scripts')
    <script>
        var check_todo = 0;

        $('#chkSeleccionarTodos').click(function(e) {

            if ($('#chkSeleccionarTodos').is(':checked')) {

                var check_enviar = $('#chkSeleccionarTodos').val();
                console.log('check_enviar: activado ='+check_enviar);

                if ($('._check').is(':checked')) {

                    $('input[type=checkbox]:checked').each(function(i,j){
                        $(".btn-delete").prop('checked', false);
                    });
                }

                check_todo = 1;

            }else{
                console.log('check_enviar desactivado');
            }

        });
        //$('#btnGrabar').click(function(e) {
        //$('#form_html').submit(function(e) {


        /*
        $('.bloque_plantilla').css('background','#d5ebf3');


        let valor  = $('#valor').val();
                if(valor == 0){
                    $('#campo_2').css('background','#d5ebf3');
                    swal("Advertencia", "Debe seleccionar una opción", "warning");
                    $('#btnGrabar').attr('disabled', false);
                    return false;
                }

         */
        $(window).ready(function(){


            var $participantes = $("#participantes");
            var $alerta = $("#alerta");
            var $btnGrabar = $('#btnGrabar');
            var $chkSeleccionarTodos = $('#chkSeleccionarTodos');
            var $email_test = $('#email_test');
            var $chkTest = $('#chkTest');
            var $divTest = $('.test');
            var $emailList = $('#email-list');
            $btnGrabar.click(valida);

            var $nombre = $('#nombre');
            var $from_id = $('#from_id');
            var $evento = $('#evento');
            var $grupo = $('#grupo');
            var $pais = $('#pais');
            var $depa = $('#depa');
            var $profesion = $('#profesion');
            var $organizacion = $('#organizacion');

            var $chkInv = $("#chkInv");
            var $inv_id = $("#inv_id");
            function enableInv(check){
                $inv_id.attr('disabled', !check)
            }
            $inv_id.on('change', cargaParticipantes);
            $chkInv.on('click', function(){
                selecciona(this.checked);
                enableInv(this.checked);
                //cargaParticipantes()
            });
            enableInv(false);

            $chkSeleccionarTodos.click(function(){
                selecciona(this.checked);
            });
            $chkTest.on('click', function(){
                var chk = $chkTest.prop('checked');
                $divTest.css('display',chk?'none':'');
                $emailList.attr('style',!chk?"display: none !important":"display:''");
                $btnGrabar.text(!chk?'Grabar Campaña':'Enviar Prueba');

            });
            var email_count = 0;
            function validaEmailList(){
                email_count = 0;
                var separator = ";";
                var emails = $email_test.val().replace(/\s/g,'').split(separator);
                //var valid = true;
                var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                for (var i = 0; i < emails.length; i++) {email_count ++;
                    if( emails[i] == "" || ! regex.test(emails[i])){
                        //valid = false;
                        return false;
                    }
                }
                return true;
            }

            function selecciona(check){

                $evento.attr('disabled', check);
                $grupo.attr('disabled', check);
                $pais.attr('disabled', check);
                $depa.attr('disabled', check);
                $profesion.attr('disabled', check);
                $organizacion.attr('disabled', check);

                $evento.attr('disabled', check);
                $grupo.attr('disabled', check);
                cargaParticipantes();
            }

            function valida(){
                if($chkTest.prop('checked')&&!validaEmailList()){
                    swal("Advertencia", "Lista de correo no cumple formato valido de correo", "warning").then(function() {
                        swal.close();
                        $email_test.focus().select();
                    });
                    return;
                }
                var nombre = $nombre.val().trim();
                var participantes = $participantes.val();
                var chk = $('li input:radio:checked').attr("value");
                var plantilla = chk ? $('.openHTML[data-id="'+chk+'"]').html():'';
                var from_id = $from_id.prop('selectedIndex');

                $btnGrabar.attr('disabled', true);
                if(plantilla=='') {
                    swal("Advertencia", "Debe seleccionar una plantilla HTML", "warning").then(function() {
                        swal.close();
                        $btnGrabar.attr('disabled', false);
                    });
                    return;
                }
                if(nombre==""){
                    swal("Advertencia", "Ingrese nombre de campaña", "warning").then(function() {
                        swal.close();
                        $btnGrabar.attr('disabled', false);
                        $nombre.focus();
                    });
                    return;
                }
                if(from_id==0){
                    swal("Advertencia", "Seleccione correo", "warning").then(function() {
                        swal.close();
                        $btnGrabar.attr('disabled', false);
                        $from_id.focus();
                    });
                    return;
                }
                if(!$chkInv.prop("checked") && $evento.val()==""&&$grupo.val()==""&&$pais.val()==""&&$depa.val()==""&&$profesion.val()==""&&$organizacion.val()==""&&!$chkSeleccionarTodos.prop("checked")){
                    swal("Advertencia", "Debe seleccionar una opción", "warning").then(function() {
                        swal.close();
                        $btnGrabar.attr('disabled', false);
                        $evento.focus();
                    });
                    return;
                }
                if(participantes==0){
                    swal("Advertencia", "No hay participantes", "warning").then(function() {
                        swal.close();
                        $btnGrabar.attr('disabled', false);
                    });
                    return;
                }
                //HTML
                var html = "Se enviaran a: <span class='font-weight-bold'>"+participantes+"</span> usuarios la plantilla: <span class='font-weight-bold'>"+plantilla+'</span>';
                if($chkTest.prop("checked")) html = "Se enviaran a : <span class='font-weight-bold'>"+email_count+"</span> correos de prueba la plantilla: <span class='font-weight-bold'>"+plantilla+'</span>';
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;

                selecciona(false);
                enableInv(true);
                swal({
                    title: "¿Estas seguro de enviar?",
                    //text: "se enviaran a: "+participantes+" usuarios la plantilla: "+plantilla,
                    content: wrapper,
                    icon: "warning",
                    buttons: ["Cancelar","Aceptar"],
                    dangerMode: true,
                })
                    .then((ok) => {
                        if (ok) {
                            $btnGrabar.text('Procesando...');
                            document.form_html.submit();
                        } else {
                            $btnGrabar.attr('disabled', false);
                            //swal("Cancelo!");
                        }
                        if($chkSeleccionarTodos.prop("checked"))selecciona(true);
                    });
                return;
            }
            function cargaParticipantes(){
                setAlerta(0);
                $btnGrabar.attr('disabled', true);
                $.post( "{{route('campanias.participantes')}}", {
                    evento: $("#evento").val(),
                    grupo: $("#grupo").val(),
                    pais: $("#pais").val(),
                    depa: $("#depa").val(),
                    organizacion: $("#organizacion").val(),
                    profesion: $("#profesion").val(),
                    all: $("#chkSeleccionarTodos").prop("checked")?1:0,
                    inv: $("#chkInv").prop("checked")?1:0,
                    inv_id: $("#inv_id").val(),
                    "_token": "{{ csrf_token() }}"
                },function(data){
                    var n = parseInt(data);
                    setAlerta(n);
                }).done(function( data ) {

                }).always(function(){
                    $btnGrabar.attr('disabled', false);
                });
            }
            function setAlerta(n){
                $participantes.val(n);
                $alerta.removeClass("alert-success").removeClass("alert-danger");
                var txt = "participante";
                if(n!=1)txt+="s";
                $alerta.html(`<strong>${n}</strong> ${txt}`);
                $alerta.addClass(n>0?'alert-success':'alert-danger');
            }
            cargaParticipantes();
            $("select.load").on("change",cargaParticipantes);


            $(window).on("keydown", function(event){
                // Check to see if ENTER was pressed and the submit button was active or not
                if(event.keyCode === 13 && event.target === document.getElementById("btnGrabar")) {
                    // It was, so submit the form
                    //document.querySelector("form").submit();
                    valida();
                } else if(event.keyCode === 13 && event.target !== document.getElementById("btnGrabar") ){
                    // ENTER was pressed, but not while the submit button was active
                    valida();
                    //alert("Debe presionar el boton Grabar");
                    // Cancel form's submit event
                    event.preventDefault();
                    event.target.click();
                    return false;
                }
            });



            $('body').on('click', '[data-toggle="modal"]', function(){
                var title=$(this).data("title");
                var fc = $(this).data("fc");
                var $target = $($(this).data("target")+' .modal-body');
                if(title)
                    $($(this).data("target")+' .modal-header .modal-title').html(title);
                $target.html('<small> cargando... </small>');
                $target.load($(this).data("remote"),function(){
                });
            });

            $('body').on('keydown', '#noemails-email', function(event){
               if(event.keyCode==13){
                   event.stopPropagation();
                   event.preventDefault();
                   return false;
               }
            });
            $('body').on('keydown', '#noemails-email', function(event){
                if(event.keyCode==13){
                    event.stopPropagation();
                    event.preventDefault();
                    return false;
                }
            });
            $('body').on('click', '.noemails-edit', function(event){
                var $this = $(this);
                var id = $this.attr("tag");
                var $tds = $this.parents("tr").find("td");
                var email = $tds.eq(0).text().trim();
                var checked = $tds.eq(1).text()=="SI";
                cambia(id,email, checked, email,"Actualizar");
                focusNombre();
            });
            $('body').on('click', '.noemails-delete', function(event){
                var $this = $(this);
                var id = $this.attr("tag");
                var $tds = $this.parents("tr").find("td");
                var email = $tds.eq(0).text().trim();
                swal({
                    title: "¿Estas seguro de eliminar?",
                    text: "Desea eliminar \""+email+"\"?",
                    icon: "warning",
                    buttons: ["Cancelar","Aceptar"],
                    dangerMode: true,
                })
                    .then((ok) => {
                        if (ok) {
                            $.post("{{route("campanias.noemails")}}",{
                                id:id,
                                _token:'{{csrf_token()}}',
                                delete:1
                            },function(data){
                                $('#modalRemote .modal-body').html(data);
                                swal("Se borro con exito", {
                                    icon: "success",
                                });
                                window.setTimeout(focusNombre,100);
                            });

                        } else {
                        }
                    });


            });

            function cambia(id,email, checked, emailDefault, buttonText){
                $("#noemails-id").val(id);
                $("#noemail-save").html('<i class="mdi mdi-content-save text-white icon-md" ></i> '+buttonText);
                $("#noemails-status").prop('checked',!!checked);
                $("#noemails-email").val(email);
                emailDefault="";
                $("#noemails-def").html(emailDefault);
            }
            function focusNombre(){
                $("#noemails-email").focus().select();
            }
            function noemailsNuevo(){
                cambia("0","",true,"","Grabar");
                focusNombre();
            }
            $('body').on('click', '#noemails-cancel', noemailsNuevo);
            $('body').on('click', '#noemail-save', function(){
                $.post("{{route("campanias.noemails")}}",{
                    id:$("#noemails-id").val(),
                    status:$("#noemails-status").prop("checked")?1:0,
                    email:$("#noemails-email").val(),
                    id:$("#noemails-id").val(),
                    _token:'{{csrf_token()}}',
                    save:1
                },function(data){
                    $('#modalRemote .modal-body').html(data);
                    window.setTimeout(focusNombre,100);
                });
            });
            $(document).on('click','#modalRemote .page-item .page-link', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                var action = $(this).attr('href');
                $('#modalRemote .modal-body').load(action);
            });
            $('#modalRemote').on('hide.bs.modal', function(){
                cargaParticipantes()
                //do your stuff
            })


        });
    </script>
@endsection
