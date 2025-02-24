<div class="row col-sm-12 piso_8">
  <h5>Listado de Aulas</h5>
</div>
<div class="row col-sm-12 piso_8">
                          @foreach($aulas as $a)
                          <?php
                          if($a->nombre_aulas=="LAB 1" or $a->nombre_aulas=="LAB 2"){
                            $col = "col-sm-6";
                          }else{
                            $col = "col-sm-3";
                          }
                          ?>
                            <div class="{{$col}}  border @if($a->ocupado&&$a->id!=$datos->aulas_id) bg-danger @else bg-light @endif">
                              <div class="form-group">
                                <div class="form-check">
                                    <div class="col-sm-12 form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input @if($a->ocupado&&$a->id!=$datos->aulas_id) disabled="" @endif @if($a->id==$datos->aulas_id)checked @endif id="aulas_{{$a->id}}" name="aulas_id" type="radio" class="form-check-input" value="{{$a->id}}">{{$a->nombre_aulas}}<i class="input-helper"></i><i class="input-helper"></i></label>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          @endforeach

                        </div>