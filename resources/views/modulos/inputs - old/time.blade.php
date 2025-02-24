<div class="row">
    <label for="inp-${opt.id}">
        <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
        <div>${opt.subtitle}</div>
    </label>
    <input type="text" class="form-control timepicker-input" autocomplete="off" name="${opt.name}" placeholder="Ingrese ${opt.title}" value="" id="inp-${opt.id}" />
    <small class="form-text text-muted">${opt.note}</small>
</div>
