<input type="text" class="form-control" name="inputs[{{$input->id}}]" value="{{$input->value}}" id="inp-{{$input->id}}"
       placeholder="Ingrese {{$input->title}}" {{$input->required?"required":""}} />
