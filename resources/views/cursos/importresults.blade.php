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
                            <?php if($lista[0]["cod_curso"]){ ?><th>CODIGO</th><?php } ?>
                            <?php if($lista[0]["nom_curso"]){ ?><th>NOMBRE_CURSO</th><?php } ?>
                            <?php if($lista[0]["modalidad"]){ ?><th>MODALIDAD</th><?php } ?>
                            <?php if($lista[0]["fech_ini"]){ ?><th>FECHA_INICIO</th><?php } ?>
                            <?php if($lista[0]["fech_fin"]){ ?><th>FECHA_FIN</th><?php } ?>
                            @if(session('tipo_dj')==10)
                            <?php if($lista[0]["tpo_capa"]){ ?><th>TIPO CAPACITACION</th><?php } ?>
                            <?php if($lista[0]["provee_capa"]){ ?><th>PROVEEDOR CAPACITACION</th><?php } ?>
                            <?php if($lista[0]["horas"]){ ?><th>HORAS CAPACITACION</th><?php } ?>
                            <?php if($lista[0]["cto_directo"]){ ?><th>COSTOS DIRECTOS</th><?php } ?>
                            <?php if($lista[0]["cto_indirecto"]){ ?><th>COSTOS INDIRECTOS</th><?php } ?>
                            <?php if($lista[0]["valor_capa"]){ ?><th>VALOR DE LA CAPACITACION</th><?php } ?>
                            <?php if($lista[0]["materia_capa"]){ ?><th>MATERIA DE CAPACITACION</th><?php } ?>
                            @endif
                          </tr>

                      </thead>
                    <tbody>                       
                       <?php foreach($lista as $lst){?>
                            <tr>
                              <td><?php echo $lst->mensaje; ?> </td>
                              <?php if($lst->cod_curso!=""){ ?><td><?php echo $lst->cod_curso; ?> </td><?php } ?>
                              <?php if($lst->nom_curso!=""){ ?><td><?php echo $lst->nom_curso; ?> </td><?php } ?>
                              <?php if($lst->modalidad!=""){ ?><td><?php echo $lst->modalidad; ?> </td><?php } ?>
                              <?php if($lst->fech_ini!=""){ ?><td><?php echo $lst->fech_ini; ?> </td><?php } ?>
                              <?php if($lst->fech_fin!=""){ ?><td><?php echo $lst->fech_fin; ?> </td><?php } ?>
                              
                              @if(session('tipo_dj')==10)
                              <?php if($lst->tpo_capa!=""){ ?><td><?php echo $lst->tpo_capa; ?> </td><?php } ?>
                              <?php if($lst->provee_capa!=""){ ?><td><?php echo $lst->provee_capa; ?> </td><?php } ?>
                              <?php if($lst->horas!=""){ ?><td><?php echo $lst->horas; ?> </td><?php } ?>
                              <?php if($lst->cto_directo!=""){ ?><td><?php echo $lst->cto_directo; ?> </td><?php } ?>
                              <?php if($lst->cto_indirecto!=""){ ?><td><?php echo $lst->cto_indirecto; ?> </td><?php } ?>
                              <?php if($lst->valor_capa!=""){ ?><td><?php echo $lst->valor_capa; ?> </td><?php } ?>
                              <?php if($lst->materia_capa!=""){ ?><td><?php echo $lst->materia_capa; ?> </td><?php } ?>
                              @endif
                            </tr>                          
                          
                     <?php }?>
                    </tbody>


                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>