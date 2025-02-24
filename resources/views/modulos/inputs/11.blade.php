@isset($input)
    @php($df = $input->getDataField($data))
    @php($elements = $df["elements"])
    <select name="inputs[{{$input->id}}]" id="inp-{{$input->id}}" placeholder="Ingrese {{$input->title}}" class="form-control" {{$input->required?"required":""}}>
        <option value="" selected disabled></option>
        @if(count($elements)>0)
            @foreach( $elements as $ee)
                @php($f_text = strtr($df["f_text"], json_decode(json_encode($ee), true)))
                <option value="{{ $ee->{$df["f_key"]} }}" {{ $ee->{$df["f_key"]} == $input->value  ?"selected":"" }}>{{ $f_text }}</option>
            @endforeach
        @endif
    </select>
@else
    @isset($js_form)
        <div class="${opt.cls}">
            <div class="form-group ">
                <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
                <div>${opt.subtitle}</div>
                <select name="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" class="form-control"></select>
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
                <select name="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" class="form-control"></select>
                <small class="form-text text-muted">${opt.note}</small>
            </div>
        </div>
    @endif
@endif
