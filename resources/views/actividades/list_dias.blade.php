
    <div class="form-group row">
      
      <div class="col-md-12" id="detActividad">
        <ul>
          <?php $i = 1; ?>
          @foreach ($dias as $key => $dia) 
              <li><a href="{{ url('') }}/actividades_fecha/{{$evento_id}}/{{str_replace('/','-',$dia['fecha'])}}" target="_blank">{{$dia["fecha"]}}</a> 
                <a href="#" class="addAct bg-light ml-3 rounded-circle addAct2" onclick="formActividad('{{$evento_id}}','{{str_replace('/','-',$dia['fecha'])}}','0', '{{$i}}','{{ url('') }}')" title='Crear Actividad' data-toggle="modal" data-target="Modal_add_actividad"> 
                      <i class="mdi mdi-plus text-black icon-sm"></i>
                    </a>
              </li>
              <?php $i++; ?>
          @endforeach                      

        </ul>
        

      </div>
    </div>
