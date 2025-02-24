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
                                    <h4 class="card-title text-transform-none">Nuevo Modulo</h4>
                                    @if (session('alert'))
                                        <div class="alert alert-success">
                                            {{ session('alert') }}
                                        </div>
                                    @endif
                                    <form class="forms-sample pr-4 pl-4" action="{{ route('modulos.store') }}" method="post" id="formModulos">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="id" value="{{ old('id', $data["id"], 0) }}" id="inpid">
                                        <input type="hidden" id="datar" name="datar">
                                        <input type="hidden" id="datai" name="datai">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-xl-21 col-form-label d-block">Evento <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="name" id="name" placeholder="Nombre del modulo *" value="{{ old('name', $data["name"]) }}" />
                                            </div>
                                            <label for="slug" class="col-sm-20 col-xl-2 col-form-label text-right d-block">Label <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="slug" id="slug" placeholder="Slug..." value="{{ old('slug', $data["slug"]) }}" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-2 col-form-label d-block">Descripción</label>
                                            <div class="col-sm-10">
                                                <textarea placeholder="Descripción" class="form-control" name="description" id="description" cols="30" rows="5">{{ old('description', $data['description']) }}</textarea>
                                                <div class="col alert alert-light border-0 mb-0 text-right">
                                                    5,000 caracteres
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center mt-4">
                                                <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Grabar</button>
                                                <a href="{{ route('modulos.index') }}" class="btn btn-light">Volver al listado</a>
                                            </div>

                                        </div>


                                        <div class="stretch-card">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                    <h4 class="card-title col-sm-12 col-md-6">Formulario</h4>
                                                        @if(count($m_categories)>0)
                                                            <select class="col-sm-12 col-md-6" id="m_category_id">
                                                                <option value="" disabled selected>Copiar campos desde...</option>
                                                                @foreach($m_categories as $id =>$name)
                                                                    <option value="{{ $id }}">{{$name}}</option>
                                                                @endforeach

                                                            </select>
                                                        @endif
                                                    </div>
                                                    <ul class="nav nav-tabs tab-solid tab-solid-danger" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="tab-5-1" data-toggle="tab" href="#home-5-1" role="tab" aria-controls="home-5-1" aria-selected="true">Encabezado de Formulario</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tab-5-2" data-toggle="tab" href="#profile-5-2" role="tab" aria-controls="profile-5-2" aria-selected="false">Campos de Formulario</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content tab-content-solid">
                                                        <div class="tab-pane fade active show" id="home-5-1" role="tabpanel" aria-labelledby="tab-5-1">

                                                            <div class="pb-1 flex text-right mb-2">
{{--                                                                <h3>CAMPOS DE ENCABEZADO DE FORMULARIO</h3>--}}
                                                                <button type="button" class="btn btn-primary add-field btn-sm" data-toggle="modal" data-target="#ModalForm" id="btnModal" data-is-detail="0">
                                                                    <i class="mdi mdi-plus"></i> Nuevo Campo
                                                                </button>
                                                            </div>
                                                            <div id="inputs" class="pr-2"></div>

                                                        </div>
                                                        <div class="tab-pane fade" id="profile-5-2" role="tabpanel" aria-labelledby="tab-5-2">

                                                            <div class="pb-1 flex text-right mb-2">
{{--                                                                <h3>CAMPOS DE DETALLE DE FORMULARIO</h3>--}}
                                                                <button type="button" class="btn btn-primary add-field btn-sm" data-toggle="modal" data-target="#ModalForm" id="btnModal" data-is-detail="1">
                                                                    <i class="mdi mdi-plus"></i> Nuevo Campo
                                                                </button>
                                                            </div>
                                                            <div id="inputs2" class="row"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-6">

                                            </div>
                                            <div class="col-6">

                                            </div>
                                        </div>

                                        <div>

                                        </div>

                                    </form>

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

@include('modulos.form-modal')

