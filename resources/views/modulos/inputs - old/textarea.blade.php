<div class="row">
    <label for="inp-${opt.id}">
        <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
        <div>${opt.subtitle}</div>
    </label>
    <textarea placeholder="Ingrese ${opt.title}" class="form-control" name="${opt.name}" id="inp-${opt.id}" cols="30" rows="5">${opt.value}</textarea>
    <small class="form-text text-muted">${opt.note}</small>
</div>
