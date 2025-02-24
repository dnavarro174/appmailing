@extends('layout.home')

@section('content')
    <div class="horizontal-menu bg_fondo" >
        <!-- partial:partials/_navbar.html -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- end menu_right -->
            <!-- partial -->

            <div class="main-panel">
                <div class="content-wrapper pt-0" style="background: none;">
                    <div class="container">
                        <div class="row justify-content-center">{{-- $datos->activo == 2 --}}
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <form class="forms-sample" id="leadForm" action="{{ route('mlead.store',  [$m_category_id, $m_product_id]) }}" method="post" enctype="multipart/form-data" autocomplete="on">

                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{$id}}">

                                    <div class="row ">
                                        <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                                            <div class="card">
                                                @if($header_img!="")<img src="{{ $header_img }}" alt="{{$product_data[$DF['_nombre']] }} {{date('Y')}}" class="img-fluid">@endif

                                                <!--card-img-top -->
                                                <div class="card-body">
                                                    @if($header_img!="")
                                                    <H1 class="card-title text-center mb-3" style="color: #dc3545;">{{$product_data[$DF['_nombre']] }}</H1>
                                                    @else
                                                    <H1 class="card-title text-center mb-3" style="{{ "color: ". $title["color"].";"." font-size:".$title["size"]."px; font-family:'".$title["font"]."'" }}">{{$title["text"] }}</H1>
                                                    @endif

                                                    <div class="row pb-3">
                                                        <div class="col-xs-12 col-sm-4">
                                                            <strong>Fecha:</strong> {{date('d-m-Y', strtotime($product_data[$DF["_fecha_ini"]])) }}
                                                        </div>
                                                        <div class="col-xs-12 col-sm-4">
                                                            <strong>Hora:</strong> {{$product_data[$DF["_hora_ini"]] }}
                                                        </div>
                                                        <div class="col-xs-12 col-sm-4">
                                                            <strong>Lugar:</strong> {{$product_data[$DF["_lugar"]]}}
                                                        </div>
                                                    </div>
                                                    <p>

                                                        {!! $product_data[$DF["_description"]] !!}
                                                    </p>

                                                    <p class="card-text">
                                                        <strong class="text-danger">* Campos obligatorios / Required fields</strong>
                                                    </p>

                                                    @if(Session::has('dni'))
                                                        <p class="alert alert-danger">{{ Session::get('dni') }}</p>
                                                    @endif
                                                    @if(Session::has('dni_registrado'))
                                                        <p class="alert alert-warning">{{ Session::get('dni_registrado') }}</p>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-sm-12 col-md-12  grid-margin stretch-card">

                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">Datos Personales / Personal Data</h4>

                                                    <div class="form-group row">
                                                        <div class="col-sm-12">

                                                            @if(count($errors)>0)

                                                                <div class="alert alert-danger">
                                                                    Error:<br>
                                                                    <ul>
                                                                        @foreach($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>

                                                            @endif
                                                        </div>

                                                    </div>

                                                    <div class=" form-group row">
                                                        @php($expdata = $data["exp"])
                                                        @php($btn=[])
                                                        @foreach($ins3 as $v)
                                                            @php($cls = $v->is_fullsize !=1 ? 'col-md-4': 'clearfix')
                                                            @php($v->_value= $v->value)

                                                            @php($field_text = $v->field."_t")
                                                            @php($v->_text = array_key_exists($field_text, $ins_data) && $ins_data[$field_text] != "" ? $ins_data[$field_text] : ""  )
                                                            @if( in_array($v->id, $visible_data) )
                                                                @php($v->value = array_key_exists($v->field, $ins_data) ? $ins_data[$v->field] : (in_array($v->m_field_id, [12, 14])?[]:""))
                                                                @php($v->values = in_array($v->m_field_id, [12, 14]) ? is_array($v->value)?$v->value:[] : [$v->value])
                                                                @php( $v->value = in_array($v->m_field_id, [2]) ? $v->getFileText($m_product_id, $id): $v->value)

                                                            @if(in_array($v->m_field_id, [5, 6]))
                                                                    <div class="col-sm-12 {{$cls}}" id="content-{{$v->id}}">
                                                                        @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v])
                                                                    </div>
                                                                @elseif($v->m_field_id==20/* && $v->name == 'grabar'*/)
                                                                    @php($btn = $v->toArray())
                                                                @elseif($v->m_field_id==18)
                                                                    @php($exps=isset($v->value)?json_decode($v->value, true):[$expdata["emptyExp"]])
                                                                    @include('modulos.inputs.html.18')
                                                                @elseif($v->m_field_id==19)
                                                                    @php($exps=isset($v->value)?json_decode($v->value, true):[$expdata["emptyExp2"]])
                                                                    @include('modulos.inputs.html.19')
                                                                @else
                                                                    <div class="col-sm-12 {{$cls}}" id="content-{{$v->id}}">
                                                                        <div class="form-group ">
                                                                            @if($v->is_title_hidden!=1 && !in_array($v->m_field_id, [5,6]) )
                                                                                <label for="inp-{{$v->id}}">{{$v->title}}@if($v->required) <span class="text-danger">*</span>@endif</label>
                                                                            @endif
                                                                            @include('moduloslead.form-subtitle', ["subtitle"=>trim($v->subtitle)])

                                                                                @if(isset($DF["_plantilla"]) && $v->field == $DF["_plantilla"])
                                                                                    <select name="" id="" class="form-control select-custom-2 select-input-plantilla" data-input-editor="inp-{{$v->id}}">
                                                                                        @foreach($data["plantillas"] as $t)
                                                                                            <option value="{{$t->id}}">{{$t->nombre}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @endif

                                                                            @if( View::exists('modulos.inputs.'.$v->m_field_id ))
                                                                                @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v])
                                                                            @else
                                                                                @include('modulos.inputs.html.1', ["input"=>$v])
                                                                            @endif
                                                                            @include('moduloslead.form-note', ["note"=>trim($v->note)])
                                                                            <input type="hidden" name="f[{{$v->id}}]" class="input-id" value="{{$v->field}}" tag="{{$v->id}}">
                                                                             <input type="hidden" name="texts[{{$v->id}}]" id="htexts-{{$v->id}}" class="input-kei" value="{{$v->_text}}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="row">
                                                        @if(1)
                                                            <div class="col-sm-12 col-md-8">
                                                                <div class="form-check">
                                                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input check_click" required="">
                                                                    <label class="form-check-label" for="enc">
                                                                        He leído y acepto los <a href="#" onclick="eximForm()" data-toggle="modal">Término y Condiciones</a>

                                                                    </label>
                                                                    <span class="small" style="position: relative;right: -9px;">
                                    Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales
                                  </span>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group ">

                                                                @include("moduloslead.form-boton", ["boton_title"=>"ENVIAR / SEND"])
                                                                {{-- <div class="col-sm-12 pt-2">
                                                                    <div class="alert alert-warning mb-0 text-center" role="alert">
                                                                        (Cada registro es una entrada)
                                                                    </div>
                                                                </div> --}}
                                                                <div class="col-sm-12 col-md-12 p-0 mt-3 text-center">
                                                                    @if($footer_img!="")<img src="{{ $footer_img }}" alt="{{$product_data[$DF['_nombre']] }} {{date('Y')}}" class="img-fluid">@endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> {{-- end row --}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12  grid-margin stretch-card"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if(1)
                    @include('termino-condiciones.index')
                @endif

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

    {{-- <script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>
    <script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script> --}}

@endsection

@include("modulos.js-import", compact("editors1", "data", "linesjs", "eventsjs", "requireds", "ins3"))
