<div id="datepicker-popup-{{$input->id}}" class="input-group date datepicker datepicker-input">
    <input type="text" class="form-control form-border" name="inputs[{{$input->id}}]" value="{{$input->value}}"
           {{$input->required?"required":""}} placeholder="Ingrese {{$input->title}}" id="inp-{{$input->id}}">
    <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
</div>
