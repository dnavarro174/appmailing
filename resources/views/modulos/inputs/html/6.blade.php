@isset($form)
    <div class="col-2 col-sm-none">&nbsp</div>
    <div class="col-10 col-sm-12">
@endif
        <div class="form-check">
            <input type="checkbox" id="inp-{{$v->id}}" name="inputs[{{$v->id}}]" class="form-check-input check_click" value="1" {{ $v->value ==1 ? 'checked': '' }}/>
            <label class="form-check-label" for="inp-{{$v->id}}"> <i class="input-helper"></i>
                <div>{{$v->title}}</div>
                <div class="txt_subtitulo text-small text-gray mb-2 ml-2">{!!$v->subtitle!!}</div>
                <div class="txt_nota text-small text-danger mt-2 ml-2">{!!$v->note!!}</div>
            </label>
        </div>
@isset($form)
    </div>
@endif
