@isset($input)
    <div id="datepicker-popup-{{$input->id}}" class="input-group date datepicker datepicker-input">
        <input type="text" class="form-control form-border" name="inputs[{{$input->id}}]" value="{{$input->value}}"
               {{$input->required?"required":""}} placeholder="Ingrese {{$input->title}}" id="inp-{{$input->id}}">
        <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
    </div>
@else
    <div class="row">
        <label for="inp-${opt.id}">
            <div class="h4 ${opt.clsh}">${opt.title}<span class="text-danger">${opt.req}</span></div>
            <div>${opt.subtitle}</div>
        </label>
        <div id="datepicker-popup-${opt.index}" class="input-group date datepicker datepicker-input">
            <input type="text" class="form-control form-border" name="${opt.name}" value="${opt.value}" placeholder="Ingrese ${opt.title}" id="inp-${opt.id}">
            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
        </div>
        <small class="form-text text-muted">${opt.note}</small>
    </div>
@endif
