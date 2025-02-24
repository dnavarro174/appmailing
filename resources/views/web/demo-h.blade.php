@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    
    {{-- include('layout.nav_superior') --}}
    <nav class="navbar horizontal-layout-2 col-lg-12 col-12 p-0 d-flex flex-row align-items-start" style="
">
      <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <a class="navbar-brand brand-logo" href="../../index.html"><img src="http://www.urbanui.com/radiant/jquery/images/logo-default.svg" alt="logo"></a>
          <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="http://www.urbanui.com/radiant/jquery/images/logo-mini.svg" alt="logo"></a>

        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center pr-0">
          <ul class="navbar-nav header-links">
            <li class="nav-item">
              <a href="#" class="nav-link">Schedule <span class="badge badge-success ml-1">New</span></a>
            </li>
            <li class="nav-item active">
              <a href="#" class="nav-link"><i class="mdi mdi-elevation-rise"></i>Reports</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="mdi mdi-bookmark-plus-outline"></i>Score</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto dropdown-menus">
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-message-text-outline"></i>
                <span class="count bg-warning">2</span>
              </a>
              <div class="dropdown-menu dropdown-menu-left navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <div class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have 7 unread mails
                  </p>
                  <span class="badge badge-inverse-info badge-pill float-right">View all</span>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="../../images/faces/face4.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">David Grey
                      <span class="float-right font-weight-light small-text text-gray">1 Minutes ago</span>
                    </h6>
                    <p class="font-weight-light small-text mb-0">
                      The meeting is cancelled
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="../../images/faces/face2.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">Tim Cook
                      <span class="float-right font-weight-light small-text text-gray">15 Minutes ago</span>
                    </h6>
                    <p class="font-weight-light small-text mb-0">
                      New product launch
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="../../images/faces/face3.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1"> Johnson
                      <span class="float-right font-weight-light small-text text-gray">18 Minutes ago</span>
                    </h6>
                    <p class="font-weight-light small-text mb-0">
                      Upcoming board meeting
                    </p>
                  </div>
                </a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count bg-success">4</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <a class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have 4 new notifications
                  </p>
                  <span class="badge badge-pill badge-inverse-info float-right">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-success">
                      <i class="mdi mdi-alert-circle-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                    <p class="font-weight-light small-text mb-0">
                      Just now
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-warning">
                      <i class="mdi mdi-comment-text-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                    <p class="font-weight-light small-text mb-0">
                      Private message
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-info">
                      <i class="mdi mdi-email-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                    <p class="font-weight-light small-text mb-0">
                      2 days ago
                    </p>
                  </div>
                </a>
              </div>
            </li>
            {{-- <li class="nav-item mr-0">
              <a href="#" class="nav-link py-0 pr-0">
                <span class="text-black d-none d-lg-inline-block text-white mr-2">Hello, Sebastian !</span>
                <img class="img-xs rounded-circle" src="../../images/faces/face1.jpg" alt="profile image">
              </a>
            </li> --}}
            <!-- Authentication Links -->
          @guest
            <li class="nav-item dropdown d-none d-xl-inline-block">
              <a class="nav-link" href="{{ route('login') }}"><span class="mr-3">{{ __('Login') }}</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
          @else

          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="mr-3">Hola, {{ Auth::user()->name }} !</span><img class="img-xs rounded-circle" src="{{ asset('images/logo_ticketing_1.png')}}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <a class="dropdown-item p-0">
                <div class="d-flex border-bottom">
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right"><i class="mdi mdi-account-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-alarm-check mr-0 text-gray"></i></div>
                </div>
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </li>
          <li class="d-block d-md-none">
            <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 <i class="mdi mdi-logout text-white"></i>
            </a>
          </li>

          @endguest

          <!-- Authentication Links -->
          </ul>
          <button type="button" class="navbar-toggler d-block d-md-none"><i class="mdi mdi-menu"></i></button>
        </div>
      </div>
      <div class="container">
        <div class="nav-bottom">
          <ul class="navbar-nav">
            <li class="nav-item dropdown active">
              <a class="nav-link count-indicator dropdown-toggle" id="dashboard-dropdown" href="#" data-toggle="dropdown">
                
                Inicio
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="dashboard-dropdown">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 1</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 2</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 3</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 4</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="finance-dropdown" href="#" data-toggle="dropdown">
                CAII
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="finance-dropdown">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 1</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 2</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 3</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="project-dropdown" href="#" data-toggle="dropdown">
                Eventos
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="project-dropdown">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 1</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 2</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 3</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="hr-dropdown" href="#" data-toggle="dropdown">
                A.Academicas
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="hr-dropdown">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 1</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 2</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 3</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 4</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 5</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 6</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="revenue-dropdown" href="#" data-toggle="dropdown">
                Maestria
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="revenue-dropdown">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 1</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 2</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 3</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 4</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 5</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="usuarios" href="#" data-toggle="dropdown">
               <i class="mdi mdi-settings"></i>
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="usuarios">
                <ul>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Usuarios</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Roles</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link"><i class="mdi mdi-settings"></i>  Auditoria Leads</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link"><i class="mdi mdi-settings"></i>  Auditoria Programaciones</a></li>
                  <li class="dropdown-item"><a href="#" class="dropdown-link">Menu Link 5</a></li>
                </ul>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto d-none d-lg-block">
            <li class="nav-item mr-4">
              <form action="#">
                <div class="form-group mb-0">
                  <div class="input-group search-field">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
                </div>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      {{-- @ include('layout.menutop_setting_panel') --}}
      



      <!-- end menu_user -->
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->

      {{-- @ include('layout.menu_iz') --}}

  <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image"> <img src="{{URL::route('home')}}/images/logo_ticketing_1.png" alt="Logo Ticketing" /> <span class="online-status online"></span> </div>
              <div class="profile-name">
                <p class="name">Ticketing V2.0</p>
                <!-- <p class="designation">UI/UX Designer</p> -->
              </div>
              <!-- <div class="notification-panel mt-4">
                <span><i class="mdi mdi-settings"></i></span>
                <span class="count-wrapper"><i class="mdi mdi-bell"></i><span class="count top-right bg-warning">4</span></span>
                <span><i class="mdi mdi-email"></i></span>
              </div> -->
            </div>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('home')}}"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/01.png" alt="menu icon"> <span class="menu-title">Inicio</span></a> </li>
          
          {{-- <li class="nav-item">
            <a class="nav-link {{ request()->is('cursos.index') ? 'active' : '' }}" data-toggle="collapse" href="#cursos-dropdown" aria-expanded="false" aria-controls="cursos-dropdown"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/08.png" alt="menu icon"> <span class="menu-title">Cursos</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="cursos-dropdown">
              <ul class="nav flex-column sub-menu">
                @if(in_array('cursos',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('cursos.index') ? 'active' : '' }}" href="{{ route('cursos.index')}}">Cursos</a> </li>
                @endif
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_sedes.index')}}">Sede</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('cat_cursos.index')}}">Categoría Curso</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_modalidades.index')}}">Modalidad</a> </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tc_tipos.index')}}">Tipo</a> </li>
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["cursos"]["permisos"]["reportes"]["permiso"]   ) &&  session("permisosTotales")["cursos"]["permisos"]["reportes"]["permiso"] == 1)
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                
                @if(isset( session("permisosTotales")["cursos"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["cursos"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
              </ul>
            </div>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->is('programaciones.index') ? 'active' : '' }}" data-toggle="collapse" href="#apps-dropdown" aria-expanded="false" aria-controls="apps-dropdown"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/09.png" alt="menu icon"> <span class="menu-title">Programaciones</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="apps-dropdown">
              <ul class="nav flex-column sub-menu">
                @if(in_array('programaciones',session('permisosMenu')))
                <li class="nav-item"><a class="nav-link {{ request()->is('programaciones.index') ? 'active' : '' }}" href="{{ route('programaciones.index')}}">Programaciones</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->is('actividades') ? 'active' : '' }}" href="{{ route('actividades.index')}}">Actividades</a></li>
                @endif
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["programaciones"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["programaciones"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
       
                @if(isset( session("permisosTotales")["programaciones"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["programaciones"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('estudiantes') ? 'active' : '' }}" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/02.png" alt="menu icon"> <span class="menu-title">Leads / Registros</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="page-layouts">
              <ul class="nav flex-column sub-menu">
                @if(in_array('estudiantes',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('estudiantes.index') ? 'active' : '' }}" href="{{ route('estudiantes.index')}}">Registros</a></li>
                {{-- <li class="nav-item "> <a class="nav-link" href="{{ route('entidades.index') }}">Entidades</a></li> --}}
                <li class="nav-item"> <a class="nav-link" href="{{ route('tipo_doc.index') }}">Tipo de Documento</a></li>
                @endif
                {{-- <li class="nav-item"> <a class="nav-link" href="#">Ubigeo</a></li> --}}
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["estudiantes"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["estudiantes"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                @if(isset( session("permisosTotales")["estudiantes"]["permisos"]["informes"]["permiso"]   ) && session("permisosTotales")["estudiantes"]["permisos"]["informes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Informes</a></li>
                @endif
                
              </ul>
            </div>
          </li>
          @if(in_array('ponentes',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('ponentes') ? 'active' : '' }}" data-toggle="collapse" href="#sidebar-layouts" aria-expanded="false" aria-controls="sidebar-layouts"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/04.png" alt="menu icon"> <span class="menu-title">Ponentes</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="sidebar-layouts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('ponentes') ? 'active' : '' }}" href="{{ route('ponentes.index')}}">Ponentes</a></li>
              </ul>
            </div>
          </li>
          @endif
          {{-- @if(in_array('eventos',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('eventos') ? 'active' : '' }}" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/17.png" alt="menu icon"> <span class="menu-title">Evento</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('eventos') ? 'active' : '' }}" href="{{ route('eventos.index')}}">Evento</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('tipo_eventos.index')}}">Tipo de Eventos</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
              </ul>
            </div>
          </li>
          @endif --}}

          @if(in_array('historiaemail',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('historiaemail') ? 'active' : '' }}" data-toggle="collapse" href="#historiaemail" aria-expanded="false" aria-controls="historiaemail"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/20.png" alt="menu icon"> <span class="menu-title">Mailing</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="historiaemail">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('historiaemail') ? 'active' : '' }}" href="{{ route('historiaemail.index')}}">Historia Email</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('plantillaemail.index')}}">Plantilla HTML</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('estudiantes.enviar_email')}}">Enviar Email</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('form_confirmacion.index')}}">Pre-Inscritos</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('newsletter.index')}}">Newsletter</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Solicito Información</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('asistencia',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link {{ request()->is('asistencia') ? 'active' : '' }}" data-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/07.png" alt="menu icon"> <span class="menu-title">Asistencia</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->is('asistencia') ? 'active' : '' }}" href="{{ route('asistencia.index')}}">Registrar Asistencia</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('maestria',session('permisosMenu')))
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#maestria" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/14.png" alt="menu icon"> <span class="menu-title">Maestria </span><i class="menu-arrow"></i></a>
            <div class="collapse" id="maestria">
              <ul class="nav flex-column sub-menu">
                @if(in_array('maestria',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('maestria.index')}}">Maestria</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('auditoria',session('permisosMenu')) )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auditoria" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/15.png" alt="menu icon"> <span class="menu-title">Auditoría </span><i class="menu-arrow"></i></a>
            <div class="collapse" id="auditoria">
              <ul class="nav flex-column sub-menu">
                @if(in_array('auditoria',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('auditoriae.index')}}">Auditoría Leads</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('auditoriap.index')}}">Auditoría Programaciones</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          @if(in_array('usuarios',session('permisosMenu')) or in_array('roles',session('permisosMenu')) )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#users-roles" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/05.png" alt="menu icon"> <span class="menu-title">Usuarios / Roles</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="users-roles">
              <ul class="nav flex-column sub-menu">
                @if(in_array('usuarios',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('usuarios.index')}}">Usuarios</a></li>
                @endif
                @if(in_array('roles',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('roles.index')}}">Roles</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/19.png" alt="menu icon"> <span class="menu-title">Form elements</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/advanced_elements.html">Advanced Elements</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/validation.html">Validation</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/wizard.html">Wizard</a></li>
              </ul>
            </div>
          </li> --}}
      
          
        </ul>
      </nav>


      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper" style="padding: 1.5rem 1.7rem;">
          <div class="row">

            @if(in_array('estudiantes',session('permisosMenu')))
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-database text-danger icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      {{-- <h1>{{ request()->is('/') ? 'Estas en el home' : 'No estas home' }}</h1> --}}
                      <h4 class="card-text mb-0"><a href="#">Base de Datos</a></h4>
                      <p class="card-text mb-0">Todos los registros de la ENC.</p>
                      <!-- <div class="fluid-container">
                        <h3 class="card-title mb-0">$65,650</h3>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif






            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-account-plus text-info icon-lg"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="#">CAII</a></h4>
                      <p class="card-text mb-0">Inscritos al Evento Caii 2019---</p>

                      
                    </div>
                  </div>
                </div>
              </div>
            </div>

            
          </div>
          
          
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        {{-- @ include('layout.footer') --}}

        <footer class="footer">
          <div class="container-fluid clearfix">
            {{-- <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="https://enc-ticketing.org/tkt/">Ticketing V2.0</a>. Todos los derechos reservados.</span> --}}
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Desarrollado por ENC {{-- <i class="mdi mdi-heart text-danger"> --}}</i></span>
            <!-- <p>Dany Navarro Manta </p>-->
          </div>
        </footer>
        
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