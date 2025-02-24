@extends('layout.home')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          {{-- <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('estudiantes.import') }}" class="formarchivo" enctype="multipart/form-data" > --}}
          <form id="f_enviarInvitacionE" method="post" action='{{ route('estudiantes.enviarI') }}'>
            {!! csrf_field() !!}
            <div class="card" style="width:100%;background:#FFFFFF; border:none">
              <div class="card-body">
                <h4 class="card-title">Resultado de Importaci&oacute;n <span class="text-right"><a href="{{ route('estudiantes.importresults') }}" target="_blank">Ver informe</a></span></h4>

                <div class="row">
                  <div class="col-12">
                      <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                        <thead>
                            <tr role="row">
                              <th></th> 
                              <?php if($lista[0]["dni_doc"]){ ?><th>DNI</th><?php } ?>
                              <?php if($lista[0]["nombres"]){ ?><th>NOMBRES</th><?php } ?>
                              <?php if($lista[0]["ap_paterno"]){ ?><th>APE PATERNO</th><?php } ?>
                              <?php if($lista[0]["ap_materno"]){ ?><th>APE MATERNO</th><?php } ?>
                              <?php if($lista[0]["email"]){ ?><th>EMAIL</th><?php } ?>                  
                              <?php if($lista[0]["email_labor"]){ ?><th>EMAIL LABOR</th><?php } ?>                  
                              <?php if($lista[0]["codigo_prog"]){ ?><th>COD_PROG</th><?php } ?>                  
                              <?php if($lista[0]["IDACTIVIDAD"]){ ?><th>IDACTIVIDAD</th><?php } ?>                  
                              <?php if($lista[0]["organizacion"]){ ?><th>ORGANIZACIÓN</th><?php } ?>                  
                              <?php if($lista[0]["cargo"]){ ?><th>CARGO</th><?php } ?>
                              <?php if($lista[0]["profesion"]){ ?><th>PROFESION</th><?php } ?>
                              <?php if($lista[0]["direccion"]){ ?><th>DIRECCION</th><?php } ?>
                              <?php if($lista[0]["telefono"]){ ?><th>TELEFONO</th><?php } ?>
                              <?php if($lista[0]["telefono_labor"]){ ?><th>TEL.LABOR</th><?php } ?>
                              <?php if($lista[0]["fecha_nac"]){ ?><th>F. NACIMIENTO</th><?php } ?>
                              <?php if($lista[0]["celular"]){ ?><th>CELULAR</th><?php } ?>
                              <?php if($lista[0]["sexo"]){ ?><th>SEXO</th><?php } ?>
                              <?php if($vEnt!=0){ ?><th>ENTIDAD</th><?php } ?>
                              <!--<?php //if((int)$lista[0]["idEntidad"]!=0){ ?><th>ENTIDAD</th><?php // } ?>-->

                              {{-- falta campo CODIGO PROGRAMACION --}}
                            </tr>

                        </thead>
                      <tbody>                       
                         <?php foreach($lista as $lst){?>
                              <tr>
                                <td><?php echo $lst->mensaje; ?> </td>
                                <?php if($lst->dni_doc!=""){ ?><td><?php echo $lst->dni_doc; ?> </td><?php } ?>
                                <?php if($lst->nombres!=""){ ?><td><?php echo $lst->nombres; ?> </td><?php } ?>
                                <?php if($lst->ap_paterno!=""){ ?><td><?php echo $lst->ap_paterno; ?> </td><?php } ?>
                                <?php if($lst->ap_materno!=""){ ?><td><?php echo $lst->ap_materno; ?> </td><?php } ?>
                                <?php if($lst->email!=""){ ?><td><?php echo $lst->email; ?> </td><?php } ?>
                                <?php if($lst->email_labor!=""){ ?><td><?php echo $lst->email_labor; ?> </td><?php } ?>
                                <?php if($lst->codigo_prog!=""){ ?><td><?php echo $lst->codigo_prog; ?> </td><?php } ?>
                                <?php if($lst->IDACTIVIDAD!=""){ ?><td><?php echo $lst->IDACTIVIDAD; ?> </td><?php } ?>
                                <?php if($lst->organizacion!=""){ ?><td><?php echo $lst->organizacion; ?> </td><?php } ?>
                                <?php if($lst->cargo!=""){ ?><td><?php echo $lst->cargo; ?> </td><?php } ?>
                                <?php if($lst->profesion!=""){ ?><td><?php echo $lst->profesion; ?> </td><?php } ?>
                                <?php if($lst->direccion!=""){ ?><td><?php echo $lst->direccion; ?> </td><?php } ?>
                                <?php if($lst->telefono!=""){ ?><td><?php echo $lst->telefono; ?> </td><?php } ?>
                                <?php if($lst->telefono_labor!=""){ ?><td><?php echo $lst->telefono_labor; ?> </td><?php } ?>
                                <?php if($lst->fecha_nac!=""){ ?><td><?php echo $lst->fecha_nac; ?> </td><?php } ?>
                                <?php if($lst->celular!=""){ ?><td><?php echo $lst->celular; ?> </td><?php } ?>
                                <?php if($lst->sexo!=""){ ?><td><?php echo $lst->sexo; ?> </td><?php } ?>
                                <?php if((int)$lst->idEntidad!=0){ ?><td><?php echo $lst->entidad; ?> </td><?php } ?>
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