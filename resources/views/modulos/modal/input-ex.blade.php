@php($exps = $exps??[$expdata["emptyExp"]])
@foreach($exps as $e)
    @php($iix=isset($jsd)?$jsd["index"]:$loop->iteration)
    @php($iid=isset($jsd)?$jsd["id"]:$input->id)
    @php($icls=$iix>1?"":"d-none")

    <div class="row contenedor-{{$iid}} c-ex" tag="{{$iix}}">
        <hr class="w-100 border-secondary border-top mt-2 pt-2 btn-linea {{$icls}}">
        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-empresa">Empresa / Institución <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" placeholder="Empresa / Institución"
                       id="input-{{$iid}}-{{$iix}}-empresa" name="inputs[{{$iid}}][{{$iix}}][empresa]" value="{{$e["empresa"]}}">
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="input-{{$iid}}-{{$iix}}-tipo">Tipo de Empresa <span class="text-danger">*</span></label>
                <select class="form-control" id="input-{{$iid}}-{{$iix}}-tipo" name="inputs[{{$iid}}][{{$iix}}][tipo]">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($expdata["companytypes"] as $vl)<option value="{{ $vl }}" {{ $e["tipo"]==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="input-{{$iid}}-{{$iix}}-puesto">Cargo Puesto <span class="text-danger">*</span></label>
                <select class="form-control" id="input-{{$iid}}-{{$iix}}-puesto" name="inputs[{{$iid}}][{{$iix}}][puesto]">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($expdata["cargos"] as $vl)<option value="{{ $vl }}" {{  $e["puesto"]==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="input-{{$iid}}-{{$iix}}-modalidad">Modalidad <span class="text-danger">*</span></label>
                <select class="form-control" id="input-{{$iid}}-{{$iix}}-modalidad" name="inputs[{{$iid}}][{{$iix}}][modalidad]">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($expdata["modalities"] as $vl)<option value="{{ $vl }}" {{ $e["modalidad"]==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-actividad">Actividad Desarrollada </label>
                <input type="text" class="form-control text-uppercase" id="input-{{$iid}}-{{$iix}}-actividad" name="inputs[{{$iid}}][{{$iix}}][actividad]" value="{{ $e["actividad"]}}">
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-inicio">Fecha Inicio <span class="text-danger">*</span> </label>
                <div class="input-group mb-2">
                    <input type="date" class="form-control" placeholder="{{date("d/m/Y")}}"
                           id="input-{{$iid}}-{{$iix}}-inicio" name="inputs[{{$iid}}][{{$iix}}][inicio]" value="{{ $e["inicio"]}}">
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-termino">Fecha de Término <span class="text-danger">*</span> </label>
                <div class="input-group mb-2">
                    <input type="date" class="form-control" placeholder="{{date("d/m/Y")}}"
                           id="input-{{$iid}}-{{$iix}}-termino" name="inputs[{{$iid}}][{{$iix}}][termino]" value="{{ $e["termino"]}}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 btn-del  {{$icls}}">
            <p><a href="#" class="btn btn-sm btn-danger btn-delete-exp"><span>Quitar</span></a></p>
        </div>
    </div>
@endforeach
