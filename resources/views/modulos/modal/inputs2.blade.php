<div class="col-sm-10 f-select-1 t-field"  id="fv-${opt.index}">
    <div class="input-group d-block px-2">
        <div class="input-group-append pt-1">
            <label for="fvname-${opt.index}" class="font-weight-bold px-3 w-25">${opt.index}</label>
            <input type="text" name="fvname[${opt.index}]" id="fvname-${opt.index}" required="required"  class="form-control" placeholder="Texto" aria-label="Texto" aria-describedby="basic-addon2">
            <input type="text" name="fvvalue[${opt.index}]" id="fvvalue-${opt.index}" class="form-control" placeholder="Valor" aria-label="Valor" aria-describedby="basic-addon2">
            <button class="btn btn-danger icon-btn p-1" type="button" tag="${opt.index}">
                <i class="mdi mdi-minus text-white icon-md" ></i>
            </button>
        </div>
    </div>
</div>
