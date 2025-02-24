@isset($form)
    <div class="form-group row">
        <label for="inp-${opt.id}" class="col-sm-12 col-md-4 col-lg-2 col-form-label d-block">
            <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
            <div>${opt.subtitle}</div>
        </label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" />
            <small class="form-text text-muted">${opt.note}</small>
        </div>
    </div>
@else
    <div class="${opt.cls}">
        <div class="form-group ">
            <label for="inp-${opt.id}" class="${opt.clsh}">${opt.title} <span class="text-danger">${opt.req}</span></label>
            <div>${opt.subtitle}</div>
            <input type="file" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" />
            <small class="form-text text-muted">${opt.note}</small>
        </div>
    </div>
@endif
