@isset($form)

@else
    <div class="exp" tag="{{$v->id}}">
        <h4 class="card-title mt-4">{{$v->title}}</h4>
        <div class="exp-row">
            @include('modulos.modal.input-ex2', ["input"=>$v,"exps"=>$exps])
        </div>

        <div class="row">
            <div class="col-sm-12">
                <p>
                    <a href="#" class="btn-link add-exp" tag="2">+  Añadir Experiencia en Docencia</a>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @include('moduloslead.form-note', ["subtitle"=>trim($v->subtitle)])
        </div>
    </div>
@endif
