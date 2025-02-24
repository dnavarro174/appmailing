@isset($form)
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-radio">
                <input type="radio" id="inp-${opt.id}" name="${opt.name}" class="form-check-input check_click" value="1" >
                <label class="form-check-label" for="inp-${opt.id}">${opt.title} <i class="input-helper"></i></label>
            </div>
            <small class="form-text text-muted">${opt.note}</small>
        </div>
    </div>
@else
    <div class="${opt.cls}">
        <div class="form-radio">
            <input type="radio" id="inp-${opt.id}" name="${opt.name}" class="form-check-input check_click" value="1" >
            <label class="form-check-label" for="inp-${opt.id}">${opt.title} <i class="input-helper"></i></label>
        </div>
    </div>
@endif
