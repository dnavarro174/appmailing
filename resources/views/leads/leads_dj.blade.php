@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Leads / Registros 
                  @if($_GET['tipo']==8)
                    <span class="badge badge-success">OCI</span>
                    @php
                    $genera_pdf = 'dj_form.generate-pdf';
                    @endphp
                  @endif
                  @if($_GET['tipo']==10)
                    <span class="badge badge-danger">CGR</span>
                    @php
                    $genera_pdf = 'djcgr_form.generate-pdf';
                    @endphp
                  @endif

                  @if($_GET['tipo']==8)
                  <a class="btn btn-link" href="{{ route('grupo-dj.index') }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver</a>
                  <a class="btn btn-link" href="{{ route('dj_link.create', array('id'=>session('eventos_id')))}}" target="_blank"><i class="mdi text-link mdi-link-variant"></i>Link Form</a>
                  @else
                  <a class="btn btn-link" href="{{ route('grupo-djcgr.index') }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver</a>
                  <a class="btn btn-link" href="{{ route('djcgr_link.create', array('id'=>session('eventos_id')))}}" target="_blank"><i class="mdi text-link mdi-link-variant"></i>Link Form</a>
                  @endif

                  <a href="{{route('ddjj-comprimido.zip',['id'=>session('eventos_id')])}}"><span class="badge badge-dark mt-1"> <i class="mdi mdi-download text-white icon-md"></i> Descargar DDJJ</span></a>
                  
                </h4>
                <span class="badge @if($_GET['tipo']==8) badge-success @else badge-danger @endif">DJ: {{\Illuminate\Support\Str::limit(session('evento')['nombre'],110)}}</span>
                
              </div>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-5 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">
                        <input type="hidden" name="tipo" id="tipo" value="{{$_GET['tipo']}}">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('leads.index', ['tipo'=>$_GET['tipo']])}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="g" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">FILTRAR POR</option>
                          <option value="1">DJ APROBADOS</option>
                          <option value="2">DJ RECHAZADOS</option>
                          <option value="0">DJ EMITIDAS</option>
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="mod" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">POR MODALIDAD</option>
                          @foreach($array['modalidades'] as $m)
                            <option value="{{ $m->modalidad }}">{{ $m->modalidad }}</option>
                          @endforeach
                          {{-- <option value="1">CLASES EN VIVO</option>
                          <option value="2">FORMATO MOOC</option>
                          <option value="3">AUTOINSTRUCTIVO </option>
                          <option value="4">PRESENCIAL</option>
                          <option value="5">HIBRIDO</option> --}}
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="cod" id="cod" onchange="submit();">
                          <option selected="selected" value="">CÓDIGO</option>
                          @foreach($array['cod_cursos'] as $c)
                            <option value="{{ $c->cod_curso }}">{{ $c->cod_curso }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="cur" id="cur" onchange="submit();">
                          <option selected="selected" value="">CURSO</option>
                          @foreach($array['nom_cursos'] as $n)
                          <option value="{{ $n->id }}">{{ $n->nom_curso }}</option>
                        @endforeach
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="reg" id="reg" onchange="submit();">
                          <option selected="selected" value="">POR ESTUDIANTES</option>
                            <option value="1">APROBADOS</option>
                            <option value="2">DESAPROBADOS</option>
                        </select>
                      </div>
                      
                      
                      <div class=" col-sm-1 col-xs-12">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected="" @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected="" @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected="" @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected="" @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected="" @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected="" @endif>500</option>
                          @else
                          <option value="15">15</option><option value="20">20</option><option value="30" >30</option><option value="50" >50</option><option value="100">100</option><option value="500">500</option>{{-- <option value="-1" >Todos</option> --}}
                          @endif
                        </select>
                      </div>

                      <div class=" col-sm-1 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
                        
                      </div>
                    </div>
                  </form>
                </div>
              </div>


              
              
              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('leads.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <input type="hidden" name="xtipo" id="xtipo" value="{{$_GET['tipo']}}">

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 2)
                        @if($_GET['tipo']!=4)
                        <a href="{{ route('leads.create', ['tipo'=>4]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn "><i class="mdi mdi-plus text-white icon-md" ></i></a>
                        @endif
                      @endif
                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 2)
                        @if($_GET['tipo']!=4)
                        <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Importar" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                        @endif
                      @endif
                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Reporte
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <a class="dropdown-item" href="{{route('reportes.e_registrados')}}?t=8">Registrados</a>
                            </div>
                        </div>
                        {{-- Nuevos Cursos --}}
                        <button class="btn btn-sm @if($_GET['tipo']==8)btn-success @else btn-danger @endif text-white" id="addCursos" type="button"
                                data-toggle="modal" data-target="#modalNewFormCursos" data-remote="{{route('agregar.cursos')}}"
                                data-backdrop="static" data-title="Listado de Cursos" data-fc="form-codigo">
                            <i class="mdi mdi-settings text-white icon-md"></i> Agregar Cursos
                        </button>

                        {{-- <button class="btn btn-sm btn-dark text-white btnImport" id="importarCursos--" type="button"
                            data-toggle="modalImportar" data-target="#modalRemote" data-remote="{{route('importar_cursos')}}"
                            data-backdrop="static" data-title="Importar Cursos" >
                            <i class="mdi mdi-settings text-white icon-md"></i> Importar**
                        </button> --}} 

                      @endif

                      {{-- Colaboradores --}}
                      @if($_GET['tipo']==10)
                      <button class="btn btn-sm btn-danger text-white " id="importarColaboradores" type="button"
                              data-toggle="modal" data-target="#modalNewFormCursos" data-remote="{{route('agregar.colaboradores')}}"
                              data-backdrop="static" data-title="Personal.CGR" data-fc="form-cgr">Personal.CGR
                      </button>
                      {{-- modalNewFormColaboradores -  --}}
                      @endif
                      

                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        @if(!session('evento')['maestria'])
                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Asistencia
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('asistencia.create',['eventos_id'=>session('eventos_id')]) }}">Registrar Ingreso y Salida</a>
                            <div role="separator" class="dropdown-divider "></div>
                            <a class="dropdown-item" href="{{ route('asistencia.index', ['eventos_id' => session('eventos_id')]) }}" target="_blank">Listado de Asistencias</a>
                            <div role="separator" class="dropdown-divider "></div>
                            <a class="dropdown-item" href="{{route('reportes.a_general')}}">Reporte General</a>
                          </div>
                        </div>
                        @endif
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $estudiantes_datos->firstItem() }} - {{ $estudiantes_datos->lastItem() }} de
                          {{ $estudiantes_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="text-center" style="width: 3%;">#</th>
                              <th style="width: 5%;">Fecha</th>
                              <th class="text-center" style="width: 8%;">DNI</th>
                              <th style="width: 25%;">Apellidos y Nombres</th>
                              <th style="width: 10%;">Modalidad</th>
                              <th style="width: 10%;">Código</th>
                              <th style="width: 10%;">Curso</th>
                              <th style="width: 10%;">F.Inicio</th>
                              <th style="width: 10%;">F.Fin</th>
                              <th style="width: 10%;">Nota</th>
                              <th style="width: 10%;"></th>
                              <th style="width: 10%;">País</th>
                              <th style="width: 10%;">Depto</th>
                              <th style="width: 10%;">Distrito</th>
                              <th style="width: 5%;">Email</th>
                              <th style="width: 5%;">Email2</th>
                              <th style="width: 5%;">Celular</th>
                              <th style="width: 10%;">COND.1</th>
                              <th style="width: 10%;">COND.2</th>
                              <th style="width: 10%;">COND.3</th>
                              <th style="width: 5%;">COND.4</th>
                              <th style="width: 5%;">COND.5</th>
                              <th style="width: 5%;">COND.6</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($estudiantes_datos as $datos)
                              <?php 
                              $bg = "";
                                                            
                              if($datos->confirmado==1 ){$bg ='#f8fdc9';}#aprobado: dj Amarillo
                              if($datos->confirmado==2 ){$bg ='#e5c7f5';}#observado: dj morado
                              if($datos->confirmado==3 ){$bg ='#f7b7b7';}#rechazado: dj rojo

                              if($datos->dtrack=="SI")$bg='#f8fdc9';//Carta de compromiso generada -- amarillo

                              if($datos->nota > 0 and $datos->nota<14){$bg ='#f9c982';}#Jalado: dj naranja
                              if($datos->nota > 13 and $datos->nota<=20){$bg ='#83f3bc';}#Aprobo dj verde

                              ?>
                            <tr role="row" class="odd" style="background: {{ $bg }}">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id_dj }}" data-id="{{ $datos->id_dj }}"></td>
                                <td>{{-- {{ $datos->id }} -  --}}{{$datos->id_dj}}</td>
                                <td>{{ $datos->created_at->format('d/m/y') }} <br>{{ $datos->created_at->format('H:i:s') }}</td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)

                                    @if($_GET['tipo']==8 or $_GET['tipo']==10)
                                      <a href="{{route('leads.edit',['id'=>$datos->id,'tipo'=>$_GET['tipo'], 'det'=>$datos->id_dj])}}">{{ $datos->dni_doc }}</a>
                                    @else
                                      <a href="{{route('leads.edit',['id'=>$datos->id,'tipo'=>$_GET['tipo']])}}">{{ $datos->dni_doc }}</a>
                                    @endif
                                  @else
                                    {{ $datos->dni_doc }}
                                  @endif
                                </td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ $datos->modalidad }}</td>
                                <td>{{ $datos->cod_curso }}</td>
                                <td><em title="{{$datos->nom_curso}}">{{ \Illuminate\Support\Str::limit($datos->nom_curso,20) }}</em>
                                </td>
                                <td>{{ $datos->fech_ini }}</td>
                                <td>{{ $datos->fech_fin }} {{-- {{$array['folder']}} --}}</td>
                                <?php 
                                $bgn = "";
                                if($datos->nota > 0 and $datos->nota<=13){$bgn ='#f9c982';}elseif($datos->nota > 13 and $datos->nota<=20){$bgn ='#83f3bc';}else{$bgn ='';}?>
                                <td style="background: {{ $bgn }}" class="text-center"><strong>{{ $datos->nota }}</strong></td>
                                <td>
                                    @if(File::exists("storage/ddjj/{$array['folder']}/".session('eventos_id').'-'.$datos->dni_doc.'.pdf'))
                                      <a href="{{asset("storage/ddjj/{$array['folder']}/".session('eventos_id').'-'.$datos->dni_doc.'.pdf')}}" title="Ver PDF" target="_blank"><span class="badge badge-dark disabled">PDF</span></a>
                                    @endif
                                    @if(File::exists("storage/ddjj/{$array['folder']}/".session('eventos_id').'-'.$datos->dni_doc.'-'.$datos->detalle_id.'.pdf'))
                                      <a href="{{asset("storage/ddjj/{$array['folder']}/".session('eventos_id').'-'.$datos->dni_doc.'-'.$datos->detalle_id.'.pdf')}}" title="Ver PDF" target="_blank"><span class="badge badge-dark disabled">PDF</span></a>
                                    @endif
                                      <a href="{{route($genera_pdf,[session('eventos_id'),$datos->dni_doc,$datos->detalle_id])}}" title="Ver PDF" target="_blank"><span class="badge badge-danger">crear PDF</span></a>
                                    @if(session('tipo_dj')==10) 
                                      @if($datos->confirmado!=3 ) 
                                      <br>
                                      <a href="{{route($genera_pdf,[session('eventos_id'),$datos->dni_doc,$datos->detalle_id,'carta'])}}" title="Previsualizar Carta de Compromiso" target="_blank"><span class="badge badge-success mt-1"> <i class="mdi mdi-eye text-white icon-md"></i> VIEW CC</span></a>
                                      @endif
                                    @endif
                                    
                                </td>
                                <td> {{ \Illuminate\Support\Str::limit($datos->pais,8) }} </td>
                                <td> {{ \Illuminate\Support\Str::limit($datos->region,8) }} </td>
                                <td> {{ \Illuminate\Support\Str::limit($datos->distrito,8) }} </td>
                                <td>{{ $datos->email }}</td>
                                <td>{{ $datos->email_labor }}</td>
                                <td>
                                  @if($datos->celular != "") {{ $datos->codigo_cel." ".$datos->celular }} @endif
                                </td>
                                <td class="text-center">{{ $datos->preg_1, '' }}</td>
                                <td class="text-center">{{ $datos->preg_2, '' }}</td>
                                <td class="text-center">{{ $datos->preg_3, '' }}</td>
                                <td class="text-center">{{ $datos->preg_4, '' }}</td>
                                <td class="text-center">{{ $datos->preg_5, '' }}</td>
                                <td class="text-center">{{ $datos->preg_6, '' }}</td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $estudiantes_datos->appends(request()->query())->links() !!}
                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

                </div>
              </div> {{-- end cap_form_list --}}
            </div>
          </div>
        </div> <!-- end listado table -->

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

  {{--
   @include('importar.colaboradores') --}}
   <div id="moodal">
      @include('importar.cursos')
   </div>


