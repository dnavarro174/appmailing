@extends('layout.home')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          <div class="card" style="width:100%;background:#FFFFFF; border:none">
            <div class="card-body">
              <h4 class="card-title">Resultado de Importaci&oacute;n </h4>
              <div class="row">
                <div class="col-12">
                    <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                      <thead>
                          <tr role="row">
                            <th></th> 
                            <?php if($lista[0]["codigo"]){ ?><th>CODIGO</th><?php } ?>
                            <?php if($lista[0]["nombres"]){ ?><th>NOMBRES</th><?php } ?>
                            <?php if($lista[0]["ap_paterno"]){ ?><th>PATERNO</th><?php } ?>
                            <?php if($lista[0]["ap_materno"]){ ?><th>MATERNO</th><?php } ?>
                            <?php if($lista[0]["dni_doc"]){ ?><th>DNI</th><?php } ?>
                            <?php if($lista[0]["categoria"]){ ?><th>CATEGORIA</th><?php } ?>
                            <?php if($lista[0]["unidad_organica"]){ ?><th>UNIDAD ORGANICA</th><?php } ?>
                            <?php if($lista[0]["email"]){ ?><th>EMAIL</th><?php } ?>
                            
                          </tr>

                      </thead>
                    <tbody>                       
                       <?php foreach($lista as $lst){?>
                            <tr>
                              <td><?php echo $lst->mensaje; ?> </td>
                              <?php if($lst->codigo!=""){ ?><td><?php echo $lst->codigo; ?> </td><?php } ?>
                              <?php if($lst->nombres!=""){ ?><td><?php echo $lst->nombres; ?> </td><?php } ?>
                              <?php if($lst->ap_paterno!=""){ ?><td><?php echo $lst->ap_paterno; ?> </td><?php } ?>
                              <?php if($lst->ap_materno!=""){ ?><td><?php echo $lst->ap_materno; ?> </td><?php } ?>
                              <?php if($lst->dni_doc!=""){ ?><td><?php echo $lst->dni_doc; ?> </td><?php } ?>
                              <?php if($lst->categoria!=""){ ?><td><?php echo $lst->categoria; ?> </td><?php } ?>
                              <?php if($lst->unidad_organica!=""){ ?><td><?php echo $lst->unidad_organica; ?> </td><?php } ?>
                              <?php if($lst->email!=""){ ?><td><?php echo $lst->email; ?> </td><?php } ?>
                            </tr>                          
                          
                     <?php }?>
                    </tbody>


                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>