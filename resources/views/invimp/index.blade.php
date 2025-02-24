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
                            <h4 class="card-title">Lista de Importaciones de Invitados</h4>

                            <div class="row" id="capBusqueda">
                                <div class="col-sm-12">
                                    <form>
                                        <div class="form-row">
                                            <div class=" col-sm-9 col-xs-12">
                                                <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                                                <?php if (isset($_GET['s'])){ ?>
                                                <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('invimport.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                                                <?php } ?>

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

                                            <div class=" col-sm-2 col-xs-12">
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
                                    {{-- {{ Form::open(array('route' => array('invimport.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }} --}}
                                    <form action="{{route('invimport.eliminarVarios')}}" id="form-delete" style='display:inline' method="post">
                                        @csrf

                                        <div class="row">{{-- cap: opciones --}}

                                            <div class="col-xs-12  col-sm-8 text-left mb-4">
                                                @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                                                    <a href="#" onclick="eximForm()" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                                                        <i class="mdi mdi-plus text-white icon-md" ></i> Agregar Nuevo
                                                    </a>
                                                @endif
                                                @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                                    <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_bd" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                                                @endif
                                            </div> {{-- end derecha --}}
                                            <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $camps->firstItem() }} - {{ $camps->lastItem() }} de
                          {{ $camps->total() }}
                        </span>
                                            </div>{{-- end izq --}}
                                        </div> {{-- end cap: opciones --}}



                                        <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                                            <div class="row">
                                                <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                                                    <table id="order-listing" class="table table-hover table-sm">
                                                        <thead class="thead-dark">
                                                        <tr role="row">
                                                            <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                                                            <th style="width: 3%;"></th>
                                                            <th class="sorting" style="width: 2%;">#</th>
                                                            <th class="sorting" style="width: 200px;">Fecha</th>
                                                            <th class="sorting" style="width: 50%;">Nombre</th>
                                                            <th class="sorting" style="width: 8%;">Procesado</th>
                                                            <th class="sorting" style="width: 8%;">Total</th>
                                                            <th class="sorting" style="width: 8%;">OKS</th>
                                                            <th class="sorting" style="width: 8%;">Errores</th>
                                                            <th class="sorting" style="width: 8%;">Archivo</th>
                                                            <th class="sorting" style="width: 8%;">Tamaño</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach ($camps as $datos)
                                                            <tr role="row" class="odd" <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?>>
                                                                <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                                                <td nowrap="">
                                                                    @if(@isset($permisos['editarr']['permiso']) and  $permisos['editarr']['permiso'] == 1){{-- 1 --}}
                                                                    <a href="{{ route('invimport.edit',$datos->id)}}"  data-id="{{ $datos->dni_doc }}">
                                                                        <i class="mdi mdi-pencil text-info icon-md"></i>
                                                                    </a>
                                                                    @endif
                                                                    @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                                                        <a href="{{ route('invimport.show',$datos->id)}}" class="">
                                                                            <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">{{ $datos->id }}</td>

                                                                <td>{{ $datos->created_at->format('d/m/y H:i') }}</td>
                                                                <td>{{ $datos->nombre }}</td>
                                                                <td class="text-center">{{ $datos->procesado }}</td>
                                                                <td class="text-center">{{ $datos->total }}</td>
                                                                <td class="text-center">{{ $datos->oks }}</td>
                                                                <td class="text-center">{{ $datos->error }}</td>
                                                                <td class="text-center">{{ $datos->file }}</td>
                                                                <td class="text-center">{{ $datos->filesize }}</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning text-white p-1 text-center" type="button" id="lst"
                                                                            data-toggle="modal" data-target="#modalRemote" data-remote="{{route('invimp.detail', $datos->id)}}"
                                                                            data-backdrop="static" data-title="Listado" data-fc="form-codigo"
                                                                            data-imp-id="{{$datos->id}}"
                                                                    >
                                                                        <i class="mdi mdi-account-multiple text-white"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                    {!! $camps->appends(request()->query())->links() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    {{--{{ Form::close() }}  end close form --}}

                                </div>
                            </div> {{-- end cap_form_list --}}
                        </div>
                    </div>
                </div> <!-- end listado table -->


                @include('invimp.modal', ['action'=>route('invimport.imp')])


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
                                    @include('invimp.modal-inv')
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                {{-- modal openHTML --}}

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
        var IMP_ID = 0;
        var REMOTE_URL;
        (function($) {
            var $chkPrimeraFila = $('#chkPrimeraFila');
            function setDefaultFirstRow(){
                $('#txtFila').val($chkPrimeraFila.prop('checked')?'2':'1');
            }
            $chkPrimeraFila.on('click', setDefaultFirstRow);
            setDefaultFirstRow();



            $('body').on('click', '#lst', function(){
                resetFields();
                var title=$(this).data("title");
                IMP_ID=$(this).data("imp-id");
                var fc = $(this).data("fc");
                var $target = $($(this).data("target")+' .modal-table');
                REMOTE_URL= $(this).data("remote");
                if(title)
                    $($(this).data("target")+' .modal-header .modal-title').html(title);
                $target.html('<small> cargando... </small>');
                $target.load(REMOTE_URL,function(){
                });
            });

            $(document).on('keydown','#email', function(e){
                if(e.which==13)$("#nombres").select();
            });
            $(document).on('keydown','#nombres', function(e){
                if(e.which==13)$("#ap_paterno").select();
            });
            $(document).on('keydown','#ap_paterno', function(e){
                if(e.which==13)$("#ap_materno").select();
            });
            $(document).on('keydown','#ap_materno', function(e){
                if(e.which==13)$("#btnSave").trigger('click');
            });

            $(document).on('click','#modalRemote .page-item .page-link', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                var action = $(this).attr('href');
                $('#modalRemote .modal-table').load(action);
            });

            $("#form-reset").on('click', function(){
                resetFields();
                buscar();
            });

            $("#noemail-save").on('click', buscar);
            $("#form-estado").on('click', buscar);

            $('body').on('click', '.students-edit', function(event){
                $('#modalRemote .modal-table').load($(this).data("url"), function(){
                    focusNombre();
                });
            });

            $('body').on('click', '.students-save', function(){
                $.post($(this).data("url"),getFields(),function(data){
                    $('#modalRemote .modal-table').html(data);
                    window.setTimeout(focusNombre,100);
                });
            });

            $('body').on('click', '.students-delete', function(event){
                var $this = $(this);
                var id = $this.attr("tag");
                var url = $this.data("url");
                var $tds = $this.parents("tr").find("td");
                var email = $tds.eq(0).text().trim();
                swal({
                    title: "¿Estas seguro de eliminar?",
                    text: "Desea eliminar el estudiante de email \""+email+"\"?",
                    icon: "warning",
                    buttons: ["Cancelar","Aceptar"],
                    dangerMode: true,
                })
                    .then((ok) => {
                        if (ok) {
                            $.post(url,{
                                id:id,
                                _token:'{{csrf_token()}}',
                                delete:1
                            },function(data){
                                $('#modalRemote .modal-table').html(data);
                                swal("Se borro con exito", {
                                    icon: "success",
                                });
                                window.setTimeout(focusNombre,100);
                            });

                        } else {
                        }
                    });
            });

            function buscar(){
                var data = {
                    q: $("#modal-search").val(),
                    e: $("#form-estado").val(),
                    search: 1
                }
                var url = REMOTE_URL + '?'+$.param(data);
                console.log(url)
                $('#modalRemote .modal-table').load(url);
            }
            function resetFields(){
                $("#modal-search").val("");
                $("#form-estado").val(0);
            }
            function focusNombre(){
                $("#email").focus().select();
            }
            function getFields(){
                return {
                    sid:$("#sid").val(),
                    iid:$("#iid").val(),
                    email:$("#email").val()||"",
                    nombres:$("#nombres").val()||"",
                    ap_paterno:$("#ap_paterno").val()||"",
                    ap_materno:$("#ap_materno").val()||"",
                    _token:'{{csrf_token()}}',
                    save:1
                }
            }
        })(jQuery);
    </script>

@endsection
