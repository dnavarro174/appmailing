@extends('layout.home')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          {{-- <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('estudiantes.import') }}" class="formarchivo" enctype="multipart/form-data" > --}}
          <form id="f_enviarInvitacionE" method="post" action=''>
            {!! csrf_field() !!}
            <div class="card" style="width:100%;background:#FFFFFF; border:none">
              <div class="card-body">
                <h4 class="card-title">Resultado de Importaci&oacute;n <span class="text-right"><a href="{{ route('est.importresults') }}" target="_blank">Ver informe</a></span></h4>

                <div class="row">
                  <div class="col-12">
                      <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                        <thead>
                            <tr role="row">
                              <th></th> 
                              <?php if($lista[0]["codigo"]){ ?><th>CODIGO</th><?php } ?>
                              <?php if($lista[0]["nombre"]){ ?><th>NOMBRE</th><?php } ?>
                              <?php if($lista[0]["linea"]){ ?><th>LINEA</th><?php } ?>
                              <?php if($lista[0]["tipo_control"]){ ?><th>TIPO</th><?php } ?>
                              <?php if($lista[0]["f_inicio"]){ ?><th>FECHA INICIO</th><?php } ?>
                              <?php if($lista[0]["f_final"]){ ?><th>FECHA FIN</th><?php } ?>                  
                              <?php if($lista[0]["h_inicio"]){ ?><th>HORA INICIO</th><?php } ?>                  
                              <?php if($lista[0]["h_final"]){ ?><th>HORA FIN</th><?php } ?>                  
                              <?php if($lista[0]["unidad_area"]){ ?><th>ÁREA</th><?php } ?>                  
                              <?php if($lista[0]["modalidad"]){ ?><th>MODALIDAD</th><?php } ?>                  
                              <?php if($lista[0]["sesiones"]){ ?><th>SESIONES</th><?php } ?>
                              <?php if($lista[0]["vacantes"]){ ?><th>VACANTES</th><?php } ?>
                              <?php if($lista[0]["h_cronologicas"]){ ?><th>H_CRONOLOGICAS</th><?php } ?>
                              <?php if($lista[0]["lugar"]){ ?><th>LUGAR</th><?php } ?>
                            </tr>

                        </thead>
                      <tbody>                       
                         <?php foreach($lista as $lst){?>
                              <tr>
                                <td><?php echo $lst->mensaje; ?> </td>
                                <?php if($lst->codigo!=""){ ?><td><?php echo $lst->codigo; ?> </td><?php } ?>
                                <?php if($lst->nombre!=""){ ?><td><?php echo $lst->nombre; ?> </td><?php } ?>
                                <?php if($lst->linea!=""){ ?><td><?php echo $lst->linea; ?> </td><?php } ?>
                                <?php if($lst->tipo_control!=""){ ?><td><?php echo $lst->tipo_control; ?> </td><?php } ?>
                                <?php if($lst->f_inicio!=""){ ?><td><?php echo $lst->f_inicio; ?> </td><?php } ?>
                                <?php if($lst->f_final!=""){ ?><td><?php echo $lst->f_final; ?> </td><?php } ?>
                                <?php if($lst->h_inicio!=""){ ?><td><?php echo $lst->h_inicio; ?> </td><?php } ?>
                                <?php if($lst->h_final!=""){ ?><td><?php echo $lst->h_final; ?> </td><?php } ?>
                                <?php if($lst->unidad_area!=""){ ?><td><?php echo $lst->unidad_area; ?> </td><?php } ?>
                                <?php if($lst->modalidad!=""){ ?><td><?php echo $lst->modalidad; ?> </td><?php } ?>
                                <?php if($lst->sesiones!=""){ ?><td><?php echo $lst->sesiones; ?> </td><?php } ?>
                                <?php if($lst->vacantes!=""){ ?><td><?php echo $lst->vacantes; ?> </td><?php } ?>
                                <?php if($lst->h_cronologicas!=""){ ?><td><?php echo $lst->h_cronologicas; ?> </td><?php } ?>
                                <?php if($lst->lugar!=""){ ?><td><?php echo $lst->lugar; ?> </td><?php } ?>
                              </tr>                          
                            
                       <?php }?>
                      </tbody>


                  </div>
                </div>
              </div>

              <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
                <div class="card bg-white" style="background:#f3f3f3 !important;" >
                  <div class="">
                    <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                  </div>
                </div>
              </div>{{-- msg cargando --}}

            </div>
          </form>
        </div> 
      </div>