@endsection

@section('scripts')

<script>
// Form Agregar cursos
$(window).ready(function(){

    
        $('body').on('click', '[data-toggle="modal"]', function(){
            var title=$(this).data("title");
            var fc = $(this).data("fc");
            var $target = $($(this).data("target")+' .modal-body');
            console.log('New form Modal cursos...');
            
            if(title)
                $($(this).data("target")+' .modal-header .modal-title').html(title);
            $target.html('<small> Cargando... </small>');

            $target.load($(this).data("remote"),function(){
            });
        });

        
        // Open modal Cursos-Personal
        $('body').on('click', '#importarCursos', function(){
          console.log('Abrir ventana importar...X');
          $("#modalImportar").modal('show');
          $("#modalImportar form")[0].reset();
        });

        $('body').on('keydown', '#curso-email', function(event){
            if(event.keyCode==13){
                event.stopPropagation();
                event.preventDefault();
                return false;
            }
        });
        $('body').on('keydown', '#curso-email', function(event){
            if(event.keyCode==13){
                event.stopPropagation();
                event.preventDefault();
                return false;
            }
        });
        $('body').on('click', '.curso-edit', function(event){
          $('#formBuscar').addClass('d-none');
          $('#formNuevo').removeClass('d-none');
            var $this = $(this);
            var id = $this.attr("tag");
            var $tds = $this.parents("tr").find("td");
            var email = $tds.eq(1).text().trim();
            var cod_curso = $tds.eq(2).text().trim();
            var modalidad = $tds.eq(3).text().trim();
            var fech_ini = $tds.eq(4).text().trim();
            var fech_fin = $tds.eq(5).text().trim();
            var checked = $tds.eq(6).find('span').text()=="SI";

            var xdatos = {
              'nom_curso' : email, 
              'cod_curso' : cod_curso, 
              'modalidad' : modalidad, 
              'fech_ini'  : fech_ini, 
              'fech_fin'  : fech_fin,
            }
            cambia(id, xdatos, checked, email, "Actualizar");
            focusNombre();
        });
        $('body').on('click', '.curso-delete', function(event){
            var $this = $(this);
            var id = $this.attr("tag");
            var $tds = $this.parents("tr").find("td");
            var email = $tds.eq(1).text().trim();
            swal({
                title: "¿Estas seguro de eliminar?",
                text: "Desea eliminar \""+email+"\"?",
                icon: "warning",
                buttons: ["Cancelar","Aceptar"],
                dangerMode: true,
            })
                .then((ok) => {
                    if (ok) {
                        $.post("{{route("agregar.cursos")}}",{
                            id:id,
                            _token:'{{csrf_token()}}',
                            delete:1
                          },function(data,response){
                            $('#modalNewFormCursos .modal-body').html(data.html);
                            
                            if(data.msg==1){
                              swal(
                                "Mensaje", 
                                "Registro borrado.", {
                                  icon: "success",
                              });
                            }else{
                              swal(
                                "Mensaje", 
                                "No se puede eliminar el curso, porque tiene registrado \""+data.cant+" Declaraciones Juradas\".", {
                                  icon: "warning",
                              });
                            }
                            
                            window.setTimeout(focusNombre,100);
                        }, "json");

                    } else {
                    }
                });


        });

        function cambia(id,data, checked, emailDefault, buttonText){
            $("#curso-id").val(id);
            $("#curso-save").html('<i class="mdi mdi-content-save text-white icon-md" ></i> '+buttonText);
            $("#curso-status").prop('checked',!!checked);
            $("#nom_curso").val(data.nom_curso);
            $("#cod_curso").val(data.cod_curso);
            $("#modalidad").val(data.modalidad);
            $("#fech_ini").val(data.fech_ini);
            $("#fech_fin").val(data.fech_fin);
            emailDefault="";
            $("#curso-def").html(emailDefault);
        }
        function focusNombre(){
            $("#nom_curso").focus().select();
        }
        function noemailsNuevo(){
            cambia("0","",true,"","Grabar");
            focusNombre();
            $('#formBuscar').removeClass('d-none');
            $('#formNuevo').addClass('d-none');
        }
        
        $('body').on('click', '#curso-nuevo', function(){
          $('#formBuscar').addClass('d-none');
          $('#formNuevo').removeClass('d-none');
        });

        $('body').on('click', '#curso-cancel', noemailsNuevo);
        $('body').on('click', '#curso-save', function(){

            $.post("{{route("agregar.cursos")}}",{
                id:$("#curso-id").val(),
                status:$("#curso-status").prop("checked")?1:0,
                nom_curso:$("#nom_curso").val(),
                cod_curso:$("#cod_curso").val(),
                modalidad:$("#modalidad").val(),
                fech_ini:$("#fech_ini").val(),
                fech_fin:$("#fech_fin").val(),
                id:$("#curso-id").val(),
                _token:'{{csrf_token()}}',
                save:1
            },function(data){
                $('#modalNewFormCursos .modal-body').html(data);
                window.setTimeout(focusNombre,100);
            });
        });
        $('body').on('click', '#curso-buscar', function(){
          let url_new = $('#curso-buscar').attr('data-search');
          //{{route("agregar.cursos")}}
            $.post(url_new,{
                id:$("#curso-id").val(),
                s:$("#s").val(),
                _token:'{{csrf_token()}}',
                save:0
            },function(data){
                $('#modalNewFormCursos .modal-body').html(data);
                window.setTimeout(focusNombre,100);
            });
        });
        $(document).on('click','#modalNewFormCursos .page-item .page-link', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            var action = $(this).attr('href');
            $('#modalNewFormCursos .modal-body').load(action);
        });
        $('#modalNewFormCursos').on('hide.bs.modal', function(){
            //cargaParticipantes()
            //do your stuff
        });

    // Cerrar importacion y ver listado actualizado:
    $('#btnCerrar_curso').click((e) => {
        //location.reload();
        let url_home = $('.brand-logo').attr('href');
        let tipo = "";
        let xurl_list="";

        let xtipo_form = $('#tipo_form').val();
        if(xtipo_form=="cgr"){
          tipo = "colaboradores";
        }else{
          tipo = "cursos";
        }
        xurl_list = url_home+"/agregar_"+tipo;
        //console.log(tes,xtipo_form,xurl_list);

        $('#Modal_estudiantes,#modalImportar,#Modal_organizar').modal('hide');
        $('#modalNewFormCursos .modal-body').load(xurl_list,function(){}); 

      });
    
});

