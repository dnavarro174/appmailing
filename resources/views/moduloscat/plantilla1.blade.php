<div class="form-group row" id="content-{{$v->id}}">
    <div class="col-sm-12 col-md-4">
        <label for="inp-{{$v->id}}" class="col-form-label d-block">{{$v->title}} <span class="text-danger">{{$v->required?'*':''}}</span></label>
        <div style="height: 250px;  overflow: auto">
            @foreach($data["plantillas"] as $t)
                <a class="text-small select-link-plantilla d-block" href="#" data-input-editor="inp-{{$v->id}}" tag="{{$t->id}}">{{$t->nombre}}</a>
            @endforeach
        </div>
    </div>
    <div class="col-sm-12 col-md-8" style="height: 300px;  overflow: auto">
        @include('modulos.inputs.html.'.$v->m_field_id, ["input"=>$v, "form"=>1])
        <input type="hidden" name="f[{{$v->id}}]" value="{{$v->field}}">
        @include("moduloslead.form-note", ["note"=>$v->note])
    </div>
</div>
