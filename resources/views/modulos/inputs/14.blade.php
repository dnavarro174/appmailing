@isset($input)
    @php($df = $input->getDataField($data))
    @php($elements = $df["elements"])
    @if(count($elements)>0)
        @foreach( $elements as $i=>$ee)
            @php($f_text = strtr($df["f_text"], json_decode(json_encode($ee), true)))
            <div class="form-check">
                <input type="checkbox" id="inp-{{$input->id}}-o-{{$i}}" name="inputs[{{$input->id}}][]" class="form-check-input check_click" value="{{ $ee->{$df["f_key"]} }}" {{ in_array($ee->{$df["f_key"]}, $input->values)   ?"checked":"" }}>
                <label class="form-check-label" for="inp-{{$input->id}}-o-{{$i}}">{{ $f_text }} <i class="input-helper"></i></label>
            </div>
        @endforeach
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
