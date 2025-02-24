<div id="order-listing_wrapper2"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
    <div class="form-group row">
        <input required id="noemails-id" type="hidden" name="noemails[id]" value="{{ old('email',$noemail->id) }}" />

        <label for="noemails-email" class="col-xs-12 text-right  col-form-label d-block">Email <span class="text-danger">*</span></label>
        <div class="col-sm-8">
            <input required id="noemails-email" type="text" class="form-control {{$errors->has('email')?'is-invalid':''}}" name="email" placeholder="Parametros o dominio" value="{{ old('email',$noemail->email) }}" maxlength="50" />
            <div id="noemails-def"></div>
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label class="form-check-label form-check-inline"  style="line-height: 25px;">
                <input type="checkbox" class="form-check-input" name="flatRadios1" id="noemails-status" value="" checked="">
                <i class="input-helper input-"></i> Activo</label>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right mb-4 mx-auto">
            <button type="button" title="Agregar" class="btn btn-sm btn-success btn-sm icon-btn " id="noemail-save">
                <i class="mdi mdi-content-save text-white icon-md" ></i> Grabar
            </button>
            <button type="button" title="Agregar" class="btn btn-sm btn-danger btn-sm icon-btn" id="noemails-cancel">
                <i class="mdi mdi-cancel text-white icon-md" ></i> Cancelar
            </button>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
            <table id="order-listing" class="table table-hover table-sm">
                <thead class="thead-dark">
                <tr role="row">
                    <th class="sorting" style="width: 70%;">NOMBRE</th>
                    <th class="sorting text-center" style=";">ACTIVADO?</th>
                    <th class="sorting" style="width: 10%;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($noemails as $datos)
                    <tr role="row" class="odd hover" style='background:{{ $loop->index%2==0?'#ccc':'f7d3d3' }};' >
                        <td>{{ $datos->email }}</td>
                        <td class="text-center">{{ $datos->status==1?'SI':'NO' }}</td>
                        <td class="text-center">
                            <div class="col-xs-12 text-right mx-auto">
                                <a type="button" title="Agregar" class=" noemails-edit" tag="{{$datos->id}}">
                                    <i class="mdi mdi-pencil text-dark icon-md" ></i>
                                </a>
                                <a type="button" title="Agregar" class=" noemails-delete" tag="{{$datos->id}}">
                                    <i class="mdi mdi-delete text-danger icon-md" ></i>
                                </a>
                                {{--  <button type="button" title="Agregar" class="btn btn-dark btn-sm icon-btn noemails-edit" tag="{{$datos->id}}">
                                    <i class="mdi mdi-pencil text-white icon-md" ></i>
                                </button>
                                <button type="button" title="Agregar" class="btn btn-danger btn-sm icon-btn noemails-delete" tag="{{$datos->id}}">
                                    <i class="mdi mdi-delete text-white icon-md" ></i>
                                </button>  --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $noemails->appends(request()->query())->links() !!}
        </div>
    </div>
</div>
