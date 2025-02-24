<div class="row t-field" id="fv-${opt.index}">
    <div class="col-sm-5">
        <input type="text" required="required" class="form-control"
               name="fvname[${opt.index}]" id="fvname-${opt.index}" placeholder="Nombre" />
    </div>
    <div class="col-sm-5">
        <input type="text" class="form-control"
               name="fvvalue[${opt.index}]" id="fvvalue-${opt.index}" placeholder="Valor" value="" />
    </div>
    <div class="col-sm-2 form-group px-0">
        <input type="checkbox" class="largerCheckbox" id="fvcheck-${opt.index}">
        <button class="btn btn-danger icon-btn p-1" type="button" tag="${opt.index}">
            <i class="mdi mdi-minus text-white icon-md" ></i>
        </button>
    </div>
</div>
