@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    
    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            @if(in_array('bd',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-database text-danger icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      {{-- <h1>{{ request()->is('/') ? 'Estas en el home' : 'No estas home' }}</h1> --}}
                      <h4 class="card-text mb-0"><a href="{{ route('bd.index')}}">Base de Datos</a></h4>
                      <p class="card-text mb-0">Todos los registros de la ENC</p>
                      <!-- <div class="fluid-container">
                        <h3 class="card-title mb-0">$65,650</h3>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
  
            @if(in_array('estudiantes',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-account-plus text-info icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('caii.index')}}">CAII</a></h4>{{-- caii.index --}}
                      <p class="card-text mb-0">Registros de participantes {{date('Y')}}</p>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          
                        </p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('eventos',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-email text-warning icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{route('eventos.index')}}">Eventos</a></h4>
                      <p class="card-text mb-0">Todos los eventos registrados en el sistema.</p>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('eventos-es',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-checkbox-multiple-marked-outline text-dark icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{route('eventos-es.index')}}">Eventos Especiales</a></h4>
                      <p class="card-text mb-0">Este evento permite generar preguntas al público.</p>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('crm',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center ">
                    <div class="highlight-icon bg-dark mr-3">
                      <i class="mdi mdi-cube-send text-light icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('campanias.index') }}">Mailing - CRM</a></h4>
                      <div class="fluid-container">
                        <p class="card-text mb-0">Envio de mailing.</p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('asistencia--',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center ">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-calendar text-success icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('asistencia.index') }}">Asistencia</a></h4>
                      <div class="fluid-container">
                        <p class="card-text mb-0">Registre la asistencia a cursos, programas y eventos</p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @if(in_array('academico--',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-light mr-3">
                      {{-- <i class="mdi mdi-account text-info icon-lg"></i> --}}
                      <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/14.png" alt="menu icon">
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('academico.index') }}">Actividades Académicas</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Registro de cursos y programas de la ENC.
                        </p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('maestria',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-light mr-3">
                      {{-- <i class="mdi mdi-account text-info icon-lg"></i> --}}
                      <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/14.png" alt="menu icon">
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('grupo-maestria.index') }}">Maestría</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Cree periodos y cree formuarios para la Maestría de la ENC.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @if(in_array('einvestigacion',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-clipboard-account text-dark icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('grupo-estudio-investigacion.index') }}">Subdirección de Estudios e Investigaciones</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Cree nuevos formularios y almacene por separado a los participantes inscritos.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(in_array('dj',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-success mr-3">
                      <i class="mdi mdi-account-card-details text-light icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('grupo-dj.index') }}">Form. Declaraciones Juradas</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Permite crear periodos para poder <br>agrupar los formularios de las DDJJ.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @if(in_array('formdoc',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-danger mr-3">
                      <i class="mdi mdi-account-multiple-outline text-light icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('grupo-doc.index') }}">Formulario Docentes</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Permite crear periodos para poder <br>agrupar los formularios de los docentes.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            
            @if(in_array('correos',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-account-key text-dark icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('correos.index') }}">Correos ENC</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Permite crear generar correos @enc.edu.pe
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @if(in_array('usuarios',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-left ">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-account text-info icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('usuarios.index') }}">Usuarios</a></h4>

                      <div class="fluid-container">
                        <p class="card-text mb-0">Administre los usuarios del sistema.</p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
          
          
        </div>
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


  {{-- code js --}}

@endsection