<div class="modal" id="ModalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top:2%; ">
        <form id="formField" name="formField" action="" method="post" onsubmit="return false" >
            <div class="modal-content" >
                <div class="card">
                    <div class="card-body" style=" overflow: scroll; ">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fid" id="fid" value="">
                        <input type="hidden" name="findex" id="findex" value="">
                        <input type="hidden" name="is_detail" id="is_detail" value="">

                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label for="ffield">Campo</label>
                                <select class="form-control" id="ffield" name="ffield">
                                    <option value="0">Personalizado</option>
                                    @foreach($attrs as $v)
                                        <option @if($v->id == old('ffield')) selected @endif value="{{$v->id}}" tag="{{$v->is_detail}}">{{$v->id}} - {{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="ftype">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control" id="ftype" name="ftype" required="required">
                                    <option value="" disabled>Seleccione</option>
                                    @foreach($fields as $v)
                                        <option @if($v->id == old('ftype')) selected @endif value="{{$v->id}}">{{$v->id}} - {{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fname">Nombre <span class="text-danger">*</span></label>
                                <input type="text" required="required" class="form-control"
                                       name="fname" id="fname" placeholder="Nombre" value="" />
                            </div>
                        </div>

                        <div class="row d-none" id="fvselect">
                            <div class="col-sm-12 col-md-4">
                                <label for="fopt">Valores</label>
                                <select class="form-control" id="fopt" name="fopt">
                                    @foreach($opts as $i=>$v)
                                        <option @if($i == old('fopt')) selected @endif value="{{$i}}">{{$v}}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-success btn-sm icon-btn my-2 float-right" type="button" id="btnAddField">
                                    <i class="mdi mdi-plus text-white icon-md" ></i> Agregar
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-8 pt-2 align-middle" id="cbfields">

                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-sm-12 col-md-6">
                                <label for="ftitle">Titulo <span class="text-danger">*</span></label>
                                <input type="text" required="required" class="form-control"
                                       name="ftitle" id="ftitle" placeholder="Titulo" value="" />
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="fsubtitle">Subtitulo</label>
                                <input type="text" class="form-control"
                                       name="fsubtitle" id="fsubtitle" placeholder="Titulo" value="" />
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-12 col-md-9">
                                <label for="fnote">Nota</label>
                                <textarea placeholder="Nota" class="form-control" name="fnote" id="fnote" cols="30" rows="2"></textarea>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <br>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="frequired">
                                    <label class="custom-control-label" for="frequired">Requerido</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="fvisible">
                                    <label class="custom-control-label" for="fvisible">Visible</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-12">
                                <label for="fsize" class="col-sm-12">Estilo</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" required="required" class="form-control"
                                       name="fsize" id="fsize" placeholder="Tamaño" value="" min="8" max="72" />
                            </div>
                            <div class="col-sm-4">
                                <input type="text" required="required" class="form-control color-picker"
                                       name="fcolor" id="fcolor" placeholder="Color" value="" />
                            </div>
                            <div class="col-sm-4">
                                <input type="text" required="required" class="form-control"
                                       name="ffont" id="ffont" placeholder="Fuente" value="" />
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-12 col-md-9">
                                <label for="fvalue">Valor Predeterminado</label>
                                <input type="text" class="form-control"
                                       name="fvalue" id="fvalue" placeholder="Nombre" value="" />
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="ftitle">Posicion <span class="text-danger">*</span></label>
                                <input type="text" required="required" class="form-control"
                                       name="fposition" id="fposition" placeholder="Posicion" value="" />
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-12">
                                <label for="fstyle">Estilo</label>
                                <input type="text" class="form-control"
                                       name="fstyle" id="fstyle" placeholder="Estilo" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="resultado" style="display:none;">Cargando...</div>
                    <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                    <button type="button" class="btn btn-secondary" id="btn-cerrar">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnOK">Aceptar</button>
                </div>

            </div>
        </form>
    </div>
</div>
{{-- form importar --}}
