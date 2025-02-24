@isset($form)
    <div class="form-group row">
        <div for="inp-${opt.id}" class="col-sm-12 col-form-label d-block">
            <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
            <div>${opt.subtitle}</div>
            <small class="form-text text-sm">${opt.note}</small>
        </div>
    </div>
@else
    <div class="${opt.cls}">
        <div class="form-group ">
            <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
            <div>${opt.subtitle}</div>
            <small class="form-text text-sm">${opt.note}</small>
        </div>
    </div>
@endif
