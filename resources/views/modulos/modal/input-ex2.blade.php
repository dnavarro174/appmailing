@php($exps = $exps??[$expdata["emptyExp2"]])
@foreach($exps as $e)
    @php($iix=isset($jsd)?$jsd["index"]:$loop->iteration)
    @php($iid=isset($jsd)?$jsd["id"]:$input->id)
    @php($icls=$iix>1?"":"d-none")

    <div class="row contenedor-{{$iid}} c-ex" tag="{{$iix}}">
        <hr class="w-100 border-secondary border-top mt-2 pt-2 btn-linea {{$icls}}">
        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="input-{{$iid}}-{{$iix}}-ins_tipo">Institución <span class="text-danger">*</span></label>
                <select  class="form-control" required id="input-{{$iid}}-{{$iix}}-ins_tipo" name="inputs[{{$iid}}][{{$iix}}][ins_tipo]">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($expdata["ins_tipos"] as $vl)<option value="{{ $vl }}" {{ isset($e["ins_tipo"])&&$e["ins_tipo"]==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-ins_nombre">Nombre de la Institución  </label>
                <input type="text" class="form-control text-uppercase"  id="input-{{$iid}}-{{$iix}}-ins_nombre" name="inputs[{{$iid}}][{{$iix}}][ins_nombre]" value="{{$e["ins_nombre"]}}">
            </div>
        </div>


        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="input-{{$iid}}-{{$iix}}-nivel">Nivel<span class="text-danger">*</span></label>
                <select class="form-control" id="input-{{$iid}}-{{$iix}}-nivel" name="inputs[{{$iid}}][{{$iix}}][nivel]">
                    <option value="">SELECCIONE / CHANGE</option>
                    @foreach($expdata["niveles"] as $vl)<option value="{{ $vl }}" {{  $e["nivel"]==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-12 col-md-4">
            <div class="form-group ">
                <label for="input-{{$iid}}-{{$iix}}-curso">Curso a Cargo </label>
                <input type="text" class="form-control text-uppercase" id="input-{{$iid}}-{{$iix}}-curso" name="inputs[{{$iid}}][{{$iix}}][curso]" value="{{ $e["curso"]}}">
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
