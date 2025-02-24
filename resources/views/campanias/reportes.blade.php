@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <div class="main-panel">
        <div class="content-wrapper mt-3">
          <div class="container">
            <div class="row">
              <p class="card-text mb-0">
                <a href="{{ route('campanias.index') }}" class="btn btn-link">
                  <i class="mdi text-link mdi-keyboard-backspace"></i>
                Volver al listado</a>
              </p>
              <div class="col-xl-12 col-sm-12 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">

                      <div class="wrapper">
                        <p class="card-text mb-0">Campaña: </p>
                        <div class="fluid-container">
                          <h3 class="card-title mb-0">{{$campania->nombre}}</h3>
                          <p class="mb-0"><strong>Asunto: </strong>
                            {!! $campania->asunto !!}
                          </p>
                          <p><strong>Filtros: </strong>
                            {{$campania->grupo}} / {{$campania->pais}} / {{$campania->region}}
                            @if($campania->Evento)
                            <br>
                            <strong>Evento: </strong>{{ $campania->Evento->nombre_evento }}
                            @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3">
                        <i class="mdi mdi-briefcase-check text-primary icon-lg"></i>
                      </div>
                      <div class="wrapper">
                        <p class="card-text mb-0">TOTAL PARTICIPANTES</p>
                        <div class="fluid-container">
                          <h3 class="card-title mb-0">{{$total["count"]}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3">
                        <i class="mdi mdi-check-all text-info icon-lg"></i>
                      </div>
                      <div class="wrapper">
                        <p class="card-text mb-0">ENTREGADOS</p>
                        <div class="fluid-container">
                          <h3 class="card-title mb-0">{{$total["enviado"]}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3">
                        <i class="mdi mdi-cube text-success icon-lg"></i>
                      </div>
                      <div class="wrapper">
                        <p class="card-text mb-0">EN PROCESO</p>
                        <div class="fluid-container">
                          <h3 class="card-title mb-0">{{$total["pendiente"]}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3">
                        <i class="mdi mdi-link-variant-off text-danger icon-lg"></i>
                      </div>
                      <div class="wrapper">
                        <p class="card-text mb-0">REBOTADOS</p>
                        <div class="fluid-container">
                          <h3 class="card-title mb-0">{{$total["rebotados"]}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="row">
             <div class="col-md-4 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Resumen de campaña</h5>

                    <div class="d-flex align-items-start pb-3 pt-1 mb-4 border-bottom">
                      <i class="mdi mdi-check-all icon-lg text-info"></i>
                      
                      <div class="wrapper w-100 pl-3">
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="badge badge-dark badge-lg mb-2">ENTREGADOS</span>
                          
                          <span class="text-gray text-small">{{$total["enviado"]}}</span>
                        </div>
                        <p>Emails se enviarón satisfactoriamente.</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-start pb-3 pt-1 mb-4 border-bottom">
                      <i class="mdi mdi-cube icon-lg text-success"></i>
                      <div class="wrapper w-100 pl-3">
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="badge badge-dark badge-lg mb-2">EN PROCESO</span>
                          <span class="text-gray text-small">{{$total["pendiente"]}}</span>
                        </div>
                        <p>Emails aún estan en proceso</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-start pb-3 pt-1 mb-4 border-bottom">
                      <i class="mdi mdi-link-variant-off icon-lg text-danger"></i>
                      <div class="wrapper w-100 pl-3">
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="badge badge-dark badge-lg mb-2">REBOTADOS</span>
                          <span class="text-gray text-small">{{$total["rebotados"]}}</span>
                        </div>
                        <p>Emails por corregir o eliminar</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-start pt-1" style="display: none !important;">
                      <i class="mdi mdi-comment-account text-info icon-lg"></i>
                      <div class="wrapper w-100 pl-3">
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="badge badge-dark badge-lg mb-2">CLICK EN ENLACE</span>
                          <span class="text-gray text-small">1</span>
                        </div>
                        <p>Usuarios dieron click en el botón de acción</p>
                      </div>
                    </div>


                  </div>
                </div>
              </div>


              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <h5 class="card-title mb-0">Corregir Emails </h5>
                      <small class="text-gray d-none d-sm-block">Por corregir: {{$lista1_total}} email{{$lista1_total!=1?'s':''}}</small>
                    </div>
                    <p>
                      <a href="{{ route('campanias.emails_errores',['id'=>$campania->id]) }}" target="_blank">Ver todos los emails fallidos </a>
                    </p>
                    <div class="new-accounts">


                        @csrf
                        <ul class="chats ps">
                          @forelse($lista1 as $reg)
                          <!--<form action="{{route('campanias.actualiza')}}" method="post">-->

                              <form  action="{{route('campanias.actualiza')}}" method="POST" id="form_html" name="form_html" style="display: inline;">
                                  {{csrf_field()}}
                          <li class="chat-persons">
                              <div class="wrapper todo-content">
                                  <h6 class="mb-1 ellipsis">{{$reg->nombre}} {{$reg->ape_pat}} {{$reg->ape_mat}}</h6>
                                  <p class="text-gray mb-0 ellipsor">DNI: {{$reg->dni}}</p>
                              </div>
                              <div class="input-group mb-2">
                                  <input type="text" name="email" id="email" required="" value="{{$reg->email??'Email'}}" placeholder="{{$reg->email??'Email'}}" class="form-control text-lowercase">
                                  <input type="hidden" name="campania_id" value="{{$campania->id}}"/>
                                  <input type="hidden" name="id" value="{{$reg->id}}"/>
                                  <input type="hidden" name="estudiante_id" value="{{$reg->estudiante_id}}"/>
                                  <button class="btn btn-sm btn-dark">
                                    Actualizar
                                  </button>
                              </div>
                          </li>
                          <!--</form>-->
                            </form>
                          @empty
                              NINGUN REGISTRO
                          @endforelse

                          <!-- list person -->
                          <li class="chat-persons">
                            <a href="#">

                              <div class="user w-100">
                                <p class="u-name">Faltan: {{$lista1_dif}} email{{$lista1_dif!=1?'s':''}} por corregir</p>
                                <p class="u-designation">Corrija los primeros para que se muestren los demás</p>
                              </div>

                            </a>
                          </li>
                          <!-- list person -->
                          <!-- list person -->
                          {{-- <li class="chat-persons">
                            <a href="#">
                              <span class="pro-pic"><img src="images/faces/face14.jpg" alt="profile image"></span>
                              <div class="user">
                                <p class="u-name">Allen Donald</p>
                                <p class="u-designation">UI/UX Designer</p>
                              </div>
                              <p class="joined-date">5 Days ago</p>
                            </a>
                          </li> --}}
                          <!-- list person -->
                        </ul>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <form action="{{route('campanias.person')}}" method="post">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h5 class="card-title mb-0">Envío Personalizado</h5>
                      <button type="submit" class="btn btn-sm btn-success ml-auto {{count($personalizados)>0?'':'disabled'}}" name="save" value="1" id="btnSave"><i class="mdi mdi-plus"></i>Enviar</button>
                    </div>
                    <div class="todo-list-container">

                            {{csrf_field()}}
                      <div class="list-wrapper">
                        <ul class="todo-list">
                          <li class="list">
                            <div class="input-group mb-2">
                                <input type="text" name="dni" id="txtDNI"  value="" placeholder="DNI" class="form-control text-uppercase">
                                <input type="hidden" name="id" value="{{$campania->id}}">
                                <button class="btn btn-sm btn-dark" name="search" value="1" id="btnSearch" type="button">
                                  BUSCAR
                                </button>
                            </div>

                          </li>
                        </ul>
                          <ul class="todo-list" id="lista2">

                          @forelse($personalizados as $reg)
                          <li class="list">
                            <div class="form-check form-check-flat todo-form-check w-100">
                              <div class="form-check-label">
                                <input id="check1" type="checkbox" class="form-check-input check_click" checked="{{$reg["check"]==1?'checked':''}}" name="checks[]" value="{{$reg["id"]}}">
                                <i class="input-helper"></i>
                                <div class="d-flex">
                                  <div class="wrapper todo-content">
                                    <h6 class="mb-1 ellipsis">
                                      {{-- <label class="custom-control-label ellipsis" for="check1"> --}}
                                        {{$reg["nombres"]}}
                                      {{-- </label> --}}
                                    </h6>
                                    <p class="text-gray mb-0 ellipsor">DNI: {{$reg["dni"]}}</p>
                                    <span class="text-success ellipsor">{{$reg["email"]}}</span>
                                  </div>
                                  <div class="action-wrapper wrapper ml-auto d-flex align-items-center">
                                    <i class="btn-close mdi mdi-close-circle-outline text-danger icon-md"></i>
                                    <i class="btn-action mdi mdi-dots-vertical text-muted icon-md"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          @empty
                          @endforelse



                        </ul>
                      </div>
                    </div>
                      </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        @section('scripts')
        <style>
        .check_click{
          left: auto !important;z-index: 999!important;margin-top: 4px!important;
        }
        </style>
        <script>
          console.log('Ejecutado.');
          var ps = [];
          $(window).ready(function(){
              $btnSearch = $("#btnSearch");
              $btnSave = $("#btnSave");
              $dni = $("#txtDNI");
              $lista = $("#lista2");
              function busca(){
                  carga();
              }
              function valida(){
                  $btnSave.submit();
              }
              $(window).on("keydown", function(event){
                  console.info(event.target);//event.target !== document.getElementById("btnSearch")&&
                  if(event.keyCode === 13&& event.target === document.getElementById("txtDNI")){
                      busca();
                      event.preventDefault();
                      //event.target.click();
                      return false;
                  }
              });
              function carga(all){
                  //$btnGrabar.attr('disabled', true);
                  $.post( "{{route('campanias.dni')}}", {
                      dni: $dni.val(),
                      campania_id: {{$campania->id??0}},
                      all: all?1:0,
                      "_token": "{{ csrf_token() }}"
                  },function(data){
                      if(!data.success){
                          swal("Advertencia", data.message, "warning").then(function() {
                              $dni.focus().select();

                          });

                      }else {
                          ps = data.data;
                          $dni.val('').focus();
                      }
                  }).done(function( data ) {

                  }).always(function(){
                      lista2();
                      //$btnGrabar.attr('disabled', false);
                  });
              }
              $btnSave.on('click',valida);
              $btnSearch.on('click',busca);
              carga(1);
              //ps.push({nombres:'Edi',email:'@gmail.com',dni:'979933',check:true,'id':21});
              //ps.push({nombres:'2Edi',email:'2@gmail.com',dni:'979933',check:true,'id':21});

              lista2();
              function lista2(){
                  $lista.empty();
                  var n = ps.length;
                  $btnSave.removeClass("disabled");
                  if(n<1)$btnSave.addClass("disabled");
                  $btnSave.attr("disabled", n<1);
                  for(var i=0; i<n;i++){
                      var data = ps[i];
                      var html =`
                  <li class="list">
                            <div class="form-check form-check-flat todo-form-check w-100">
                              <div class="form-check-label">
                                <input id="check1" type="checkbox" class="form-check-input check_click" checked="${data.check?'checked':''}" name="checks[]" value="${data.id}">
                                <i class="input-helper"></i>
                                <div class="d-flex">
                                  <div class="wrapper todo-content">
                                    <h6 class="mb-1 ellipsis">
                                      {{-- <label class="custom-control-label ellipsis" for="check1"> --}}
                      ${data.nombres}
                          {{-- </label> --}}
                      </h6>
                      <p class="text-gray mb-0 ellipsor">DNI: ${data.dni}</p>
                                    <span class="text-success ellipsor">${data.email}</span>
                                  </div>
                                  <div class="action-wrapper wrapper ml-auto d-flex align-items-center">
                                    <i class="btn-close mdi mdi-close-circle-outline text-danger icon-md"></i>
                                    <i class="btn-action mdi mdi-dots-vertical text-muted icon-md"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>`;
                      $lista.append($(html));
                  }
              }

          });



        </script>
        @endsection


        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

@endsection
