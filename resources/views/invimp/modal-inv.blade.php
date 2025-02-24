<div id="order-listing_wrapper2"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
    <div class="row">
        <div class="col-lg-6 col-sm-6 form-inline">
            <label for="students-email" class="col-xs-12 text-right  col-form-label pr-2">Buscar </label>
            <input required id="modal-search" type="text" class="form-control" name="modal-search" placeholder="Ingrese texto a buscar" value="" maxlength="50" />
        </div>
        <div class="col-lg-3 col-sm-6">
            <select class="form-control" name="form-estado" id="form-estado">
                @foreach($form['estados'] as $i=>$estado)
                    <option value="{{$i}}" title="{{$estado}}">{{$estado}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-12 text-right">
            <button type="button" title="Buscar" class="btn btn-sm btn-success btn-sm icon-btn" id="noemail-save">
                <i class="mdi mdi-magnify text-white icon-md" ></i>
            </button>
            <button type="button" title="Limpiar" class="btn btn-sm btn-danger btn-sm icon-btn" id="form-reset">
                <i class="mdi mdi-refresh text-white icon-md" ></i>
            </button>
        </div>
    </div>
    <div class="row modal-table">

    </div>
</div>
