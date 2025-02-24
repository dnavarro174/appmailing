@php($df = $input->getDataField($data))
@php($elements = $df["elements"])
@php($input->values = is_array($input->values)?$input->values:[])
@if(count($elements)>0)
    @foreach( $elements as $i=>$ee)
        @php($f_text = strtr($df["f_text"], json_decode(json_encode($ee), true)))
        <div class="form-check">
            <input type="checkbox" id="inp-{{$input->id}}-o-{{$i}}" name="inputs[{{$input->id}}][]" class="form-check-input check_click" value="{{ $ee->{$df["f_key"]} }}" {{ in_array($ee->{$df["f_key"]}, $input->values)   ?"checked":"" }}>
            <label class="form-check-label" for="inp-{{$input->id}}-o-{{$i}}">{{ $f_text }} <i class="input-helper"></i></label>
        </div>
    @endforeach
@endif
