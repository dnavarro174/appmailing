<div class="row">
    <label for="inp-${opt.id}">
        <div class="h4">${opt.title}<span class="text-danger">${opt.req}</span></div>
        <div>${opt.subtitle}</div>
    </label>
    <div class="form-group">
        <input type="checkbox" class="form-control" name="${opt.name}" value="${opt.name}" id="inp-${opt.id}" />
        <label for="${opt.id}">${opt.value}</label>
    </div>
</div>
