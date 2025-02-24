@php($df = $input->getDataField($data))
@php($elements = $df["elements"])
@php($input->values = is_array($input->values)?$input->values:[])
<select name="inputs[{{$input->id}}][]" id="inp-{{$input->id}}" placeholder="Ingrese {{$input->title}}" multiple="multiple" class="form-control" {{$input->required?"required":""}}>
    <option value="" selected disabled></option>
    @if(count($elements)>0)
        @foreach( $elements as $ee)
            @php($f_text = strtr($df["f_text"], json_decode(json_encode($ee), true)))
            <option value="{{ $ee->{$df["f_key"]} }}" {{ $ee->{$df["f_key"]} }}" {{ in_array($ee->{$df["f_key"]}, $input->values)  ?"selected":"" }}>{{ $f_text }}</option>
        @endforeach
    @endif
</select>
