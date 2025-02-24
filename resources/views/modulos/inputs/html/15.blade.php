@php($file_="images/m_{$input->m_category_id}/{$input->value}")
@if($input->value != "" && file_exists($file_))
    <div>
    @if(stristr($input->value, '.jpg')||stristr($input->value, '.png')||stristr($input->value, '.jpeg'))
        <div class="d-flex align-items-end flex-column" id="content-remove-{{$input->id}}">
            <a title="Eliminar archivo" class="delete-file" tag="{{$input->id}}"><i class="mdi mdi-close text-danger icon-md"></i></a>
            <a  title="Restaurar archivo" class="restore-file d-none" tag="{{$input->id}}"><i class="mdi mdi-restore text-danger icon-md"></i></a>
            <img src="{{ asset($file_) }}" alt="" width="100%" id="content-file-{{$input->id}}">
        </div>
        @endif
    </div>
    <div id="content-link-{{$input->id}}"><a href="{{ asset($file_) }}">{{ $input->value }}</a></div>
@endif
@php($required = $input->required && $input->value=="" ? 1: 0)
@php($acc = $input->listAccess($data["exp"]["filetypesAceept"]))
@php($acc = $acc!="" ? 'accept="'.$acc.'"': "")
<div class="input-group">
    <input type="file" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
           placeholder="Ingrese {{$input->title}}" {{$required?"required":""}} {!! $acc !!}/>
    <div class="input-group-append">
        <button type="button" class="text-danger btn-reset-input-file border-0" tag="{{$input->id}}"><i class="mdi mdi-close text-danger icon-md"></i></button>
    </div>
</div>