function openModal(tipoFormulario){
    console.log('Open Modal--', tipoFormulario);
    //return false;
    var tipo="";
    var url_1,url_2,url_3,url_list="";
    var url_home = $('.brand-logo').attr('href');

    $("#modalImportar").modal('show');
    $("#modalImportar form")[0].reset();
    let url_new_import = $('#estudiantesImportSave').attr('data-url');

    let url_new = $('#importarCursos-2').attr('data-remote');
    
    if(tipoFormulario=="curso"){
      tipo = "cursos";
      url_list = url_home+"/agregar_"+tipo;
      $('#tipo_form').val('curso');
      $('#addCursos').attr('data-remote',url_list);
    }else{
      tipo = "colaboradores";
      url_list = url_home+"/agregar_"+tipo;
      $('#tipo_form').val('cgr');
      $('#addCursos').attr('data-remote',url_list);
    }

      url_1 = url_home+"/"+tipo+"_import"; //cursos_import cursos_importsave cursos_importresults
      url_2 = url_home+"/"+tipo+"_importsave";
      url_3 = url_home+"/"+tipo+"_importresults";

      $('#f_cargar_cursos').attr('action',url_1);
      $('#estudiantesImportSave').attr('action',url_2);
      $('#iframePrev').attr('src',url_3);
      $('#addCursos').attr('data-remote',url_list);//btnCerrar_curso
      console.log("tipoFormulariosss: "+tipoFormulario+ "** tipo: "+tipo);
      console.log(url_list);
      
    /* var action = $(this).attr('href');
    $('#modalNewFormCursos .modal-body').load(action); */
  }
</script>

@endsection