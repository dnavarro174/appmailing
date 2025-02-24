@isset($js_form)
    <div class="row">
        <div class="col-sm-9 col-md-9">
            <div class="form-group pl-3">
                <label class="d-flex flex-row bd-highlight mb-3" for="preg_1">
                    <span class="h6 pr-${opt.index}">${opt.index}.</span>
                    <span class="txtcampo h6 font-weight-normal text-justify">${opt.text} <em class="text-danger">${opt.rq}</em></span> </label>
            </div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group">
                <div class="txt_center">
                    <label class="px-4 pt-2 number">Si <input type="radio" name="preg_${opt.index}" id="preg_${opt.index}-si" value="SI"></label>
                    <label class="px-4 pt-2 number">No <input type="radio" name="preg_${opt.index}" id="preg_${opt.index}-no" value="NO"></label>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-sm-12 t-question"  id="fq-${opt.index}">
        <div class="row py-2">
            <label for="fqname-${opt.index}" class="font-weight-bold px-3 col-sm-1">${opt.index}</label>
            <div class="col-sm-10">
                <input type="text" name="fqname[${opt.index}]" id="fqname-${opt.index}"  value="${opt.text}"
                       class="form-control editor-sn" placeholder="Texto" aria-label="Texto" aria-describedby="basic-addon2">
            </div>

            <div class="d-flex flex-column align-content-between flex-wrap  col-sm-1 align-items-center">
                <div class="form-check">
                    <input type="checkbox" id="fqreq-${opt.index}" name="fqreq[${opt.index}]" class="form-check-input check_click" value="1" >
                    <label class="form-check-label" for="fqreq-${opt.index}">Req <i class="input-helper"></i></label>
                </div>
                <button class="btn btn-danger icon-btn p-1" type="button" tag="${opt.index}">
                    <i class="mdi mdi-minus text-white icon-md" ></i>
                </button>
            </div>
        </div>
    </div>
@endisset
