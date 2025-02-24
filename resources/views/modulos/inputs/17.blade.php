@isset($input)
    @php($elements = $input->getDataQ())
    @php($value=is_array($input->value)?$input->value:[])
    @if(count($elements)>0)
        <div class="row">
        @foreach( $elements as $i=>$v)
                @php($rq = $v["required"]==1?"*":"")
                @php($rq2 = $v["required"]==1?"required=\"required\"":"")
                <div class="col-sm-9 col-md-9">
                    <div class="form-group pl-3">
                        <label class="d-flex flex-row bd-highlight mb-3" for="preg_1">
                            <span class="h6 pr-${opt.index}">{{$v["index"]}}.</span>
                            <span class="txtcampo h6 font-weight-normal text-justify">{{$v["text"]}} <em class="text-danger">{{$rq}}</em></span> </label>
                    </div>
                </div>
                <div class="col-sm-3 col-md-3">
                    <div class="form-group">
                        <div class="txt_center">
                            <label class="px-4 pt-2 number">Si <input type="radio" name="inputs[{{$input->id}}][{{$v["index"]}}]" id="inp-{{$input->id}}-{{$v["index"]}}-si" value="SI" {{$rq2}} {{isset($value[$v["index"]])&&$value[$v["index"]]=="SI"?"checked":""}}></label>
                            <label class="px-4 pt-2 number">No <input type="radio" name="inputs[{{$input->id}}][{{$v["index"]}}]" id="inp-{{$input->id}}-{{$v["index"]}}-no" value="NO" {{isset($value[$v["index"]])&&$value[$v["index"]]=="NO"?"checked":""}}></label>
                        </div>
                    </div>
                </div>
        @endforeach
        </div>
    @endif
@else
    @isset($js_form)
        <div class="${opt.cls}">
            <div class="form-group ">
                <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
                <div>${opt.subtitle}</div>
                <div id="inp-${opt.id}"></div>
                <small class="form-text text-muted">${opt.note}</small>
            </div>
        </div>
    @else
        <div class="form-group row">
            <label for="inp-${opt.id}" class="col-sm-12 col-md-4 col-lg-2 col-form-label d-block">
                <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
                <div>${opt.subtitle}</div>
            </label>
            <div class="col-sm-10">
                <div id="inp-${opt.id}"></div>
                <small class="form-text text-muted">${opt.note}</small>
            </div>
        </div>
    @endif
@endif
