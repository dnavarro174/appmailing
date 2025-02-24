<div class="table-responsive fixed-height">{{-- table-responsive-lg --}}{{--  --}}
    <table id="order-listing" class="table table-hover table-sm">
        <thead class="thead-dark">
        <tr>
            <td colspan="6" class="text-right">
                <span class="pull-right">
  <strong>Mostrando</strong>
  {{ $students->firstItem() }} - {{ $students->lastItem() }} de
  {{ $students->total() }}
</span>
            </td>
        </tr>
        <tr role="row">
            <th class="sorting" style="width: 20%;">EMAIL</th>
            <th class="sorting" style="width: 20%;">NOMBRE</th>
            <th class="sorting" style="width: 15%;">PAT</th>
            <th class="sorting" style="width: 15%;">MAT</th>
            <th class="sorting text-center" style="width:10%;">ERROR?</th>
            <th class="sorting" style="width: 10%;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($students as $datos)
            @php($is_edit = $edit>0 && $datos->id == $edit)
            @if(!$is_edit)
            <tr role="row" class="odd hover" style='background:{{ $loop->index%2==0?'#ccc':'f7d3d3' }};' >
                <td>{{ $datos->email }}</td>
                <td>{{ $datos->nombres }}</td>
                <td>{{ $datos->ap_paterno }}</td>
                <td>{{ $datos->ap_materno }}</td>
                <td class="text-center">{{ $datos->error>0?'SI':'NO' }}</td>
                <td class="text-center">
                    <div class="col-xs-12 text-right mx-auto">
                        <button type="button" title="Editar" class="students-edit btn btn-sm btn-info icon-btn p-1" tag="{{$datos->id}}" data-url="{{ request()->fullUrlWithQuery(["edit"=>$datos->id]) }}">
                            <i class="mdi mdi-pencil text-white icon-sm" ></i>
                        </button>
                        <button type="button" title="Eliminar" class="students-delete btn btn-sm btn-warning icon-btn p-1" tag="{{$datos->id}}" data-url="{{ request()->fullUrlWithQuery(["page"=>$page2]) }}">
                            <i class="mdi mdi-delete text-white icon-sm" ></i>
                        </button>
                    </div>
                </td>
            </tr>
            @else
                <tr role="row" class="odd hover" style='background:{{ $loop->index%2==0?'#ccc':'f7d3d3' }};' tag="{{$datos->id}}" >
                    <td class="align-top">
                        <input id="sid" type="hidden" name="sid"  value="{{ old('sid',$datos->id) }}"/>
                        <input id="iid" type="hidden" name="iid"  value="{{ old('sid',$datos->import_id) }}"/>
                        <input required id="email" type="text" class="form-control form-control-sm {{$errors->has('email')?'is-invalid':''}}" name="email"
                               placeholder="Email..." value="{{ old('email',$datos->email) }}" maxlength="50" />

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </td>
                    <td  class="align-top">
                        <input type="text" class="form-control form-control-sm {{$errors->has('nombres')?'is-invalid':''}}" id="nombres" name="nombres"
                               placeholder="Nombres..." value="{{ old('email',$datos->nombres) }}" maxlength="50" />
                        @if ($errors->has('nombres'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('nombres') }}</strong>
                            </div>
                        @endif
                    </td>
                    <td class="align-top">
                        <input id="ap_paterno" type="text" class="form-control form-control-sm {{$errors->has('ap_paterno')?'is-invalid':''}}" name="paterno"
                               placeholder="paterno..." value="{{ old('email',$datos->ap_paterno) }}" maxlength="30" />
                    </td>
                    <td class="align-top">
                        <input id="ap_materno" type="text" class="form-control form-control-sm {{$errors->has('ap_materno')?'is-invalid':''}}" name="materno"
                               placeholder="materno..." value="{{ old('email',$datos->ap_materno) }}" maxlength="30" />
                    </td>
                    <td class="text-center">{{ $datos->error>0?'SI':'NO' }}</td>
                    <td class="text-center align-top">
                        <div class="col-xs-12 text-right mx-auto">
                            <button type="button" title="Grabar" id="btnSave" class="students-save btn btn-sm btn-success icon-btn p-1" data-url="{{ request()->fullUrlWithQuery(["edit"=>null]) }}">
                                <i class="mdi mdi-content-save text-primary icon-md" ></i>
                            </button>
                            <button type="button" title="Cancelar" class="students-edit btn btn-sm btn-danger icon-btn p-1"  data-url="{{ request()->fullUrlWithQuery(["edit"=>null]) }}">
                                <i class="mdi mdi-cancel text-white icon-md" ></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    {!! $students->appends(request()->query())->links() !!}
</div>

