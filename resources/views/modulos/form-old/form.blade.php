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
                                        <input type="hidden" name="id" value="{{ old('id', $data["id"]) }}">
                                        <input type="hidden" id="datar" name="datar">
                                        <input type="hidden" id="datai" name="datai">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-xl-21 col-form-label d-block">Evento <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="name" id="name" placeholder="Nombre del modulo *" value="{{ old('name', $data["name"]) }}" />
                                            </div>
                                            <label for="label" class="col-sm-20 col-xl-2 col-form-label text-right d-block">Label <span class="text-danger">*</span></label>
                                            <div class="col-sm-10 col-xl-4">
                                                <input type="text" required="" class="form-control" name="label" id="label" placeholder="Label..." value="{{ old('label', $data["label"]) }}" />
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

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="p-3 flex">
                                                    <h3>CAMPOS DE ENCABEZADO DE FORMULARIO</h3>
                                                    <button type="button" class="btn btn-primary add-field" data-toggle="modal" data-target="#ModalForm" id="btnModal" data-is-detail="0">
                                                        Nuevo
                                                    </button>
                                                </div>
                                                <div id="inputs" class="pr-2"></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 flex">
                                                    <h3>CAMPOS DE DETALLE DE FORMULARIO</h3>
                                                    <button type="button" class="btn btn-primary add-field" data-toggle="modal" data-target="#ModalForm" id="btnModal" data-is-detail="1">
                                                        Nuevo
                                                    </button>
                                                </div>
                                                <div id="inputs2"></div>
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
        var $inputs, $inputs2, $btnModal, $cbfields, $fvselect, $fopt;
        var recs = [];
        var ins = [];
        type_id_old = 0
        var doctypes = [], groups = [], countries = [], departments = [], provinces = [], emails = [], domains = [];
        $('document').ready(function(){
            $inputs = $("#inputs");
            $inputs2 = $("#inputs2");
            $btnModal = $("#btnModal");
            $cbfields = $("#cbfields");
            $fvselect = $("#fvselect");
            $fopt = $("#fopt");

            function verifyElements(type_id){
                return [11, 12, 13, 14].includes(type_id);
            }

            function fvSelect(){
                var type_id = parseInt($("#ftype").val() || "0");
                $fvselect.addClass("d-none");
                var e1 = verifyElements(type_id);
                var e2 = verifyElements(type_id_old);

                if(!e1 || !e2)$cbfields.empty();
                if (e1){
                    $fvselect.removeClass("d-none");
                    if(!e2)$fopt.val("0");
                    $fopt.trigger("change");
                }
                type_id_old = type_id;
            }
            $("#ftype").on("change", fvSelect);
            $fopt.on("change", function(){
                $sel = $(".t-field");
                if($sel.length==0) {
                    addField(this.selectedIndex, {index: lastIndex()+1, value: "holaa"})
                }
            });
            function addField(i, opt){
                var h = '';
                if(i==0)
                    h = `@include("modulos.modal.manual")`;
                else
                    h = `@include("modulos.modal.combo")`;
                $cbfields.append($(h));
                $fopt.prop("disabled", true);
            }

            function getText(opt){
                opt.req = opt.required == 1?"*":"";
                @foreach($fields as $f)
                    @if( View::exists('modulos.inputs.'.$f->id ))
                        if( opt.m_field_id == {{$f->id}} ) return `@include('modulos.inputs.'.$f->id)`;
                    @endif
                @endforeach
                return `@include("modulos.inputs.1")`;
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


            function generateInputHML(opt, index){
                var $btnEditar = $('<button type="button" class="text-right right-icon-pos border-0 x-editar" tag="'+index+'"><i class="mdi mdi-pencil"></i></button>');
                return $(getText(opt)).prepend($btnEditar).addClass("e-relative");
            }

            function refreshLista(data, $sel){
                $.each(data, function(index, v){
                    inp_index++;
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
                    }
                    // $sel.append($(getText(opt)));
                    $sel.append(generateInputHML(opt, inp_index));
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
            function generateOpt(){
                var xopts = {
                }
                var fop = parseInt($fopt.val() || "0");
                var type_id = parseInt($("#ftype").val() || "0");
                var tfields = $(".t-field");
                if(verifyElements(type_id)){
                    var xvals = []
                    $.each(tfields, function(v, k){
                        var ii = parseInt(this.id.substring(3))
                        var a = "", b = "", c = "";
                        if(fop == 0){
                            a = $("#fvname-"+ii).val();
                            b = $("#fvvalue-"+ii).val();
                        }
                        else{
                            c =  $("#fvfield").val() || "";
                        }

                        var d = $("#fvcheck-"+ii).prop("checked")?1:0;
                        xvals.push([a,b,c,d]);
                    });
                    xopts["t"] = fop;
                    xopts["v"] = xvals;
                }
                return xopts;
            }
            $("#formField").on("submit",function(e){
                var index = parseInt($("#findex").val());
                var is_detail = $("#is_detail").val() == 1;
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

                var opt = {
                    "id": parseInt($("#fid").val() || "0"),
                    "name": $("#fname").val(),
                    "title": $("#ftitle").val(),
                    "subtitle": $("#fsubtitle").val(),
                    "value": $("#fvalue").val(),
                    "note": $("#fnote").val(),
                    "m_field_id": parseInt( $("#ftype").val() || "0"),
                    "m_attr_id": parseInt($("#ffield option:selected").attr("value") || "0"),//$("#ffield").val(),
                    "required": $("#frequired").prop("checked")?1:0,
                    "visible": $("#fvisible").prop("checked")?1:0,
                    "opt": JSON.stringify(generateOpt()),
                }
                if (index==-1){
                    opt.id = 0;
                    //$inp.append($(getText(opt)));
                    $inp.append(generateInputHML(opt, 0));
                    index = d.length;
                    setValueD(d, index);
                }else{
                    opt.id = parseInt(opt.id);
                    $($inp.children()[index]).replaceWith(generateInputHML(opt, opt.id));//$(getText(opt))
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
                if(verifyElements(v.m_field_id)){
                    $("#ftype").trigger('change');
                    $fopt.val(sopt.t);
                    $fopt.trigger('change');
                    var vv = sopt.v;
                    $cbfields.empty();
                    for(var i=0;i<vv.length;i++){
                        var tt = sopt.t;
                        var j = i+1;
                        addField(tt>0?1:0 ,{index:j, value:""});
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
            $( document ).on( "click", ".t-field button.btn-danger", function(event) {
                var i = $(this).attr("tag");
                $("#fv-"+i).remove();
                var n = $(".t-field").length;
                if(n==0){
                    $fopt.prop("disabled", false);
                }
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

            });


            $( document ).on( "click", ".x-editar", function(event) {
                event.stopPropagation();
                var $this = $(this);
                var $parent1 = $this.parent();
                var $parent2 = $parent1.parent();
                var xid = $parent2.attr("id");
                var d = xid=="inputs2"?ins:recs;
                $('.add-field').eq(xid=='inputs2'?1:0).trigger('click');
                var index = $("#"+xid+">div").index($parent1);
                loadForm(index, d);
                $("#btnEliminar").css("display", "");
                disabledFields(d,index);
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

            $.getJSON("{{route("modulos.inputs", $data["id"])}}", function(d){
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
            });
            $("#formModulos").on("submit", function(e){
                e.preventDefault();

                $("#datar").val(JSON.stringify(recs));
                $("#datai").val(JSON.stringify(ins));
                this.submit();
                return false;
            });
            function loadCombo($select, v){
                if($select.is("select")){
                    $select.empty();
                    $select.append('<option value="" disabled selected></option>');
                    if(v.m_field_id==11 || v.m_field_id == 12){
                        o = JSON.parse(v.opt)
                        t = o.t || 0;
                        d = [];
                        if (t==0){
                            d = o.v ||[];
                            $.each(d,function(ii,vv){
                                $("<option></option>").attr("value", vv[1]).text(vv[0]).appendTo($select);
                            });
                        }
                        if (t==1 || t==2){
                            d = t==1 ? doctypes : groups;
                            $.each(d,function(ii,vv){
                               $("<option></option>").attr("value", vv.id).text(vv.name).appendTo($select);
                            });
                        }
                        if (t==3){//PAIS
                            d = countries;
                            $.each(d,function(ii,vv){
                                $("<option></option>").attr("value", vv.name).text(vv.name).appendTo($select);
                            });
                        }
                        if (t==4){//DEPARTMENT
                            d = departments;
                            $.each(d,function(ii,vv){
                                $("<option></option>").attr("value", vv.nombre).text(vv.nombre).appendTo($select);
                            });
                        }
                        if (t==6){//DOMAINS
                            d = domains;
                            $.each(d,function(ii,vv){
                                $("<option></option>").attr("value", vv.id).text(vv.domain).appendTo($select);
                            });
                        }
                        if (t==7){//EMAILS
                            d = emails;
                            $.each(d,function(ii,vv){
                                $("<option></option>").attr("value", vv.id).text(vv.name + " - " + vv.email).appendTo($select);
                            });
                        }
                    }
                }
            }
            $.asColorPicker.setDefaults();
        });
    </script>

@endsection
