@isset($form)
    <div class="form-group row">
        <label for="inp-${opt.id}" class="col-sm-12 col-md-4 col-lg-2 col-form-label">
            <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
            <div>${opt.subtitle}</div>
        </label>
        <div class="col-sm-10">
            <div id="datepicker-popup-${opt.index}" class="input-group date datepicker datepicker-input">
                <input type="text" class="form-control form-border" name="${opt.name}" value="${opt.value}" placeholder="Ingrese ${opt.title}" id="inp-${opt.id}">
                <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
            </div>
            <small class="form-text text-muted">${opt.note}</small>
        </div>
    </div>
@else
    <div class="${opt.cls}">
        <div class="form-group">
            <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
            <div>${opt.subtitle}</div>
            <div id="datepicker-popup-${opt.index}" class="input-group date datepicker datepicker-input">
                <input type="text" class="form-control form-border" name="${opt.name}" value="${opt.value}" placeholder="Ingrese ${opt.title}" id="inp-${opt.id}">
                <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
            </div>
            <small class="form-text text-muted">${opt.note}</small>
        </div>
    </div>
@endif
