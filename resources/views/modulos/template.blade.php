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
                                    <h4 class="card-title text-transform-none">{{ $modulo["name"] }} - Plantilla</h4>

                                    <form class="forms-sample pr-4 pl-4" action="{{ route('modulos.plantilla.save') }}" method="post">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="id" value="{{ old('id', $modulo["id"], 0) }}" id="inpid">

                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-xl-21 col-form-label d-block">Nombre <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="{{ old('nombre', $plantilla['nombre']) }}" />
                                            </div>
                                            <label for="slug" class="col-sm-20 col-xl-2 col-form-label text-right d-block">Flujo Ejecucion <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <select name="flujo_ejecucion" id="flujo_ejecucion" class="form-control select-custom-2">
                                                    @foreach($flujos as $v)
                                                        <option value="{{$v}}" {{$v==$plantilla["flujo_ejecucion"]?"selected":""}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-xl-21 col-form-label d-block">Lista <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="lista" id="lista" placeholder="Lista" value="{{ old('lista', $plantilla["lista"]) }}" />
                                            </div>
                                            <label for="slug" class="col-sm-20 col-xl-2 col-form-label text-right d-block">Gafete?</label>
                                            <div class="col-sm-10 col-xl-4">
                                                <select name="gafete" id="gafete" class="form-control select-custom-2">
                                                    @foreach($gafetes as $v)
                                                        <option value="{{$v}}" {{$v==$plantilla["gafete"]?"selected":""}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="asunto" class="col-sm-2 col-form-label d-block">Asunto</label>
                                            <div class="col-sm-10">
                                                <input type="text" required="" class="form-control" name="asunto" id="asunto" placeholder="Asunto" value="{{ old('asunto', $plantilla["asunto"]) }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="badge badge-success even-larger-badge">PLANTILLA</div>
                                            </div>
                                            <div class="col-sm-12">
                                                <select name="select1" id="select1" class="form-control select-custom-2 select-input-plantilla" data-input-editor="template1">
                                                    @foreach($plantillas as $t)
                                                        <option value="{{$t->id}}">{{$t->nombre}}</option>
                                                    @endforeach
                                                </select>
                                                <textarea placeholder="" class="form-control" name="template1" id="template1" cols="30" rows="5" data-input-count="counter1">{{$plantilla["html1"]}}</textarea>
                                                <div class="col alert alert-light border-0 mb-0 text-right" id="counter1">
                                                    caracteres
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="badge badge-success even-larger-badge">PLANTILLA EXTRA</div>
                                            </div>
                                            <div class="col-sm-12">
                                                <select name="select2" id="select2" class="form-control select-custom-2 select-input-plantilla" data-input-editor="template2">
                                                    @foreach($plantillas as $t)
                                                        <option value="{{$t->id}}">{{$t->nombre}}</option>
                                                    @endforeach
                                                </select>
                                                <textarea placeholder="" class="form-control" name="template2" id="template2" cols="30" rows="5" data-input-count="counter2">{{ $plantilla["html2"] }}</textarea>
                                                <div class="col alert alert-light border-0 mb-0 text-right" id="counter2">
                                                    caracteres
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="badge badge-success even-larger-badge">PLANTILLA GAFETE</div>
                                            </div>
                                            <div class="col-sm-12">
                                                <select name="select3" id="select3" class="form-control select-custom-2 select-input-plantilla" data-input-editor="template3">
                                                    @foreach($plantillas as $t)
                                                        <option value="{{$t->id}}">{{$t->nombre}}</option>
                                                    @endforeach
                                                </select>
                                                <textarea placeholder="" class="form-control" name="template3" id="template3" cols="30" rows="5" data-input-count="counter3">{{ $plantilla["html3"] }}</textarea>
                                                <div class="col alert alert-light border-0 mb-0 text-right" id="counter3">
                                                    caracteres
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center mt-4">
                                                <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Grabar</button>
                                                <a href="{{ route('modulos.index') }}" class="btn btn-light">Volver al listado</a>
                                            </div>

                                        </div>



                                        <div>

                                        </div>

                                    </form>
                                    @foreach($fields as $fk=>$fls)
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class=" badge badge-secondary even-larger-badge">{{$loop->first?"Formulario":"Inscripcion"}}</div>
                                        </div>
                                        @foreach($fls as $k=>$v)
                                        <div class="col-sm-12 col-md-6 col-xl-4 p-1">
                                            <div>
                                                <span for="slug" class=" badge badge-primary">{{$k}}</span>
                                                <span class="text-small pl-3">{{$v}}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class=" badge badge-secondary even-larger-badge">OTROS</div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-xl-4 p-1">
                                            <div>
                                                <span for="slug" class=" badge badge-primary">nombres</span>
                                                <span class="text-small pl-3">Nombre ApellidoPaterno ApellidoMaterno</span>
                                            </div>
                                        </div>
                                    </div>


                                    @if (session('alert'))
                                        <div class="alert alert-success">
                                            {{ session('alert') }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>


                    @include('email.view_html.view_html')


                </div>


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
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .note-editable b, .note-editable strong { font-weight: bold; }
        .badge.even-larger-badge {
            font-size: 1.1em;
        }
    </style>
@endpush
@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-es-ES.js"></script>

    <script>
        var $gafete;
        $('document').ready(function(){
            actualizarEditorSN("#template1");
            actualizarEditorSN("#template2");
            actualizarEditorSN("#template3");

            var $sc = $('.select-custom-2');
            $gafete = $('#gafete');
            var $input_plantilla = $('.select-input-plantilla');
            var $link_plantilla = $('.select-link-plantilla');
            if($sc.length){
                $sc.select2();
            }
            if($input_plantilla.length){
                $input_plantilla.on('change', function(){
                    var editorId = $(this).data("input-editor");
                    var id = $(this).val();
                    loadEditorSelect(id, editorId);
                });
            }
            $gafete.on("change", seleccionaGafete);
            seleccionaGafete();
        });
        function seleccionaGafete(){
            var hg = $gafete.val()||"NO";
            console.log(hg)
            var cc = (hg && hg=="SI");
            $("#select3").prop("disabled", !(cc));
            $('#template3').summernote(!(cc)?'disable':'enable');
            if(!cc){
                $("#select3").val();
                $('#template3').summernote('code', "");
            }
            //$("#template3").val("").prop("disabled", hg!="SI");
        }
        function loadEditorSelect(id, editorId){
            var $template = $("#"+editorId);

            $template.summernote('code', "");
            if(id){
                $.getJSON("/m/template/"+id, function(v){
                    if(v && v.success){
                        $template.summernote('code', v.data.plantillahtml);
                    }
                });
            }
        }
        function actualizarEditorSN(s){
            var $s = $(s);
            var vv = $s.val();
            var p = {
                height:400,                 // set editor height
                minHeight: 200,             // set minimum height of editor
                maxHeight: 600,
                placeholder: '(Vacío)',
                tabsize: 2,
                toolbar: [
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['forecolor', ['forecolor']],
                    ['view', ['fullscreen', 'codeview']]//, 'help'
                ],
                lang: 'es-ES' // default: 'en-US'
            }
            var input_count = $s.data("input-count")||""

            if(input_count!=""){
                p["callbacks"] = {
                    onKeyup: actualizaCount,
                    onChange: actualizaCount,
                }
            }
            $s.summernote(p);
            $s.summernote('code', vv);
        }
        function actualizaCount(e){
            var $this = $(this);
            var input_count = $(this).data("input-count");
            if(input_count){
                setTimeout(function(){
                    var t = $this.val().length;
                    var ts = t==1?"":"s";
                    $("#"+input_count).html(t+" character"+ts);
                },200);
            }
        }
    </script>

@endsection
