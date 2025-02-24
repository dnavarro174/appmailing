@isset($form)
    <div class="form-group row" id="content-{{$v->id}}">
        <label for="inp-{{$v->id}}" class="col-sm-12 col-form-label d-block">{{$v->title}} <span class="text-danger">{{$v->required?'*':''}}</span></label>
        <div class="col-sm-12">
            @include("moduloslead.form-subtitle", ["subtitle"=>$v->subtitle])
            @include("moduloslead.form-note", ["note"=>$v->note])
        </div>
    </div>
@else

@endif
