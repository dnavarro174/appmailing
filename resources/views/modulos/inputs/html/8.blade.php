<input type="text" class="form-control timepicker-input" autocomplete="off" name="inputs[{{$input->id}}]"  id="inp-{{$input->id}}"
       {{$input->required?"required":""}} placeholder="Ingrese {{$input->title}}" value="{{$input->value}}"  />
