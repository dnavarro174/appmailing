@extends('layout.home')
@section('content')

<div class="main-panel bg-white w-100">
        
        <div class="content-wrapper pt-0 bg-white" style="width: 100%;border:none;">
          {{-- <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('estudiantes.import') }}" class="formarchivo" enctype="multipart/form-data" > --}}
          <form id="f_enviarInvitacionE" method="post" action='{{ route('estudiantes.enviarI') }}'>
            {!! csrf_field() !!}
            <div class="card w-100 bg-white" style="overflow-x:scroll;">
              <div class="card-body">
                <h4 class="card-title">Resultado de Importaci&oacute;n <span class="text-right"><a href="{{ route('estudiantes.importresults') }}" target="_blank">Ver informe</a></span></h4>

                <div class="row">
                  <div class="col-12">
                      <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                        <thead>
                          <tr role="row">
                            <th><strong>IMPORTADOS: <?php echo $nlista; ?></strong></th> 
                            <?php if($lista[0]["dni_doc"]){ ?><th>DNI / PASAPORTE</th><?php } ?>
                            <?php if($lista[0]["nombres"]){ ?><th>NOMBRES</th><?php } ?>
                            <?php if($lista[0]["ap_paterno"]){ ?><th>AP_PATERNO</th><?php } ?>
                            <?php if($lista[0]["ap_materno"]){ ?><th>AP_MATERNO</th><?php } ?>
                            <?php if($lista[0]["grupo"]){ ?><th>GRUPO</th><?php } ?>                  
                            <?php if($lista[0]["pais"]){ ?><th>PAÍS</th><?php } ?>                  
                            <?php if($lista[0]["region"]){ ?><th>DEPARTAMENTO</th><?php } ?>
                            <?php if($lista[0]["profesion"]){ ?><th>PROFESIÓN</th><?php } ?>
                            <?php if($lista[0]["cargo"]){ ?><th>CARGO</th><?php } ?>
                            <?php if($lista[0]["organizacion"]){ ?><th>ENTIDAD / EMPRESA / ORGANIZACIÓN</th><?php } ?>                  
                            <?php if($lista[0]["email"]){ ?><th>CORREO ELECTRÓNICO PERSONAL</th><?php } ?>
                            <?php if($lista[0]["email_labor"]){ ?><th>EMAIL LABOR</th><?php } ?>
                            <?php if($lista[0]["celular"]){ ?><th>CELULAR</th><?php } ?>
                            <?php if($lista[0]["telefono"]){ ?><th>TELÉFONO</th><?php } ?>
                            <?php if($lista[0]["codigo_prog"]){ ?><th>ID EVENTO</th><?php } ?>
                            <?php if($lista[0]["sexo"]){ ?><th>REGISTRADO</th><?php } ?>
                            <?php if($lista[0]["direccion"]){ ?><th>ASISTIDO</th><?php } ?>
                          </tr>
                        </thead>
                      <tbody>                       
                         <?php foreach($lista as $lst){?>
                          <tr>
                            <td><?php echo $lst->mensaje; ?> </td>
                            <?php if($lst->dni_doc!=""){ ?><td><?php echo $lst->dni_doc; ?> </td><?php } ?>
                            <?php if($lst->nombres!=""){ ?><td><?php echo $lst->nombres; ?> </td><?php } ?>
                            <?php if($lst->ap_paterno!=""){ ?><td><?php echo $lst->ap_paterno;?></td><?php } ?>
                            <?php if($lst->ap_materno!=""){ ?><td><?php echo $lst->ap_materno;?></td><?php } ?>
                            <?php if($lst->grupo!=""){ ?><td><?php echo $lst->grupo; ?> </td><?php } ?>
                            <?php if($lst->pais!=""){ ?><td><?php echo $lst->pais; ?> </td><?php } ?>
                            <?php if($lst->region!=""){ ?><td><?php echo $lst->region; ?> </td><?php } ?>
                            <?php if($lst->profesion!=""){ ?><td><?php echo $lst->profesion; ?> </td><?php } ?>
                            <?php if($lst->cargo!=""){ ?><td><?php echo $lst->cargo;?></td><?php } ?>
                            <?php if($lst->organizacion!=""){ ?><td><?php echo $lst->organizacion;?> </td><?php } ?>
                            <?php if($lst->email!=""){ ?><td><?php echo $lst->email;?></td><?php } ?>
                            <?php if($lst->email_labor!=""){ ?><td><?php echo $lst->email_labor;?></td><?php } ?>
                            <?php if($lst->celular!=""){ ?><td><?php echo $lst->celular;?></td><?php } ?>
                            <?php if($lst->telefono!=""){ ?><td><?php echo $lst->telefono;?></td><?php } ?>
                            <?php if($lst->codigo_prog!=""){ ?><td><?php echo $lst->codigo_prog;?></td><?php } ?>
                            <?php if($lst->sexo!=""){ ?><td><?php echo $lst->sexo;?></td><?php } ?>
                            <?php if($lst->direccion!=""){ ?><td><?php echo $lst->direccion;?></td><?php } ?>
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