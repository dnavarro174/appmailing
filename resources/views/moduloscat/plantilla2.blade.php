<div class="form-group row" id="content-{{$v->id}}">
    <label for="inp-{{$v->id}}" class="col-sm-12 col-md-4 col-lg-2 col-form-label d-block">{{$v->title}} <span class="text-danger">{{$v->required?'*':''}}</span></label>
    <div class="col-sm-10">
        <select name="" id="" class="form-control select-custom-2 select-input-plantilla" data-input-editor="inp-{{$v->id}}">
            @foreach($data["plantillas"] as $t)
                <option value="{{$t->id}}">{{$t->nombre}}</option>
            @endforeach
        </select>
        @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v, "form"=>1])
        <input type="hidden" name="f[{{$v->id}}]" value="{{$v->field}}">
        @include("moduloslead.form-note", ["note"=>$v->note])
    </div>
</div>
