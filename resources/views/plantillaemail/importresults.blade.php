@extends('layout.home')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          <div class="card" style="width:100%;background:#FFFFFF; border:none">
            <div class="card-body">
              <h4 class="card-title">Resultado de Importaci&oacute;n</h4>
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
                            <?php if($lista[0]["cargo"]){ ?><th>CARGO</th><?php } ?>
                            <?php if($lista[0]["profesion"]){ ?><th>PROFESION</th><?php } ?>
                            <?php if($lista[0]["direccion"]){ ?><th>DIRECCION</th><?php } ?>
                            <?php if($lista[0]["telefono"]){ ?><th>TELEFONO</th><?php } ?>
                            <?php if($lista[0]["fecha_nac"]){ ?><th>F. NACIMIENTO</th><?php } ?>
                            <?php if($lista[0]["celular"]){ ?><th>CELULAR</th><?php } ?>
                            <?php if($lista[0]["sexo"]){ ?><th>SEXO</th><?php } ?>
                            <?php if($vEnt!=0){ ?><th>ENTIDAD</th><?php } ?>
                            <!--<?php //if((int)$lista[0]["idEntidad"]!=0){ ?><th>ENTIDAD</th><?php // } ?>-->
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
                              <?php if($lst->cargo!=""){ ?><td><?php echo $lst->cargo; ?> </td><?php } ?>
                              <?php if($lst->profesion!=""){ ?><td><?php echo $lst->profesion; ?> </td><?php } ?>
                              <?php if($lst->direccion!=""){ ?><td><?php echo $lst->direccion; ?> </td><?php } ?>
                              <?php if($lst->telefono!=""){ ?><td><?php echo $lst->telefono; ?> </td><?php } ?>
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
          </div>
        </div> 
      </div>