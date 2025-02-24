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

                                    <h4 class="card-title text-transform-none">Creación de {{ $modulo->name }}</h4>

                                    @if (session('alert'))
                                        <div class="alert alert-success">
                                            {{ session('alert') }}
                                        </div>
                                    @endif


                                    <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('mcat.store', [$modulo->id]) }}" method="post" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="id" value="{{$m_product_id}}">
                                        <input type="hidden" name="inpr" id="inpr" value="">
                                        @php($expdata = $data["exp"])
                                        @php($btn=[])
                                        @foreach($recs as $v)
                                            @php($v->_value= $v->value)
                                            @php($v->value = array_key_exists($v->field, $product_data) ? $product_data[$v->field] :  (in_array($v->m_field_id, [12, 14])?[]:""))
                                            @php($v->values = in_array($v->m_field_id, [12, 14]) ? $v->value??[] : [$v->value])
                                            @php( $v->value = in_array($v->m_field_id, [2]) ? ($m_product_id == 0 ? $v->value : $v->getFileText($m_product_id, $v->id)): $v->value)

                                        @if(in_array($v->m_field_id, [5, 6, 16]))
                                                <div class="row" id="content-{{$v->id}}">
                                                    @if( View::exists('modulos.inputs.'.$v->m_field_id ))
                                                        @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v, "form"=>1])
                                                    @else
                                                        @include('modulos.inputs.html.1', ["input"=>$v, "form"=>1])
                                                    @endif
                                                </div>
                                            @elseif($v->m_field_id==20/*&& $v->name == '_grabar'*/)
                                                @php($btn = $v->toArray())
                                            @elseif($v->m_field_id==18)
                                            @else
                                                @if(isset($DF["_plantilla"]) && $v->field == $DF["_plantilla"])
                                                    @include("moduloscat.plantilla1")
                                                @else
                                                <div class="form-group row" id="content-{{$v->id}}">
                                                    <label for="inp-{{$v->id}}" class="col-sm-12 col-md-4 col-lg-2 col-form-label d-block">{{$v->title}} <span class="text-danger">{{$v->required?'*':''}}</span></label>
                                                    <div class="col-sm-10">
                                                        @if( View::exists('modulos.inputs.html.'.$v->m_field_id ))
                                                            @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v, "form"=>1])
                                                        @else
                                                            @include('modulos.inputs.html.1', ["input"=>$v, "form"=>1])
                                                        @endif
                                                        <input type="hidden" name="f[{{$v->id}}]" value="{{$v->field}}">
                                                        @include("moduloslead.form-note", ["note"=>$v->note])
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        @endforeach

                                        <div class="form-group row">
                                            <label for="auto_conf" class="col-sm-2 col-form-label">Campos</label>
                                            <div class="col-sm-10 bg-dark rounded text-white">
                                                <div class="form-check form-check-flat">
                                                    <div class="col-sm-10 form-check">
                                                        <label class="form-check-label">
                                                            <input id="chk-cat-all" type="checkbox" class="form-check-input" value="1">Seleccionar/Deseleccionar Todos<i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="auto_conf" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                @foreach($ins_chunk as $ins)
                                                    <div class="form-group row">
                                                        @foreach($ins as $v)
                                                            <div class="col-sm-4">
                                                                <div class="form-check">
                                                                    <div class="col-sm-10 form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input name="visibles[]" {{ in_array($v->id, $visible_data) ? 'checked="checked"': "" }} type="checkbox" class="form-check-input select-check-all" value="{{$v->id}}"> {{$v->title}} <i class="input-helper"></i><i class="input-helper"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center mt-4">
                                                @include("moduloscat.form-boton")

                                                <a href="{{ route('mcat.index', [$modulo->id]) }}" class="btn btn-light">Volver al listado</a>
                                            </div>
                                        </div>
                                    </form>

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
@php( $ins3 = $recs )
@include("modulos.js-import", compact("editors1", "data", "linesjs", "eventsjs", "requireds", "ins3"))
