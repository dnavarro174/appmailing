<div id="order-listing_wrapper2"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
    {{-- <div class="formAlert" @if($save==2)@endif>
        El curso no puede ser eliminado porque se han generado Declaraciones Juradas con el código del curso.aa
    </div> --}}
    <div id="formNuevo" @if($save==0) class=" d-none" @endif>
        <div class="form-group row">
            
            <input required id="curso-id" type="hidden" name="noemails[id]" value="{{ old('email',$curso->id) }}" />
        
            <label for="nom_curso" class="col-sm-2 col-xs-12 text-right  col-form-label d-block">Curso <span class="text-danger">*</span></label>
            <div class="col-sm-6">
                <input required id="nom_curso" type="text" class="form-control {{$errors->has('nom_curso')?'is-invalid':''}}" name="nom_curso" placeholder="Nombre del curso" value="{{ old('nom_curso',$curso->nom_curso) }}" maxlength="200" />
                <div id="curso-def"></div>
                @if ($errors->has('nom_curso'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('nom_curso') }}</strong>
                    </div>
                @endif
            </div>
            <label for="cod_curso" class="col-sm-1 col-xs-12 text-right  col-form-label d-block">Código <span class="text-danger">*</span></label>
            <div class="col-sm-2">
                <input required id="cod_curso" type="text" class="form-control {{$errors->has('cod_curso')?'is-invalid':''}}" name="cod_curso" placeholder="Código del curso" value="{{ old('cod_curso',$curso->cod_curso) }}" maxlength="50" />
                
                <div id="curso-def"></div>
                @if ($errors->has('cod_curso'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('cod_curso') }}</strong>
                    </div>
                @endif
            </div>
            <div class="form-group">{{-- check --}}
                <label class="form-check-label form-check-inline"  style="line-height: 25px;">
                    <input type="checkbox" class="form-check-input" name="flatRadios1" id="curso-status" value="" checked="">
                    <i class="input-helper input-"></i> Activo</label>
            </div>
        
            
        
        </div>
        <div class="form-group row">
        
            <label for="modalidad" class="col-sm-2 col-xs-12 text-right  col-form-label d-block">Modalidad <span class="text-danger">*</span></label>
            <div class="col-sm-2">
                <input required id="modalidad" type="text" class="form-control {{$errors->has('modalidad')?'is-invalid':''}}" name="modalidad"  value="{{ old('modalidad',$curso->modalidad) }}" maxlength="100" />
                <div id="curso-def"></div>
                @if ($errors->has('modalidad'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('modalidad') }}</strong>
                    </div>
                @endif
            </div>
        
            <label for="fech_ini" class="col-sm-2 col-xs-12 text-right  col-form-label d-block">Fecha Inicio </label>
            <div class="col-sm-2">
                <input required id="fech_ini" type="text" class="form-control {{$errors->has('fech_ini')?'is-invalid':''}}" name="fech_ini" placeholder="dd/mm/yyyy" value="{{ old('fech_ini',$curso->fech_fin) }}" maxlength="50" />
                <div id="curso-def"></div>
                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('fech_ini') }}</strong>
                    </div>
                @endif
            </div>
            <label for="fech_fin" class="col-sm-2 col-xs-12 text-right  col-form-label d-block">Fecha Fin </label>
            <div class="col-sm-2">
                <input required id="fech_fin" type="text" class="form-control {{$errors->has('fech_fin')?'is-invalid':''}}" name="fech_fin" placeholder="dd/mm/yyyy" value="{{ old('fech_fin',$curso->fech_fin) }}" maxlength="50" />
                <div id="curso-def"></div>
                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('fech_fin') }}</strong>
                    </div>
                @endif
            </div>
        
        </div>
        
        
        <div class="row">
        
            <div class="col-sm-6 col-xs-12 text-center mb-4 mx-auto">
                
                <button class="btn btn-sm btn-dark text-white" id="importarCursos" type="button"
                        data-toggle="modalImportar" data-target="#modalRemote" data-remote="{{route('importar_cursos')}}"
                        data-backdrop="static" data-title="Importar Cursos" data-fc="form-codigo">
                    <i class="mdi mdi-settings text-white icon-md"></i> Importar
                </button>
            
                <button type="button" title="Grabar" class="btn btn-sm btn-success btn-sm icon-btn " id="curso-save">
                    <i class="mdi mdi-content-save text-white icon-md" ></i> Grabar
                </button>
                <button type="button" title="Cancelar" class="btn btn-sm btn-danger btn-sm icon-btn" id="curso-cancel">
                    <i class="mdi mdi-cancel text-white icon-md" ></i> Cancelar
                </button>
            </div>
        </div>
    </div>
    <div id="formBuscar" @if($save==1) class=" d-none" @endif>
        <div class="form-group row">

            
            <input required id="curso-id" type="hidden" name="noemails[id]" value="{{ old('email',$curso->id) }}" />
        
            <label for="s" class="col-sm-1 col-xs-12 text-right col-form-label d-block">Buscar </label>
            <div class="col-sm-6 col-xs-12">
                <input required id="s" type="text" class="form-control {{$errors->has('s')?'is-invalid':''}}" name="s" placeholder="Buscar"   />
                <div id="curso-def"></div>
                @if ($errors->has('s'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('s') }}</strong>
                    </div>
                @endif
            </div>
            <div class="col-sm-5 pl-0">
                <button type="button" title="Buscar" class="btn btn-sm btn-success btn-sm icon-btn " id="curso-buscar">
                    Buscar <i class="mdi mdi-arrow-right-drop-circle text-white icon-md" ></i>
                </button>
                <button class="btn btn-sm btn-dark text-white" id="importarCursos" type="button"
                    data-toggle="modalImportar" data-target="#modalRemote" data-remote="{{route('importar_cursos')}}"
                    data-backdrop="static" data-title="Importar Cursos" data-fc="form-codigo">
                    <i class="mdi mdi-settings text-white icon-md"></i> Importar
                </button>
                <button type="button" title="Nuevo" class="btn btn-sm btn-dark btn-sm icon-btn" id="curso-nuevo">
                    <i class="mdi mdi-account-outline text-white icon-md" ></i> Nuevo
                </button>
                <button type="button" title="Cancelar" class="btn btn-sm btn-danger btn-sm icon-btn" id="curso-cancel">
                    <i class="mdi mdi-cancel text-white icon-md" ></i> Cancelar
                </button>
            </div>
        
        </div>
    </div>
        

    <div class="row">
        <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}
            
            <table id="order-listing" class="table table-hover table-sm">
                <thead class="thead-dark">
                <tr role="row">
                    <th class="sorting" style="width: 5%;"></th>
                    <th class="sorting" style="width: 20%;">CURSO</th>
                    <th class="sorting" style="width: 10%;">CODIGO</th>
                    <th class="sorting" style="width: 10%;">MODALIDAD</th>
                    <th class="sorting" style="width: 5%;">F.INICIO</th>
                    <th class="sorting" style="width: 5%;">F.FIN</th>
                    <th class="sorting text-center">VISIBLE</th>
                    @if(session('tipo_dj')==10)
                    <th class="sorting" style="width: 20%;">TIPO CAPACITACION</th>
                    <th class="sorting" style="width: 20%;">PROVEEDOR CAPACITACION</th>
                    <th class="sorting" style="width: 20%;">HORAS</th>
                    <th class="sorting" style="width: 20%;">COSTOS DIRECTOS</th>
                    <th class="sorting" style="width: 20%;">COSTOS INDIRECTOS</th>
                    <th class="sorting" style="width: 20%;">VALOR_CAPACITACION</th>
                    <th class="sorting" style="width: 20%;">MATERIA CAPACITACION</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($cursos as $datos)
                    <tr role="row" class="odd hover" style='background:{{ $loop->index%2==0?'f7d3d3':'#e4dfdf' }};' >
                        <td class="text-center">
                            <div class="col-xs-12 text-right mx-auto">
                                <a type="button" title="Agregar" class=" curso-edit" tag="{{$datos->id}}">
                                    <i class="mdi mdi-pencil text-dark icon-md" ></i>
                                </a>
                                <a type="button" title="Agregar" class=" curso-delete" tag="{{$datos->id}}">
                                    <i class="mdi mdi-delete text-danger icon-md" ></i>
                                </a>
                            </div>
                        </td>
                        <td style="display: block;white-space: initial !important;width:300px;"><a href="#" class="curso-edit" tag="{{$datos->id}}">{{ $datos->nom_curso }}</a></td>
                        <td>{{ $datos->cod_curso }}</td>
                        <td>{{ $datos->modalidad }}</td>
                        <td>{{ $datos->fech_ini }}</td>
                        <td>{{ $datos->fech_fin }}</td>
                        <td class="text-center">
                            @if($datos->status==1)
                                <span class="badge badge-dark">SI</span>
                            @else
                                <span class="badge badge-danger">NO</span>
                            @endif
                        </td>
                        @if(session('tipo_dj')==10)
                        <td>{{ $datos->tpo_capa }}</td>
                        <td>{{ $datos->provee_capa }}</td>
                        <td>{{ $datos->horas }}</td>
                        <td>{{ $datos->cto_directo }}</td>
                        <td>{{ $datos->cto_indirecto }}</td>
                        <td>{{ $datos->valor_capa }}</td>
                        <td>{{ $datos->materia_capa }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $cursos->appends(request()->query())->links() !!}
        </div>
    </div>
</div>