@endsection
@section('scripts')
    <style>
        .hidden_email, .hidden_whatsapp{display: none;}
        .ghostClass, #inputs >div:hover, #inputs2 >div:hover{
            background-color: rgba(230,255,15,0.2);
            padding: 5px 2px;
        }
        .ghostClass{
            opacity: 0.7;
        }
        input.largerCheckbox {
            width: 25px;
            height: 25px;
        }
        input[type=checkbox] {
            margin-top: 0;
            vertical-align: middle;
        }
        .modal-body{
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        e-relative {
            white-space: nowrap;
            position: relative;
        }
        .right-icon-pos {
            position: absolute;
            right: 15px;
        }
        .left-icon-pos {
            position: absolute;
            left: 5px;
        }
        .f-input {
            cursor: move;
        }
        .ghostClass{

        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>


    <script>
        //        $opts = ["Manual", "Tipo de documento", "Grupo","Pais", "Departamento","Provincia","Correos","Dominios"];

        var tabID = sessionStorage.tabID &&
        sessionStorage.closedLastTab !== '2' ?
            sessionStorage.tabID :
            sessionStorage.tabID = Math.random();
        sessionStorage.closedLastTab = '2';
        $(window).on('unload beforeunload', function() {
            sessionStorage.closedLastTab = '1';
        });
        console.log(sessionStorage);
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
        var indexlel = 1;
        var oinputs = {};
        var oidputs = {};
        var $m_category_id;
        $('document').ready(function(){
            $inputs = $("#inputs");
            $inputs2 = $("#inputs2");
            $btnModal = $("#btnModal");
            $cbfields = $("#cbfields");
            $fvselect = $("#fvselect");
            $fopt = $("#fopt");

            $ftext = $(".f-text");
            $ftexts = $(".f-text>div");
            $fselect = $(".f-select");
            $flatRadios1 = $("#flatRadios1");
            $flatRadios2 = $("#flatRadios2");
            $btnAddField2 = $("#btnAddField2");

            $fMaxlength = $("#fMaxlength");
            $fPlaceholder = $("#fPlaceholder");
            $fEditor = $("#fEditor");
            $fMin = $("#fMin");
            $fMax = $("#fMax");
            $fStep = $("#fStep");
            $fDominio = $("#fDominio");
            $fGrupo = $("#fGrupo");
            $fFormato = $("#fFormato");
            $fAccept = $("#fAccept");
            $fSize = $("#fSize");
            $fEfecto = $("#fEfecto");
            $fCond = $("#fCond");
            $campos2 = $("#campos2");
            $fcampos = $("#fcampos");
            $m_category_id = $("#m_category_id");
            if($m_category_id){
                $m_category_id.on('change', inicia)
            }

            function inicia(){
                recs = [];
                ins = [];
                $inputs.empty();
                $inputs2.empty();
                indexlel = 1;
                inp_index = 0;
                type_id_old = 0
                oinputs = {};
                oidputs = {};
                var m_category_id = parseInt( {!!  count($m_categories)>0? '$m_category_id': '$("#inpid")' !!}.val() || "0");
                //"{{route("modulos.inputs", $data["id"])}}"
                console.log("{{route("modulos.index")}}"+"/inputs/"+m_category_id);
                $.getJSON("{{route("modulos.index")}}"+"/inputs/"+m_category_id, function(d){
                    recs = d.recs;
                    ins = d.ins;
                    doctypes = d.doctypes;
                    groups = d.groups;
                    countries = d.countries;
                    emails = d.emails;
                    domains = d.domains;
                    departments = d.departments;
                    refreshLista(recs, $inputs);
                    refreshLista(ins, $inputs2);
                    refreshFList(recs, $inputs);
                    refreshFList(ins, $inputs2);
                    //REV//var ddd = getIndexTag("23");var data = ddd.data[ddd.index]
                });
            }

            function getTagIndex(d, i){
                var $inp = d[i].is_detail?$inputs2:$inputs;
                return parseInt($($inp.children()[i]).attr("tag"));
            }
            function getIndexTag(tag){
                var $inp = $("#input--"+tag);
                var $parent = $inp.parent();
                var xid = $parent.attr("id");
                var d = xid=="inputs2"?ins:recs;
                var index = $("#"+xid+">div").index($inp);
                return {index, data: d};
            }
            function loadFilter($input, v){
                var vv = [];
                for(i in v) {
                    var $ob = $input.find('option[value="'+v[i]+'"]');
                    if($ob.length){
                        //$ob.prop("selected", "selected");
                        vv.push(v[i]);
                    }
                }
                $input.val(vv).trigger("change");
                $input.select2();
            }
            function generateSelect($select, d, ignoreIndex, k, v){
                $select.empty();
                for(i in d) {
                    if (i != ignoreIndex){
                        var xtg = getTagIndex(d, i);
                        $("<option></option>").attr("value", xtg).text(d[i][v]).appendTo($select);//d[i][k]
                    }
                }
                $select.select2();
            }

            function verifyCValue(d, idx, p, v){
                console.log(d);
                console.log(idx);
                console.log(p);
                console.log(v);
                for(i in d){
                    if(i != idx )
                        if(d[i][p] == v)
                            return true;
                }
                return false;
            }

            function seleccionaRadio(){
                $(".f-select-1, .f-select-2").css("display", "none");
                if($flatRadios1.prop("checked"))$(".f-select-1").css("display", "");
                if($flatRadios2.prop("checked"))$(".f-select-2").css("display", "");
            }
            $flatRadios1.on("click", seleccionaRadio);
            $flatRadios2.on("click", seleccionaRadio);


            function verifyElements(type_id){
                return [11, 12, 13, 14].includes(type_id);
            }

            function fvSelect(){
                var type_id = parseInt($("#ftype").val() || "0");
                //$fvselect.addClass("d-none");
                // var e1 = verifyElements(type_id);
                // var e2 = verifyElements(type_id_old);

                // if(!e1 || !e2)$cbfields.empty();
                // if (e1){
                //     //$fvselect.removeClass("d-none");
                //     if(!e2)$fopt.val("0");
                //     $fopt.trigger("change");
                // }
                type_id_old = type_id;

                $ftexts.css("display", "none");
                $(".f-"+type_id).css("display","");
                $fselect.css("display", type_id>10 && type_id < 15 ? "": "none");

                $flatRadios1.prop("checked", true);

                $(".f-select-1, .f-select-2").css("display", "none");
                seleccionaRadio();
            }
            $("#ftype").on("change", fvSelect);
            // $fopt.on("change", function(){
            //     $sel = $(".t-field");
            //     if($sel.length==0) {
            //         addField(this.selectedIndex, {index: lastIndex()+1, value: "holaa"})
            //     }
            // });
            function addField(i, opt){
                var h = '';
                if(i==0)
                    h = `@include("modulos.modal.manual")`;
                else
                    h = `@include("modulos.modal.combo")`;
                $cbfields.append($(h));
                // $fopt.prop("disabled", true);
            }
            function addFieldv2(opt){
                var h = `@include("modulos.modal.inputs2")`;
                $cbfields.append($(h));
            }
            function getText(opt){
                opt.req = opt.required == 1?"*":"";
                console.log(opt.is_detail);
                @foreach($fields as $f)
                    @if( View::exists('modulos.inputs.'.$f->id ))
                        if( opt.m_field_id == {{$f->id}} ) return opt.is_detail != 1 ? `@include('modulos.inputs.'.$f->id)`: `@include('modulos.inputs.'.$f->id, ["js_form"=>1])`;
                    @endif
                @endforeach
                return opt.is_detail != 1 ? `@include("modulos.inputs.1")` : `@include('modulos.inputs.1', ["js_form"=>1])`;
            }
            function resetForm(){
                loadField(emptyField());
            }
            function sortPosition(d){
                return d.sort(function(a, b) {
                    var keyA = a.position,
                        keyB = b.position;
                    if (keyA < keyB) return -1;
                    if (keyA > keyB) return 1;
                    return 0;
                });
            }

            function getMaxPosition(data){
                var m = 0;
                for (let [k, v] of Object.entries(data))
                    if (v.position > m) m = v.position;
                return m;
            }
            function getExistsPosition(data, pos){
                for (let [k, v] of Object.entries(data))
                    if (v.position == pos) return true;
                return false;
            }
            function disabledFields(data, exclude=0){
                $(`#ffield option`).attr("disabled", false);
                for (let [k, v] of Object.entries(data))
                    if(v.m_attr_id!=exclude)$(`#ffield option[value="${v.m_attr_id}"]`).attr("disabled", true);
                return false;
                //d[index]. = $("#ffield").val();
            }

            $("#btnEliminar").on('click', function(){
                var index = parseInt($("#findex").val());
                var is_detail = $("#is_detail").val() == 1;
                var $sel = is_detail?$inputs2:$inputs;
                if (is_detail )
                    ins.splice(index,1);
                else
                    recs.splice(index,1);
                $sel.children("div").eq(index).remove();
                $('#ModalForm').modal('toggle');
            });

            function setPickers(){
                $sel = $('.datepicker-input');
                if ($sel.length)
                    $sel.datepicker({
                        enableOnReadonly: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy'
                    });
                $sel = $('.timepicker-input');
                if ($sel.length)
                    $sel.timepicker();
            }


            function generateInputHML(opt, index, tag){
                var $btnEditar = $('<div class="col-sm-12 text-right"><button type="button" class="border-0 x-editar" tag="'+index+'"><i class="mdi mdi-pencil"></i></button></div>');
                var $o = $(getText(opt)).append($btnEditar).addClass("e-relative").addClass("f-input");
                var x = (tag == -1) ? indexlel: tag;
                if(tag == -1)indexlel++;
                $o.attr("tag", x).attr("id", "input--" + x);
                return $o;
            }

            function refreshFList(data, $sel){
                for(i in data){
                    var v = data[i];
                    var opt = v.opt ? JSON.parse(v.opt) : {};
                    var flt = opt.flt||[];
                    var xflt = flt.c||[];
                    var xv2 = opt.v2||[];
                    if(xflt.length>0 || xv2.length > 0){
                        var xids = [];
                        var xids2 = [];
                        for(j in xflt) xids.push(oidputs["_"+xflt[j]]);
                        for(j in xv2) xids2.push(oidputs["_"+xv2[j]]);
                        if(xids.length>0)opt.flt.c = xids;
                        if(xids2.length>0)opt.v2 = xids2;
                        data[i].opt = JSON.stringify(opt);
                    }
                }
            }

            function refreshLista(data, $sel){
                $.each(data, function(index, v){
                    inp_index++;
                    oinputs[index+"-"+v.is_detail] = inp_index;
                    oidputs[ "_" + (v.id||0)] = inp_index;
                    var opt = {
                        "id": inp_index,//index,
                        "name": v.name,
                        "title": v.title,
                        "subtitle": v.subtitle,
                        "value": v.value,
                        "note": v.note,
                        "required": v.required,
                        "visible": v.visible,
                        "opt": v.opt,
                        "m_field_id": v.m_field_id,
                        "is_detail": v.is_detail,
                    }
                    // $sel.append($(getText(opt)));
                    $sel.append(generateInputHML(opt, inp_index, -1));
                    loadCombo($("#inp-"+inp_index), opt);
                })
                setPickers()
                var opts ={ animation: 150,ghostClass: 'ghostClass', swap: true, onEnd: function(e){
                        var oldIndex = e.oldIndex;
                        var newIndex = e.newIndex;
                        var id = e.to.id;
                        if (oldIndex != newIndex){
                            var o1 = data[oldIndex];
                            var o2 = data[newIndex];
                            var p1 = o1.position;
                            var p2 = o2.position;
                            o1.position = p2;
                            o2.position = p1;
                            data[oldIndex] = o2;
                            data[newIndex] = o1;
                        }
                    }
                }
                $sel.sortable(opts);
            }

            function setValueD(d, index){
                if(typeof d[index] === 'undefined')d[index]={};
                d[index].m_attr_id = parseInt($("#ffield option:selected").attr("value") ||0);
                d[index].id = $("#fid").val();
                d[index].m_field_id = parseInt($("#ftype").val());
                d[index].name = $("#fname").val();
                d[index].title = $("#ftitle").val();
                d[index].subtitle = $("#fsubtitle").val();
                d[index].note = $("#fnote").val();
                d[index].position = parseInt($("#fposition").val());
                d[index].is_detail = $("#is_detail").val()!=1?0:1;
                d[index].required = $("#frequired").prop("checked")?1:0;
                d[index].visible = $("#fvisible").prop("checked")?1:0;
                d[index].value = $("#fvalue").val();
                d[index].opt = JSON.stringify(generateOpt());
                d[index].style = JSON.stringify({"c": $("#fcolor").val(), "s":$("#fsize").val(), "f":$("#ffont").val()});
            }
            function getOpt(){
                if($flatRadios1.prop("checked"))return 1;
                if($flatRadios2.prop("checked"))return 2;
                return 0;
            }
            function generateOpt(){
                var xopts = {
                }
                // var fop = parseInt($fopt.val() || "0");
                var type_id = parseInt($("#ftype").val() || "0");

                var fop = verifyElements(type_id) ? getOpt(): 0;
                var tfields = $(".t-field");
                if([1, 2, 3, 4].includes(type_id))xopts["ml"] = $fMaxlength.val();
                if([1, 2, 3, 4, 7, 8, 9].includes(type_id))xopts["ph"] = $fPlaceholder.val();
                if([7, 8, 9].includes(type_id))xopts["f"] = $fFormato.val();
                if(type_id==2)xopts["ed"] = $fEditor.val();
                if(type_id==4)xopts["dm"] = $fDominio.prop("checked")?1:0;
                if(type_id==5)xopts["gr"] = $fGrupo.val();
                if(type_id==3) {
                    xopts["max"] = $fMax.val();
                    xopts["min"] = $fMin.val();
                    xopts["st"] = $fStep.val();
                }
                if(type_id==15) {
                    xopts["acc"] = $fAccept.val();
                    xopts["sz"] = $fSize.val();
                }
                if(fop > 0){
                    if(verifyElements(type_id) && fop==1){
                        var xvals = []
                        $.each(tfields, function(v, k){
                            var ii = parseInt(this.id.substring(3))
                            var c = "", d = "";
                            var a = $("#fvname-"+ii).val();
                            var b = $("#fvvalue-"+ii).val();
                            xvals.push([a,b,c,d]);
                        });
                        xopts["v"] = xvals;
                    }
                    xopts["t"] = $("#fopt").val();
                    if(fop==2){
                        xopts["v2"] = $fcampos.val() || [];
                    }
                }
                xopts["flt"] = {
                    "f": $fCond.val(),
                    "e": $fEfecto.val(),
                    "c": $campos2.val(),
                }
                return xopts;
            }
            $("#formField").on("submit",function(e){
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
                    "subtitle": $("#fsubtitle").val(),
                    "value": $("#fvalue").val(),
                    "note": $("#fnote").val(),
                    "m_field_id": m_field_id,
                    "m_attr_id": parseInt($("#ffield option:selected").attr("value") || "0"),//$("#ffield").val(),
                    "required": $("#frequired").prop("checked")?1:0,
                    "visible": $("#fvisible").prop("checked")?1:0,
                    "opt": JSON.stringify(generateOpt()),
                }
                var val = $("#campos2").val();
                if(verifyCValue(d, index, "name", $("#fname").val())){
                    swal("Advertencia", "El nombre existe", "warning").then(function() {
                        swal.close();
                        $("#fposition").focus().select();
                    });
                    return false;
                }
                if(verifyCValue(d, index, "title", $("#ftitle").val())){
                    swal("Advertencia", "El titulo existe", "warning").then(function() {
                        swal.close();
                        $("#fposition").focus().select();
                    });
                    return false;
                }
                if (index==-1){
                    opt.id = 0;
                    //$inp.append($(getText(opt)));
                    $inp.append(generateInputHML(opt, 0, -1));
                    index = d.length;
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
                e.preventDefault();
                return false;
                //
            });
            $("#btn-cerrar").on('click', function(e){
                $('#ModalForm').modal('toggle');
                e.preventDefault();
                return false;
            });
            function emptyField(){
                return {
                    "id": 0,
                    "m_attr_id": 0,
                    "m_field_id": 0,
                    "name": "",
                    "title": "",
                    "subtitle": "",
                    "note": "",
                    "position": 1,
                    "is_detail": 0,
                    "required": 0,
                    "visible": 0,
                    "index": 0,
                    "style": JSON.stringify({"c":"", "s":"", "f":""}),
                    "value":"",
                    "opt": "{}"
                }
            }

            function loadField(v){

                var fid = v.id || 0;
                $("#fid").val(fid);
                $("#ffield").val(v.m_attr_id);
                $("#ftype").val(v.m_field_id);
                $("#fname").val(v.name);
                $("#ftitle").val(v.title);
                $("#fsubtitle").val(v.subtitle);
                $("#fnote").val(v.note);
                $("#fposition").val(v.position);
                $("#is_detail").val(v.is_detail);
                $("#frequired").prop("checked",v.required);
                $("#fvisible").prop("checked",v.visible);
                $("#findex").val(v.index);
                $("#fvalue").val(v.value);
                var st = v.style ? JSON.parse(v.style) : {};
                $("#fsize").val(st.s || "");
                $("#fcolor").val(st.c || "");
                $("#ffont").val(st.f || "");
                var sopt = v.opt ? JSON.parse(v.opt) : {};

                $("#ftype").trigger('change');

                if(verifyElements(v.m_field_id)){
                    //$("#ftype").trigger('change');
                    console.log(sopt);
                    $fopt.val(sopt.t);
                    //$fopt.trigger('change');
                    $flatRadios1.prop("checked", sopt.t == 0);
                    $flatRadios2.prop("checked", sopt.t > 0);
                    seleccionaRadio();
                    var vv = sopt.v || [];
                    $cbfields.empty();
                    for(var i=0;i<vv.length;i++){
                        var tt = sopt.t;
                        var j = i+1;
                        addFieldv2({index:j, value:""});
                        var a = vv[i][0];
                        var b = vv[i][1];
                        var c= vv[i][2];
                        var d = vv[i][3];
                        if(tt==0){
                            $("#fvname-"+j).val(a);
                            $("#fvvalue-"+j).val(b);
                        }else{
                            $("#fvfield-"+j).val(c);
                        }
                        $("#fvcheck-"+j).prop("checked", d == 1);
                    }
                }
                var flt = sopt.flt || {};//f d c
                var flt_f = flt.f || "";
                var flt_e = flt.e || "";
                var flt_c = flt.c || [];
                var flt_v2 = sopt.v2 || [];
                loadFilter($campos2, flt_c);
                loadFilter($fcampos, flt_v2);
                $fCond.val(flt_f);
                $fEfecto.val(flt_e);
                //cargar otros datos
                if([1, 2, 3, 4].includes(v.m_field_id))$fMaxlength.val(sopt["ml"]||"");
                if([1, 2, 3, 4, 7, 8, 9].includes(v.m_field_id))$fPlaceholder.val(sopt["ph"] || "");
                if([7, 8, 9].includes(v.m_field_id))$fFormato.val(sopt["f"] || "");
                if(v.m_field_id==2)$fEditor.val(sopt["ed"] || 0);
                if(v.m_field_id==4)$fDominio.prop("checked", sopt["dm"] && sopt["dm"] == 1);
                if(v.m_field_id==5)$fGrupo.val(sopt["gr"] || "");
                if(v.m_field_id==3) {
                    $fMax.val(sopt["max"] || "");
                    $fMin.val(sopt["min"] || "");
                    $fStep.val(sopt["st"] || "");
                }
                if(v.m_field_id==15) {
                    $fAccept.val(sopt["acc"] || "");
                    $fSize.val(sopt["sz"] || "");
                }
            }

            function loadForm(index, data){
                resetForm();
                var v = data[index];
                v.index = index
                loadField(v);
            }
            function lastIndex(){
                var $tfields = $(".t-field");
                var  nn = $tfields.length
                var ii = nn > 0 ? parseInt($tfields[nn-1].id.substring(3)) : 0;
                return ii;
            }
            $( document ).on( "click", "#btnAddField", function(event) {
                var fopt = parseInt($fopt.val() || "0");
                addField(fopt>0?1:0 ,{index:lastIndex()+1, value:"holaa"})
            });
            $( document ).on( "click", "#btnAddField2", function(event) {
                addFieldv2({index:lastIndex()+1, value:""})
            });
            $( document ).on( "click", ".t-field button.btn-danger", function(event) {
                var i = $(this).attr("tag");
                $("#fv-"+i).remove();
            });
            $( document ).on( "click", ".add-field", function(event) {
                var $this = $(this);
                var is_detail = $this.data("is-detail");
                resetForm();
                $("#is_detail").val(is_detail);
                d=is_detail==1?ins:recs;
                $("#fposition").val(getMaxPosition(d)+1);
                disabledFields(d,0);
                $(`#ffield option`).removeClass("d-none");
                $(`#ffield option[tag="${is_detail==0?1:0}"]`).addClass("d-none");
                $("#findex").val("-1");
                $("#fid").val("0");
                $("#btnEliminar").css("display", "none");
                fvSelect();
                $("#fcolor").val("#000000");
                $("#fsize").val("11");
                $("#ffont").val("Arial");

                generateSelect( $("#campos2"),d, -1, "name", "title");
                generateSelect( $("#fcampos"),d, -1, "name", "title");

            });


            $( document ).on( "click", ".x-editar", function(event) {
                event.stopPropagation();
                var $this = $(this);
                var $parent1 = $this.parents(".f-input");
                var $parent2 = $parent1.parent();
                var xid = $parent2.attr("id");
                var d = xid=="inputs2"?ins:recs;
                $('.add-field').eq(xid=='inputs2'?1:0).trigger('click');
                console.log("#"+xid+">div");
                console.log($parent1);
                console.log($parent2);
                var index = $("#"+xid+">div").index($parent1);

                generateSelect( $("#campos2"),d, index, "name", "title");
                generateSelect( $("#fcampos"),d, -1, "name", "title");

                loadForm(index, d);
                $("#btnEliminar").css("display", "");
                disabledFields(d,index);

                console.log(d);

            });
            // $( document ).on( "dblclick", "#inputs>div,#inputs2>div", function(event) {
            //     event.stopPropagation();
            //     $this = $(this);
            //     var $parent = $this.parent();
            //     var xid = $parent.attr("id");
            //     var d = xid=="inputs2"?ins:recs;
            //     $('.add-field').eq(xid=='inputs2'?1:0).trigger('click');
            //     var index = $("#"+xid+">div").index($this);
            //     loadForm(index, d);
            //     $("#btnEliminar").css("display", "");
            //     disabledFields(d,index);
            // });


            $("#formModulos").on("submit", function(e){
                e.preventDefault();
                for(i in recs)
                    recs[i]["tag"] = getTagIndex(recs, i);
                for(i in ins)
                    ins[i]["tag"] = getTagIndex(ins, i);
                $("#datar").val(JSON.stringify(recs));
                $("#datai").val(JSON.stringify(ins));
                this.submit();
                return false;
            });

            function createLRadio($selector, id, index, key, value){
                $(`<div class="form-check">
                                    <input type="radio" id="inp-${id}-o-${index}" name="inputs[${id}]" class="form-check-input check_click" value="${key}">
                                        <label class="form-check-label" for="inp-${id}-o-${index}">${value} <i class="input-helper"></i></label>
                                </div>`).appendTo($selector)
            }
            function createLCheck($selector, id, index, key, value){
                $(`<div class="form-check">
                                    <input type="checkbox" id="inp-${id}-o-${index}" name="inputs[${id}]" class="form-check-input check_click" value="${key}">
                                        <label class="form-check-label" for="inp-${id}-o-${index}">${value} <i class="input-helper"></i></label>
                                </div>`).appendTo($selector)
            }
            function loadCombo($select, v){
                var m_field_id = v.m_field_id;
                if($select.is("select")||verifyElements(m_field_id)){
                    $select.empty();
                    if (m_field_id == 11)$select.append('<option value="" disabled selected></option>');
                    if(verifyElements(m_field_id)){
                        o = JSON.parse(v.opt)
                        t = o.t || 0;
                        d = [];
                        if ( t == 0){
                            d = o.v ||[];
                            $.each(d,function(ii,vv){
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv[1]).text(vv[0]).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv[1], vv[0]);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv[1], vv[0]);

                            });
                        }
                        if (t == 1 || t == 2){
                            d = t == 1 ? doctypes : groups;
                            $.each(d,function(ii,vv){
                                //$("<option></option>").attr("value", vv.id).text(vv.name).appendTo($select);
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv.id).text(vv.name).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv.id, vv.name);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv.id, vv.name);
                            });
                        }
                        if (t == 3){//PAIS
                            d = countries;
                            $.each(d,function(ii,vv){
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv.name).text(vv.name).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv.name, vv.name);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv.name, vv.name);
                            });
                        }
                        if (t == 4){//DEPARTMENT
                            d = departments;
                            $.each(d,function(ii,vv){
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv.nombre).text(vv.nombre).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv.nombre, vv.nombre);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv.nombre, vv.nombre);
                            });
                        }
                        if (t == 6){//DOMAINS
                            d = domains;
                            $.each(d,function(ii,vv){
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv.id).text(vv.domain).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv.id, vv.domain);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv.id, vv.domain);
                            });
                        }
                        if (t == 7){//EMAILS
                            d = emails;
                            $.each(d,function(ii,vv){
                                if ([11, 12,].includes(m_field_id))$("<option></option>").attr("value", vv.id).text(vv.name + " - " + vv.email).appendTo($select);
                                if (m_field_id == 13)createLRadio($select, v.id, ii, vv.id, vv.name + " - " + vv.email);
                                if (m_field_id == 14)createLCheck($select, v.id, ii, vv.id, vv.name + " - " + vv.email);
                            });
                        }
                    }
                }
            }
            $.asColorPicker.setDefaults();
            inicia();
        });
    </script>

@endsection
