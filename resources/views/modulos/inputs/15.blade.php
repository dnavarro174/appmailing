@php($file_="images/m_{$input->m_category_id}/{$input->value}")
@if($input->value != "" && file_exists($file_))
    @if(stristr($input->value, '.jpg')||stristr($input->value, '.png')||stristr($input->value, '.jpeg'))
    <img src="{{ asset($file_) }}" alt="" width="100%">
    @endif
    <div><a href="{{ asset($file_) }}">{{ $input->value }}</a></div>
@endif
@php($required = $input->required && $input->value=="" ? 1: 0)
@php($acc = $input->listAccess($data["exp"]["filetypesAceept"]))
@php($acc = $acc!="" ? 'accept="'.$acc.'"': "")
<input type="file" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
       placeholder="Ingrese {{$input->title}}" {{$required?"required":""}} {!! $acc !!}/>
