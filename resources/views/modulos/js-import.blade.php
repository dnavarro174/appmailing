@section('scripts')
    <style>
        .note-editable b, .note-editable strong { font-weight: bold; }

        .wizard > .content > .body{position: relative;}
        .form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
        .form-control2 label.form-radio em{color:#21AFAF;font-style: normal;}
        .form-control2 label.form-radio span{color:#556685;}
        .texto_foros p{padding-left: 25px;}
        .wizard > .content > .body input{display: inline-block;}

        h1.card-title{
            font-family: Arial,Helvetica Neue,Helvetica;
            letter-spacing: -1px;
        }
        .card-body div strong{font-weight: 800;}
        .select2 {
            width:100%!important;
        }
        a.add-exp{
            color:#35b0ff !important;
        &:hover{ color:#35b0ff !important;}
        &:visited{ color:#35b0ff !important;}
        }
        .input-select-load-data {
            background-color: cornsilk;
            opacity: 0.9;
        }
    </style>
    @if(count($editors1)>0)
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-es-ES.js"></script>
        <script>
            function actualizarEditorSN(s){
                var $s = $(s);
                var vv = $s.val();
                $s.summernote({
                    placeholder: 'Texto...',
                    tabsize: 2,
                    height: 220,
                    toolbar: [
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['forecolor', ['forecolor']],
                        ['view', ['fullscreen', 'codeview']]//, 'help'
                    ],
                    lang: 'es-ES' // default: 'en-US'
                });
                $s.summernote('code', vv);
            }
        </script>
    @endif
    <script>
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
                $sel.each(function(){
                    $(this).timepicker();
                });
        }

        var editor_ids = [];
        var remove_ids = [];
        var $inpr;


        $('document').ready(function(){
            $inpr = $('#inpr');
            setPickers();

            @if(count($editors1)>0)
            @foreach($editors1 as $v)
            actualizarEditorSN('{{$v}}');
            editor_ids.push('{{$v}}');
            @endforeach
            @endif



            @foreach($linesjs as $line)
            {!! $line !!}
            @endforeach

            @foreach($requireds  as $c)
            $("#content-{{$c->id}}").data("req", 1);
            @endforeach
            @foreach($ins3 as $c)
            @if(isset($eventsjs[$c->id]))
            $("#inp-{{$c->id}}").on("{{ $eventsjs[$c->id]["event"] }}", function(){
                @foreach($eventsjs[$c->id]["lines"]  as $line)
                    {!! $line !!}
                    @endforeach
            });
            @endif
            @endforeach
            @isset($fields[$DF['dni']])
            $('#inp-{{$fields[$DF['dni']]}}').prop("maxlength", 15);
            $('#inp-{{$fields[$DF['dni']]}}').attr("oninput", "javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);");
            var xdni_anterior= "";
            var $input_dni = $('#inp-{{$fields[$DF['dni']]}}');
            var $input_tipo_doc = $('#inp-{{$fields[$DF['tipo_doc']]}}');
            var $input_apemat = $('#inp-{{$fields[$DF['apemat']]}}');
            var $content_apemat = $('#content-{{$fields[$DF['apemat']]}}');

            $input_dni.parent().prepend('<span class="d-none badge badge-danger float-right" id="message-dni">DOCUMENTO REGISTRADO EN EL EVENTO</span>');
            $input_dni.on("keyup", function(){
                var xdni = $input_dni.val();
                if(xdni == xdni_anterior)return;
                $("#message-dni").addClass("d-none");
                setValuesChangeDisabled(false);
                setValuesChangeEmpty();
                var evento = "{{$m_product_id}}";
                var url = baseURL('')+"m/getDNI/"+xdni+"/"+evento+"";
                xdni_anterior = xdni;
                $.post('{{route('modulos.postdni',$m_product_id)}}',{id: $("#id").val() ,dni:xdni,_token: "{{ csrf_token() }}"}, function(r,d){
                    if(r.registered)$("#message-dni").removeClass("d-none");
                    if(r.success){
                        var data = r.data;
                        setValuesChangeDNI(data);
                        setValuesChangeDisabled(true);
                        xdni_anterior = xdni;
                    }
                });
/*                $.get(url, function(r,d){

                });*/
            });

            function limitTextType(is_empty=false){
                if($input_tipo_doc.val()==1){
                    if(is_empty)$input_dni.val("");
                    $input_dni.attr({type:'number', maxLength:8});
                    //$input_apemat.attr({required: true});
                    //$content_apemat.find("span").addClass('d-inline-block');
                }else{
                    $input_dni.attr("type", 'text').attr('maxlength',15);
                    //$input_apemat.attr({required: false});
                    //$content_apemat.find("span").addClass('d-none');
                }
            }

            $input_tipo_doc.on("change", function(){
                setValuesChangeEmpty();
                setValuesChangeDisabled();
                limitTextType(true);
            });
            limitTextType();

            $input_dni.keypress(function (e){
                var charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)&&$input_tipo_doc.val()==1) {
                    return false;
                }
            });

            @php($fcampos=["nom", "apepat", "apemat","pais","departamento","grado-profesion","email"])
            @php($fcampos2=["nombres", "ap_paterno", "ap_materno","pais","region","cargo","email"])
            function setValuesChangeDNI(data){
                @foreach($fcampos as $fi => $fc)
                    @isset($fields[$DF[$fc]]) $('#inp-{{$fields[$DF[$fc]]}}').val(data.{{$fcampos2[$fi]}}); @endif
                @endforeach
            }
            function setValuesChangeEmpty(){
                @foreach($fcampos as $fi => $fc)
                @isset($fields[$DF[$fc]]) $('#inp-{{$fields[$DF[$fc]]}}').val(""); @endif
                @endforeach
            }
            function setValuesChangeDisabled(disabled){

                @foreach($fcampos as $fi => $fc)
                    @isset($fields[$DF[$fc]]) $('#inp-{{$fields[$DF[$fc]]}}').prop("disabled", disabled); @endif
                @endforeach

            }
            function setValuesChangeDisabled(disabled){
                if(disabled){
                @foreach($fcampos as $fi => $fc)
                    @isset($fields[$DF[$fc]]) $('#inp-{{$fields[$DF[$fc]]}}').addClass("input-select-load-data"); @endif
                @endforeach
                }else{
                @foreach($fcampos as $fi => $fc)
                    @isset($fields[$DF[$fc]]) $('#inp-{{$fields[$DF[$fc]]}}').removeClass("input-select-load-data"); @endif
                @endforeach
                }

            }
            function setValuesChangeEmpty2(data){
                @isset($fields[$DF['nom']]) $('#inp-{{$fields[$DF['nom']]}}').val(""); @endif
                @isset($fields[$DF['apepat']]) $('#inp-{{$fields[$DF['apepat']]}}').val(""); @endif
                @isset($fields[$DF['apemat']]) $('#inp-{{$fields[$DF['apemat']]}}').val(""); @endif
                @isset($fields[$DF['pais']]) $('#inp-{{$fields[$DF['pais']]}}').val(""); @endif
                @isset($fields[$DF['departamento']]) $('#inp-{{$fields[$DF['departamento']]}}').val(data.region); @endif
                @isset($fields[$DF['grado-profesion']]) $('#inp-{{$fields[$DF['grado-profesion']]}}').val(data.cargo); @endif
                @isset($fields[$DF['email']]) $('#inp-{{$fields[$DF['email']]}}').val(data.email); @endif
            }
            function clearForm(){
                $('#inp-{{$fields[$DF['nom']]}}').val("");//nombre
                $('#inp-{{$fields[$DF['apepat']]}}').val("");//ap_paterno
                $('#inp-{{$fields[$DF['apemat']]}}').val("");//ap_materno
                $('#inp-{{$fields[$DF['grado-profesion']]}}').val("");//profesion
                $('#inp-{{$fields[$DF['grado-profesion']]}}').val("");//organizacion
                $('#inp-{{$fields[$DF['entidad']]}}').val("");//cargo
                $('#inp-{{$fields[$DF['email']]}}').val("");//email
                $('#inp-{{$fields[$DF['email']]}}').val("");//email dominio
                $('#inp-{{$fields[$DF['celular']]}}').val("");//celular

                @isset($fields[$DF['pais']]) $('#inp-{{$fields[$DF['pais']]}}').val("");//pais @endif
                $('#inp-{{$fields[$DF['grupo']]}}').val("");//grupo
            }
            @endisset
            function addExp($selector, id, index){
                var h = `@include('modulos.modal.input-ex', ["jsd"=>["id"=>'${id}', "index"=>'${index}'],"exps"=>[$expdata["emptyExp"]]])`;
                var $h = $(h);
                $h.find(".btn-del").removeClass("d-none");
                $h.find(".btn-linea").removeClass("d-none");
                $selector.append($h);
            }
            function addExp2($selector, id, index){
                var h = `@include('modulos.modal.input-ex2', ["jsd"=>["id"=>'${id}', "index"=>'${index}'],"exps"=>[$expdata["emptyExp2"]]])`;
                var $h = $(h);
                $h.find(".btn-del").removeClass("d-none");
                $h.find(".btn-linea").removeClass("d-none");
                $selector.append($h);
            }
            $( document ).on( "click", ".btn-delete-exp", function(e) {
                e.preventDefault();
                $(this).parents('.c-ex').remove();
                return false;
            });
            $( document ).on( "click", ".add-exp", function(e) {
                e.preventDefault();
                var tag = $(this).attr("tag");
                var iix = 0;
                var $parent = $(this).parents('.exp').eq(0);
                var $selector = $parent.find('.exp-row').eq(0);
                var iid = parseInt($parent.attr("tag"));
                var $list = $selector.find('.c-ex').last();
                if($list.length)iix = parseInt($list.attr("tag"));
                iix++;
                if(tag ==1)addExp($selector, iid, iix);
                if(tag ==2)addExp2($selector, iid, iix);
                console.log($selector, iid, iix);
                return false;
            });

            $( document ).on( "click", ".delete-file", function(event) {//NEW
                var i = $(this).attr("tag");
                toggleFile(i, true)
            });
            $( document ).on( "click", ".restore-file", function(event) {//NEW
                var i = $(this).attr("tag");
                toggleFile(i, false)
            });
            $( document ).on( "click", ".btn-reset-input-file", function(event) {//NEW
                var i = $(this).attr("tag");
                $content = $("#inp-"+i).val('');
            });
            function toggleFile(i, v){
                var ex = remove_ids.indexOf(i);
                var $content = $("#content-remove-"+i);
                var $content_link = $("#content-link-"+i);
                var $img = $content.find('img');
                var $btn_delete = $content.find('.delete-file');
                var $btn_restore = $content.find('.restore-file');
                $btn_delete.removeClass('d-none');
                $btn_restore.removeClass('d-none');
                $img.removeClass('d-none');
                $content_link.removeClass('d-none');
                if(v){
                    $img.addClass('d-none');
                    $btn_delete.addClass('d-none');
                    $content_link.addClass('d-none');
                    if(ex==-1)remove_ids.push(i);
                }else{
                    $btn_restore.addClass('d-none');
                    if(ex!=-1)remove_ids.splice(ex, 1);
                }
                if($inpr)$inpr.val(JSON.stringify(remove_ids));
            }

            $( "#leadForm" ).on( "submit", function(e) {

                var xinpdni = $input_dni.val();
                if($input_tipo_doc.val()==1 && xinpdni.length<8){
                    swal("Advertencia", "DNI invalido", "warning").then(function() {
                        swal.close();
                        $input_dni.focus().val("").val(xinpdni);
                    });
                    return false;
                }

                $("select[id^='inp-']").each(function(){
                    var $this = $(this);
                    var multiple = $this.prop("multiple");
                    var xid = this.id.substring(4);
                    var xindex = this.selectedIndex;
                    var arr = [];
                    var xt = "";
                    var $selecteds = $this.find("option:selected");
                    if(multiple){
                        if($selecteds.length){
                            $selecteds.each(function(ii, vv){
                                arr.push($(vv).text());
                            });
                        }
                        xt = JSON.stringify(arr);
                    }else{
                        xt = xindex > 0 ? $selecteds.text() : "";
                    }
                    $this.parent().find(".input-kei").val(xt);
                });

                $("div[id^='content-'] .form-group:has(.check_click)").each(function(){
                    var $group = $(this);
                    var $input_id = $group.find('.input-id');
                    var $input_kei = $group.find('.input-kei');
                    var xid =$input_id.attr("tag");
                    var arr = [];
                    var xt = "";
                    var multiple = !!($group.find(":checkbox").length);
                    var $selecteds = $group.find(":checked");
                    if($selecteds.length){
                        $selecteds.each(function(ii, vv){
                            var xch_id =  $(vv).attr("id");
                            var $xlab = $('.form-check-label[for="'+xch_id+'"]');
                            arr.push($xlab.text());
                        });
                    }
                    if(multiple)xt = JSON.stringify(arr);
                    else if(arr.length>0) xt = arr[0];
                    $input_kei.val(xt);
                });
            });
            var $sc = $('.select-custom-2');
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
            if($link_plantilla.length){
                $link_plantilla.on('click', function(e){
                    e.preventDefault();
                    var editorId = $(this).data("input-editor");
                    var id = $(this).attr("tag");
                    loadEditorSelect(id, editorId);
                });
            }
            function loadEditorSelect(id, editorId){
                $("#"+editorId).summernote('code', "");
                if(id){
                    $.getJSON("/m/template/"+id, function(v){
                        if(v && v.success){
                            $("#"+editorId).summernote('code', v.data.plantillahtml);
                        }
                    });
                }
            }

            //ANULANDO EL FOCUS DEL EDITOR
            editor_ids.forEach(function(vv,ii){
                $(vv).attr("required", false);
            });
            var $checks_all = $('.select-check-all');
            var $chk_cat_all = $('#chk-cat-all');
            if($chk_cat_all && $checks_all){
                var ncks1 = $checks_all.length;
                var $checkeds_all = $('.select-check-all:checked');
                var ncks2 = $checkeds_all ? $checkeds_all.length: 0;

                if(ncks2>0)$chk_cat_all.prop("checked", true);

                $chk_cat_all.on('click', function(){
                    $checks_all.prop("checked", this.checked);
                });
            }
            @isset($selects)
                function loadComboJS(e){
                    var inp = $(this).data('inp');//onchange
                    var params = $(this).data('params');//params antes
                    var select = $(this).data('select');
                    var elements = $(this).data('elements');//inputs que siguen
                    var select2 = document.getElementById('inp-'+select);
                    var arr = {};
                    arr[inp[0]] = document.getElementById('inp-'+inp[1]).value;
                    for(var ii=0; ii<params.length;ii++){
                        var p = params[ii];
                        arr[p[0]] = document.getElementById('inp-'+p[1]).value;
                    }
                    var data = { 'data': arr, 'type': inp[0], _token: "{{ csrf_token() }}"}
                    select2.options.length=inp[2]!=''?1:0;
                    var $select2 = $(select2);

                    for(var ii=0; ii<elements.length;ii++){
                        var p = elements[ii];
                        var sel = document.getElementById('inp-'+p[1]);
                        //sel.options.length=0;//p[2]!=''?1:0;
                        //if(p[2]!='') $('<option></option>').prop('disabled', true).prop('selected', true).text(p[2]).appendTo($(sel));
                        sel.options.length=p[2]!=''?1:0;
                        sel.selectedIndex = 0;
                    }
                    $.post('{{route('modulos.ubigeo')}}',data, function(r,d){
                        if(r){
                            r.forEach(v=>{
                                $('<option></option>').attr('value', v.ubigeo_id).text(v.nombre).appendTo($(select2));
                            });
                        }
                    });
                }
                @foreach($selects as $select)
                @php($input=$select['input'])
                @php($elements=$select['elements'])
                @php($elements2=$select['elements2'])
                    $('#inp-{{ $input['id']}}').data("inp",[ {{ $input['type']??0}}, {{ $input['id']??''}} ])
                    $('#inp-{{ $input['id']}}').data("params", [ @foreach($elements as $e) [ {{ $e['type']??0}}, {{ $e['id']??''}}, '{{ $e['ph']??''}}' ],  @endforeach ] )
                    $('#inp-{{ $input['id']}}').data("elements", [ @foreach($elements2 as $e) [ {{ $e['type']??0}}, {{ $e['id']??''}}, '{{ $e['ph']??''}}' ],  @endforeach ] )
                    $('#inp-{{ $input['id']}}').data("select", {{$select['id']??0}} )
                    $('#inp-{{ $input['id']}}').on('change', loadComboJS)
                @endforeach
            @endisset
        });
    </script>
@endsection
