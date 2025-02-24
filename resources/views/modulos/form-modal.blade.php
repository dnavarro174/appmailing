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

                        <input type="hidden" id="ffield" name="ffield" value="">
                        <input type="hidden" name="fname" id="fname" value="">

                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label for="ftype">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control" id="ftype" name="ftype" required="required">
                                    <option value="" disabled>Seleccione</option>
                                    @foreach($fields as $v)
                                        <option @if($v->id == old('ftype')) selected @endif value="{{$v->id}}">{{$v->id}} - {{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <label for="ftitle">Titulo <span class="text-danger">*</span></label>
                                <input type="text" required="required" class="form-control"
                                       name="ftitle" id="ftitle" placeholder="Titulo" value="" />
                            </div>
                        </div>

                        <div class="row f-text">
                            <div class="col-sm-12 col-md-4 f-1 f-2 f-3 f-4">
                                <label for="fMaxlength">Longitud</label>
                                <input type="text" class="form-control"
                                       name="fMaxlength" id="fMaxlength" placeholder="Longitud" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-1 f-2 f-3 f-4 f-7 f-8 f-9 f-11">
                                <label for="fPlaceholder">Placeholder</label>
                                <input type="text" class="form-control"
                                       name="fPlaceholder" id="fPlaceholder" placeholder="Placeholder" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-2">
                                <label for="fEditor">Editor</label>
                                <select class="form-control" id="fEditor" name="fEditor">
                                    <option value="">Ninguno</option>
                                    <option value="1">Editor1</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fMin">Valor minimo</label>
                                <input type="text" class="form-control"
                                       name="fMin" id="fMin" placeholder="Valor" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fMax">Valor maximo</label>
                                <input type="text" class="form-control"
                                       name="fMax" id="fMax" placeholder="Valor" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fStep">Salto</label>
                                <input type="text"  class="form-control"
                                       name="fStep" id="fStep" placeholder="" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-4">
                                <br>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="fDominio">
                                    <label class="custom-control-label" for="fDominio">Mostrar Dominio</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 f-7 f-8 f-9">
                                <label for="fFormato">Formato</label>
                                <input type="text" class="form-control"
                                       name="fFormato" id="fFormato" placeholder="" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-5">
                                <label for="fGrupo">Grupo</label>
                                <input type="text" class="form-control"
                                       name="fGrupo" id="fGrupo" placeholder="Valor" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-25">
                                <label for="fAccept">Formatos Validos</label>
                                <input type="text" class="form-control"
                                       name="fAccept" id="fAccept" placeholder="" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-15">
                                <label for="fSize">Tamaño Maximo Archivo</label>
{{--                                <input type="text" class="form-control"--}}
{{--                                       name="fSize" id="fSize" placeholder="" value="" />--}}
                                <select class="form-control" id="fSize" name="fSize">
                                    <option value="" disabled selected>seleccione</option>
                                    @foreach($exp["filesizes"] as $ix=>$vx)
                                        <option  value="{{$vx}}">{{$vx}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-8 f-15">
                                <div class="py-3"></div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="chkPermite">
                                    <label class="custom-control-label" for="chkPermite">Permitir solo ciertos tipos de archivo</label>
                                </div>
                            </div>
                            @foreach($exp["filetypes"] as $ix=>$vx)
                            <div class="col-sm-12 col-md-6 f-15 chk-ft">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chk-ft-{{$loop->iteration}}" value="{{$ix}}" name="chkfts[]">
                                    <label class="custom-control-label" for="chk-ft-{{$loop->iteration}}">{{$vx}}</label>
                                </div>
                            </div>
                            @endforeach


                        </div>

                        <div class="row f-select" id="fv-select">
                            <div class="col-sm-12 col-md-4">
                                <label for="flatRadios1">Valores</label>
                                <div class="form-group form-inline">
                                    <div class="form-radio form-radio-flat py-0 my-0">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="fmanual" id="flatRadios1" value="0">
                                            Manual
                                            <i class="input-helper"></i></label>
                                    </div>
                                    <div class="form-radio form-radio-flat py-0 ml-2  my-0">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="fmanual" id="flatRadios2" value="1">
                                            Automatico
                                            <i class="input-helper"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 f-select-1">
                                <br/>
                                <button class="btn btn-success icon-btn p-1" type="button" id="btnAddField2">
                                    <i class="mdi mdi-plus text-white icon-md" ></i> Agregar valores
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-4 f-select-1 p-1">
                                <br/>
                                <button class="btn btn-danger icon-btn p-1" type="button" id="btnRemoveField">
                                    <i class="mdi mdi-minus text-white icon-md" ></i> Eliminar todos los valores
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-4 f-select-2">
                                <label for="ftype">Origen de datos</label>
                                <select class="form-control" id="fopt" name="fopt">
                                    @foreach($opts as $i=>$v)
                                        <option @if($i == old('fopt')) selected @endif value="{{$i}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 f-select-2">
                                <label for="fcampos">Campos que depende</label>
                                <select class="form-control" id="fcampos" name="fcampos" multiple size="2">
                                </select>
                            </div>
                        </div>
                        <div class="row f-select">
                            <div class="col-sm-12 p-2"  id="cbfields">
                            </div>
                        </div>

                        <div class="row f-questions">
                            <div class="col-sm-12">
                                <div id="questions">
                                </div>
                            </div>
                            <div class="col-sm-12 p-3">
                                <p>
                                    <a href="#" class="btn-link" id="add_question">+ Añadir pregunta</a>
                                </p>
                            </div>
                        </div>

                        <div class="row" id="fExp">
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fMinE">Valor minimo</label>
                                <input type="text" class="form-control"
                                       name="fMinE" id="fMinE" placeholder="Valor ..." value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fMaxE">Valor maximo</label>
                                <input type="text" class="form-control"
                                       name="fMaxE" id="fMaxE" placeholder="Valor ..." value="" />
                            </div>
                            <div class="col-sm-12 col-md-4 f-3">
                                <label for="fCountE">Cantidad de campos</label>
                                <input type="text" class="form-control"
                                       name="fCountE" id="fCountE" placeholder="Cantidad Predeterminado" value="" />
                            </div>
                        </div>

                        <div class="row f-condition">
                            <div class="col-sm-12 col-md-4">
                                <label for="fCond">Condicion</label>
                                <input type="text" class="form-control"
                                       name="fCond" id="fCond" placeholder="Valor" value="" />
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fEfecto">Efecto</label>
                                <select class="form-control" id="fEfecto" name="fEfecto">
                                    <option value="">Ninguno</option>
                                    <option value="1">Deshabilitar</option>
                                    <option value="2">Ocultar</option>
                                    <option value="3">Eliminar</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="campos2">Campos que afecta</label>
                                <select class="form-control" id="campos2" name="campos2" multiple size="2">
                                    <option value="" disabled>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        {{--                        BORRAR--}}
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
                            <div class="col-sm-12 col-md-8 pt-2 align-middle" >

                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-sm-12">
                                <label for="fsubtitle">Subtitulo</label>
                                <input type="text" class="form-control editor-sn"
                                       name="fsubtitle" id="fsubtitle" placeholder="Titulo" value="" />
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-12 col-md-9">
                                <label for="fnote">Nota</label>
                                <textarea placeholder="Nota" class="form-control editor-sn" name="fnote" id="fnote" cols="30" rows="2"></textarea>
                            </div>
                            <div class="col-sm-12 col-md-3 pt-2">
                                <div class="f-select-2">
                                    <button type="button" class="btn btn-danger" id="btnChange">Auto a Manual</button>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="frequired">
                                    <label class="custom-control-label" for="frequired">Requerido</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="fvisible">
                                    <label class="custom-control-label" for="fvisible">Visible</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="f_is_title_hidden">
                                    <label class="custom-control-label" for="f_is_title_hidden">Ocultar titulo</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="f_is_fullsize">
                                    <label class="custom-control-label" for="f_is_fullsize">Toda Fila</label>
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
                            <div class="col-sm-12 col-md-9" id="fDefault">
                                <label>Valor Predeterminado</label>
                                <div>
                                    <input type="text" class="form-control"
                                           name="fvalue" id="fvalue" placeholder="Valor predeterminado" value="" />
                                </div>
                                <div>
                                    <select class="form-control " id="fvalue2" name="fvalue2"></select>
                                </div>
                                <div>
                                    <select class="form-control" id="fvalue3" name="fvalue3" multiple></select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="fposition">Posicion <span class="text-danger">*</span></label>
                                <input type="text" required="required" class="form-control"
                                       name="fposition" id="fposition" placeholder="Posicion" value="" />
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
