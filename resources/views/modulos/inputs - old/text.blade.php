<div class="row">
    <label for="inp-${opt.id}">
        <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
        <div>${opt.subtitle}</div>
    </label>
    <input type="text" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" placeholder="Ingrese ${opt.title}" />
    <small class="form-text text-muted">${opt.note}</small>
</div>
