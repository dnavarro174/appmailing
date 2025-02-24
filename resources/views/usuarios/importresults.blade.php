@extends('layout.home')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          <div class="card" style="width:100%;background:#FFFFFF; border:none">
            <div class="card-body">
              <h4 class="card-title">Resultado de Importaci&oacute;n --</h4>
              <div class="row">
                <div class="col-12">
                    <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                      <thead>
                          <tr role="row">
                            <th></th> 
                            <?php if($lista[0]["nom_curso"]){ ?><th>NOMBRES</th><?php } ?>
                            <?php if($lista[0]["descripcion"]){ ?><th>DESCRIPCION</th><?php } ?>
                            <?php if($lista[0]["cat_curso_id"]){ ?><th>CATEGORIA</th><?php } ?>
                            <?php if($lista[0]["sesiones"]){ ?><th>SESIONES</th><?php } ?>
                            <?php if($lista[0]["horas_aca"]){ ?><th>HORAS ACAD</th><?php } ?>                  
                            <!-- <?php //if($lista[0]["cargo"]){ ?><th>CARGO</th><?php //} ?>
                            <?php //if($lista[0]["profesion"]){ ?><th>PROFESION</th><?php //} ?>
                            <?php //if($lista[0]["direccion"]){ ?><th>DIRECCION</th><?php //} ?>
                            <?php //if($lista[0]["telefono"]){ ?><th>TELEFONO</th><?php //} ?>
                            <?php //if($lista[0]["fecha_nac"]){ ?><th>F. NACIMIENTO</th><?php //} ?>
                            <?php //if($lista[0]["celular"]){ ?><th>CELULAR</th><?php //} ?>
                            <?php //if($lista[0]["sexo"]){ ?><th>SEXO</th><?php //} ?>
                            <?php // ==if($vEnt!=0){ ?><th>ENTIDAD</th><?php //} ?> -->
                            <!--<?php //if((int)$lista[0]["idEntidad"]!=0){ ?><th>ENTIDAD</th><?php // } ?>-->
                          </tr>

                      </thead>
                    <tbody>                       
                       <?php foreach($lista as $lst){?>
                            <tr>
                              <td><?php echo $lst->mensaje; ?> </td>
                              <?php if($lst->nom_curso!=""){ ?><td><?php echo $lst->nom_curso; ?> </td><?php } ?>
                              <?php if($lst->descripcion!=""){ ?><td><?php echo $lst->descripcion; ?> </td><?php } ?>
                              <?php if($lst->ap_paterno!=""){ ?><td><?php echo $lst->ap_paterno; ?> </td><?php } ?>
                              <?php if($lst->sesiones!=""){ ?><td><?php echo $lst->sesiones; ?> </td><?php } ?>
                              <?php if($lst->horas_aca!=""){ ?><td><?php echo $lst->horas_aca; ?> </td><?php } ?>
                              
                              
                            </tr>                          
                          
                     <?php }?>
                    </tbody>


                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>