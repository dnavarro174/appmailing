@isset($ins)
    <div class="form-check">
        <input type="checkbox" id="inp-{{$input->id}}" name="inputs[{{$input->id}}]" class="form-check-input check_click" value="1" {{ $input->value ==1 ? 'checked': '' }}>
        <label class="form-check-label" for="inp-{{$input->id}}">{{$input->title}} <i class="input-helper"></i></label>
    </div>
@elseif(isset($input))
    <input type="checkbox" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
        {{$input->required?"required":""}} />
    <label for="inp-{{$input->id}}">{{$input->title}}</label>
@else
    @isset($js_form)
        <div class="${opt.cls}">
            <div class="form-check">
                <input type="checkbox" id="inp-${opt.id}" name="${opt.name}" class="form-check-input check_click" value="1" >
                <label class="form-check-label ${opt.clsh}" for="inp-${opt.id}">${opt.title} <i class="input-helper"></i></label>
                <label class="form-check-label" for="inp-${opt.id}">${opt.subtitle}</label>
            </div>
        </div>
    @else
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-check">
                    <input type="checkbox" id="inp-${opt.id}" name="${opt.name}" class="form-check-input check_click" value="1" >
                    <label class="form-check-label" for="inp-${opt.id}">${opt.title} <i class="input-helper"></i></label>
                </div>
                <small class="form-text text-muted">${opt.note}</small>
            </div>
        </div>
    @endif
@endif
