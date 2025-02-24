@isset($ins)
    @php($check = $input->oj()["dm"] ?? 0)
    @php($domains = $data["domains"] ?? [])
    @if($check==1)
        @php($value0 = explode("@", $input->value, 2))
        @php($value1 = $value0[0] ?? "")
        @php($value2 = $value0[1] ?? "")
        <div class="input-group mb-2">
            <input type="text" class="form-control" id="inp-{{$input->id}}-email" name="inputs[{{$input->id}}-email]" placeholder="{{$input->title}}" required="" value="{{ $value1 }}">
            <div class="input-group-prepend">
                <select class=" form-control" required="" name="inputs[{{$input->id}}-domain]" id="inp-{{$input->id}}-domain">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->domain }}" {{ "@".$value2 == $domain->domain ?"selected": "" }}>{{ $domain->domain }}</option>
                    @endforeach
                </select>
            </div>
            <span class="text-danger small pt-2 d-none" id="message-{{$input->id}}"></span>
        </div>
    @else
        <input type="text" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
               placeholder="Ingrese {{$input->title}}" {{$input->required?"required":""}} />
    @endif
@elseif(isset($input))
    <input type="text" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
           placeholder="Ingrese {{$input->title}}" {{$input->required?"required":""}} />
@else

    @isset($js_form)
        <div class="${opt.cls}">
            <div class="form-group">
                <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
                <div>${opt.subtitle}</div>
                <input type="text" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" />
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
                <input type="text" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" />
                <small class="form-text text-muted">${opt.note}</small>
            </div>
        </div>
    @endif
@endif
