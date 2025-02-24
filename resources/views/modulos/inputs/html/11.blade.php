@php($df = $input->getDataField($data))
@php($elements = $df["elements"])
@php($oo=$input->oj())
<select name="inputs[{{$input->id}}]" id="inp-{{$input->id}}" placeholder="Ingrese {{$input->title}}" class="form-control" {{$input->required?"required":""}}>
    @isset($oo["ph"])
        <option value="" selected disabled>{{ $oo["ph"] }}</option>
    @endisset
    @if(count($elements)>0)
        @foreach( $elements as $ee)
            @php($f_text = strtr($df["f_text"], json_decode(json_encode($ee), true)))
            <option value="{{ $ee->{$df["f_key"]} }}" {{ $ee->{$df["f_key"]} == $input->value  ?"selected":"" }}>{{ $f_text }}</option>
        @endforeach
    @endif
</select>
