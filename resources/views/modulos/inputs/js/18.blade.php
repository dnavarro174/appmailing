<div class="row" id="filas_contenedor_datos3">
    <div class="col-sm-12 col-md-4">
        <div class="form-group ">
            <label for="empresa_insti[empresa]">Empresa / Institución <span class="text-danger">*</span></label>
            <input type="text" class="form-control text-uppercase" name="empresa_insti[]" placeholder="Empresa / Institución" value="">
        </div>
    </div>


    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="tipo_empresa[]">Tipo de Empresa <span class="text-danger">*</span></label>
            <select class="form-control" name="tipo_empresa[]">
                <option value="">SELECCIONE / CHANGE</option>
                @foreach($exp["companytypes"] as $vl)<option value="{{ $vl }}" {{ isset($input)&&$input->value==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="cargo_puesto[]">Cargo Puesto <span class="text-danger">*</span></label>
            <select class="form-control" name="cargo_puesto[]">
                <option value="">SELECCIONE / CHANGE</option>
                @foreach($exp["cargos"] as $vl)<option value="{{ $vl }}" {{ isset($input)&&$input->value==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="modalidad[]">Modalidad <span class="text-danger">*</span></label>
            <select class="form-control" name="modalidad[]">
                <option value="">SELECCIONE / CHANGE</option>
                @foreach($exp["companytypes"] as $vl)<option value="{{ $vl }}" {{ isset($input)&&$input->value==$vl ? "selected": "" }}>{{ $vl }}</option>@endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group ">
            <label for="actividad_desarrollada[]">Actividad Desarrollada </label>
            <input type="text" class="form-control text-uppercase" name="actividad_desarrollada[]" value="">
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group ">
            <label for="fecha_inicio[]">Fecha Inicio <span class="text-danger">*</span> </label>
            <div class="input-group mb-2">
                <input type="date" class="form-control" name="fecha_inicio[]" placeholder="07/01/2023" value="">
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group ">
            <label for="fecha_term[]">Fecha de Término <span class="text-danger">*</span> </label>
            <div class="input-group mb-2">
                <input type="date" class="form-control" name="fecha_term[]" placeholder="07/01/2023" value="">
            </div>
        </div>
    </div>

</div>
