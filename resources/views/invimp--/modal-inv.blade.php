<div id="order-listing_wrapper2"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
    <div class="form-group row">
        <label for="students-email" class="col-xs-12 text-right  col-form-label d-block">Buscar</label>
        <div class="col-sm-8">
            <input required id="modal-search" type="text" class="form-control" name="modal-search" placeholder="Ingrese texto a buscar" value="" maxlength="50" />
        </div>
        <div class="form-group">
            <label class="form-check-label form-check-inline"  style="line-height: 25px;">
            <select class="form-control" name="form-estado" id="form-estado">
                @foreach($form['estados'] as $i=>$estado)
                    <option value="{{$i}}" title="{{$estado}}">{{$estado}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right mb-4 mx-auto">
            <button type="button" title="Agregar" class="btn btn-sm btn-success btn-sm icon-btn " id="noemail-save">
                <i class="mdi mdi-content- text-white icon-md" ></i> Buscar
            </button>
            <button type="button" title="Limpiar" class="btn btn-sm btn-danger btn-sm icon-btn" id="form-reset">
                <i class="mdi mdi-cancel text-white icon-md" ></i> Resetear
            </button>
        </div>
    </div>
    <div class="row modal-table">

    </div>
</div>
