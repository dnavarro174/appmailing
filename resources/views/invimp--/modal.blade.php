<div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ $action }}" class="formarchivo" enctype="multipart/form-data" >
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Importar Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    {{-- <div class="form-group row">
                      <h4 class="col-md-3 mt-1">Export</h4>
                      <div class="col-md-9">
                        <a href="{{ route('leads.export') }}" class="btn btn-secondary btn-block">Exportar</a>
                        <span class="help-block with-errors"></span>
                      </div>
                    </div> --}}
                    @if(True)
                        <div class="form-group row">
                            {{-- <h4 class="col-md-3 mt-1">Import</h4> --}}
                            <div class="col-md-12">
                                <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                                    <input type="file" name="file" id="archivo" class="dropify" required>
                                    <button type="button" class="dropify-clear">Quitar</button>

                                    <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

                                <span class="help-block with-errors"></span>

                            </div>
                        </div>
                    @else
                        <p>Evento finalizado.</p>
                    @endif
                    <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
                        <div class="card bg-white" style="background:#f3f3f3 !important;" >
                            <div class="">
                                <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                                <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                            </div>
                        </div>
                    </div>{{-- msg cargando --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-dark" id="btnImport1">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade ass" id="Modal_organizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
        <div class="modal-content" style="max-height: 600px;">

            <div class="card">
                <div class="card-body" style=" overflow: scroll;max-height:520px;">
                    <iframe src="{{ route('invimport.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none; border: 1px solid #e6e6e6;"></iframe>

                    <form class="form-inline"  id="estudiantesImportSave" name="estudiantesImportSave" action="{{ route('invimport.importsave') }}" method="post" >
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="rnr-is-control checkbox">
                                    <label> <input class="rnr-checkbox" id="chkPrimeraFila" name="chkPrimeraFila" type="checkbox" value="1" checked> <span class="text-small"> Cabeceras de columnas en la primera línea</span></label>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-3 d-none">
                                <div id="dateFormatSettings1" class="rnr-is-control form-group">
                                    <label class="pr-2 text-small" style="font-size: 15px">Fila Primer Valor: </label>
                                    <input id="txtFila" name="txtFila" type="text" value="1" class="form-control border-primary">
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-3">
                                <div id="dateFormatSettings1" class="rnr-is-control form-group text-small">
                                    <label class="pr-2" style="font-size: 15px">Formato fecha: </label>
                                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-primary">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="rnr-is-control checkbox">
                                    <label> <input class="rnr-checkbox" id="exclude" name="exclude" type="checkbox" value="1" checked> <span class="text-small">Excluir DNI</span></label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div id="dateFormatSettings1" class="rnr-is-control form-group">
                                    <label class="pr-2 text-small" style="font-size: 15px">Nombre: </label>
                                    <input id="nombre" name="nombre" type="text" value="{{ $default_nombre }}" class="form-control border-primary">
                                </div>
                            </div>
                            {{-- <div class="col-xs-12 col-sm-4">
                              <div class="rnr-is-control checkbox text-left">
                                <label class="d-flex justify-content-start text-dark font-weight-bold"> <input class="rnr-checkbox" id="chkE_invitacion" name="chkE_invitacion" type="checkbox" value="1" > Enviar Invitación</label>
                              </div>
                            </div> --}}
                            <div style="display:none;" id="cargador_excel2" class="content-wrapper p-0">{{-- end div cargando --}}
                                <div class="card bg-white text-center p-3 border0" style="background:#35b0ff !important;">
                                    <div class="row " style="display: flex;justify-content: center;">
                                        <label class="text-dark">&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                                        <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label class="text-dark">Cargando registros excel...</label>
                                    </div>
                                </div>
                            </div> {{-- end div cargando --}}
                        </div>

                        <div class="row">
                            <table id="tbl_estudiantes_imp_ord" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                            </table>
                            <input type="hidden" name="totCol" id="totCol">
                            <input type="hidden" name="hdnTabla" id="hdnTabla">
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div id="resultado" style="display:none;">Cargando...</div>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="btnRegresar" >Regresar</button>

                <button type="button" class="btn btn-secondary" id="btnCerrar" {{-- data-dismiss="modal" --}}>Cerrar</button>
                <button type="button" class="btn btn-dark" id="btnSumImport">Importar Datos</button>

            </div>

        </div>

    </div>
</div>
